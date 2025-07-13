<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\DatVe;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ZaloPayController extends Controller
{
    protected $config;

    public function __construct()
    {
        $this->config = config('payments.zalopay');
    }

    /**
     * Tạo payment request tới ZaloPay
     */
    public function createPayment($datVeId, $amount, $description = null)
    {
        try {
            // Tạo transaction record
            $transactionCode = Transaction::generateTransactionCode('ZLP');

            $transaction = Transaction::create([
                'transaction_code' => $transactionCode,
                'dat_ve_id' => $datVeId,
                'user_id' => Auth::id(),
                'payment_method' => 'zalopay',
                'amount' => $amount,
                'status' => 'pending'
            ]);

            // Chuẩn bị dữ liệu cho ZaloPay (app_trans_id phải ngắn hơn)
            $appTransId = date('ymd') . '_' . time();
            $embedData = json_encode([
                'redirecturl' => $this->config['redirect_url'] . '?transaction=' . $transactionCode
            ]);

            $order = [
                'app_id' => (int)$this->config['app_id'],
                'app_trans_id' => $appTransId,
                'app_user' => 'user_' . Auth::id(),
                'app_time' => round(microtime(true) * 1000),
                'item' => json_encode([
                    [
                        'itemid' => 'ticket_' . $datVeId,
                        'itemname' => 'Ve xem phim PolyFlix',
                        'itemprice' => (int)$amount,
                        'itemquantity' => 1
                    ]
                ]),
                'embed_data' => $embedData,
                'amount' => (int)$amount,
                'description' => 'Thanh toan ve xem phim PolyFlix #' . $datVeId,
                'bank_code' => '',
                'callback_url' => $this->config['callback_url']
            ];

            // Tạo MAC (Message Authentication Code)
            $data = $order['app_id'] . '|' . $order['app_trans_id'] . '|' . $order['app_user'] . '|' .
                $order['amount'] . '|' . $order['app_time'] . '|' . $order['embed_data'] . '|' . $order['item'];
            $order['mac'] = hash_hmac('sha256', $data, $this->config['key1']);

            Log::info('ZaloPay MAC Data String:', ['data' => $data]);
            Log::info('ZaloPay Request Data:', $order);

            // Gửi request tới ZaloPay
            $response = Http::timeout(30)
                ->withOptions([
                    'verify' => false, // Tắt SSL verify cho development
                ])
                ->post($this->config['endpoint'], $order);

            if (!$response->successful()) {
                throw new \Exception('Failed to connect to ZaloPay API');
            }

            $result = $response->json();
            Log::info('ZaloPay Response:', $result);

            if ($result['return_code'] == 1) {
                // Cập nhật transaction với thông tin từ ZaloPay
                $transaction->update([
                    'gateway_order_id' => $appTransId,
                    'payment_url' => $result['order_url'],
                    'gateway_response' => $result,
                    'status' => 'processing'
                ]);

                return [
                    'success' => true,
                    'order_url' => $result['order_url'],
                    'app_trans_id' => $appTransId,
                    'transaction_code' => $transactionCode
                ];
            } else {
                $transaction->update([
                    'status' => 'failed',
                    'gateway_response' => $result,
                    'note' => $result['return_message'] ?? 'ZaloPay creation failed'
                ]);

                throw new \Exception($result['return_message'] ?? 'ZaloPay payment creation failed');
            }
        } catch (\Exception $e) {
            Log::error('ZaloPay Payment Creation Error: ' . $e->getMessage());

            if (isset($transaction)) {
                $transaction->update([
                    'status' => 'failed',
                    'note' => $e->getMessage()
                ]);
            }

            throw $e;
        }
    }

    /**
     * Xử lý callback từ ZaloPay
     */
    public function handleCallback(Request $request)
    {
        Log::info('ZaloPay Callback Received:', $request->all());

        try {
            $data = $request->all();

            // Verify MAC
            $reqMac = $data['mac'];
            $calcMac = hash_hmac('sha256', $data['data'], $this->config['key2']);

            if (strcmp($reqMac, $calcMac) !== 0) {
                Log::error('ZaloPay MAC verification failed');
                return response()->json(['return_code' => -1, 'return_message' => 'mac not equal']);
            }

            $dataJson = json_decode($data['data'], true);
            $appTransId = $dataJson['app_trans_id'];

            // Tìm transaction theo app_trans_id
            $transaction = Transaction::where('gateway_order_id', $appTransId)->first();

            if (!$transaction) {
                Log::error('Transaction not found for app_trans_id: ' . $appTransId);
                return response()->json(['return_code' => 0, 'return_message' => 'success']);
            }

            DB::beginTransaction();
            try {
                // Cập nhật transaction
                $transaction->update([
                    'status' => 'success',
                    'paid_at' => Carbon::now(),
                    'gateway_transaction_id' => $dataJson['zp_trans_id'],
                    'gateway_response' => array_merge($transaction->gateway_response ?? [], $dataJson)
                ]);

                // Cập nhật trạng thái đặt vé
                $datVe = DatVe::find($transaction->dat_ve_id);
                if ($datVe) {
                    $datVe->update(['trang_thai' => 'Đã thanh toán']);
                }

                DB::commit();
                Log::info('ZaloPay payment completed successfully for transaction: ' . $transaction->transaction_code);

                return response()->json(['return_code' => 1, 'return_message' => 'success']);
            } catch (\Exception $e) {
                DB::rollback();
                Log::error('Error processing ZaloPay callback: ' . $e->getMessage());
                return response()->json(['return_code' => 0, 'return_message' => 'fail']);
            }
        } catch (\Exception $e) {
            Log::error('ZaloPay Callback Error: ' . $e->getMessage());
            return response()->json(['return_code' => 0, 'return_message' => 'fail']);
        }
    }

    /**
     * Kiểm tra trạng thái thanh toán từ ZaloPay
     */
    public function queryPayment($appTransId)
    {
        try {
            $data = $this->config['app_id'] . '|' . $appTransId . '|' . $this->config['key1'];
            $mac = hash_hmac('sha256', $data, $this->config['key1']);

            $postData = [
                'app_id' => $this->config['app_id'],
                'app_trans_id' => $appTransId,
                'mac' => $mac
            ];

            $response = Http::timeout(30)
                ->withOptions([
                    'verify' => false, // Tắt SSL verify cho development
                ])
                ->post($this->config['query_endpoint'], $postData);

            if (!$response->successful()) {
                throw new \Exception('Failed to query ZaloPay API');
            }

            $result = $response->json();
            Log::info('ZaloPay Query Response:', $result);

            return $result;
        } catch (\Exception $e) {
            Log::error('ZaloPay Query Error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Xử lý redirect sau thanh toán
     */
    public function handleReturn(Request $request)
    {
        $transactionCode = $request->get('transaction');

        if (!$transactionCode) {
            return redirect()->route('home')->with('error', 'Không tìm thấy thông tin giao dịch');
        }

        try {
            $transaction = Transaction::where('transaction_code', $transactionCode)->first();

            if (!$transaction) {
                return redirect()->route('home')->with('error', 'Giao dịch không tồn tại');
            }

            $datVe = DatVe::find($transaction->dat_ve_id);

            if (!$datVe) {
                return redirect()->route('home')->with('error', 'Không tìm thấy thông tin đặt vé');
            }

            // Kiểm tra trạng thái thanh toán từ ZaloPay
            if ($transaction->status === 'processing') {
                try {
                    $queryResult = $this->queryPayment($transaction->gateway_order_id);

                    if ($queryResult['return_code'] == 1) {
                        DB::beginTransaction();
                        try {
                            $transaction->update([
                                'status' => 'success',
                                'paid_at' => Carbon::now(),
                                'gateway_response' => array_merge($transaction->gateway_response ?? [], $queryResult)
                            ]);

                            $datVe->update(['trang_thai' => 'Đã thanh toán']);
                            DB::commit();
                        } catch (\Exception $e) {
                            DB::rollback();
                            Log::error('Error updating payment status: ' . $e->getMessage());
                        }
                    }
                } catch (\Exception $e) {
                    Log::error('Error querying ZaloPay status: ' . $e->getMessage());
                }
            }

            // Chuyển hướng tới trang kết quả
            if ($transaction->isSuccess()) {
                return redirect()->route('client.dat-ve.ket-qua', $datVe->id)
                    ->with('success', 'Thanh toán thành công!');
            } else {
                return redirect()->route('client.thanh-toan.index', $datVe->id)
                    ->with('error', 'Thanh toán thất bại! Vui lòng thử lại.');
            }
        } catch (\Exception $e) {
            Log::error('ZaloPay Return Handler Error: ' . $e->getMessage());
            return redirect()->route('home')->with('error', 'Có lỗi xảy ra trong quá trình xử lý thanh toán');
        }
    }
}
