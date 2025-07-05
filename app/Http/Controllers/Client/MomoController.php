<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\DatVe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MomoController extends Controller
{
    private $partnerCode = "MOMO";
    private $accessKey = "F8BBA842ECF85";
    private $secretKey = "K951B6PE1waDMi640xX08PD3vg6EkVlz";
    private $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";

    /**
     * Tạo thanh toán MoMo cho đặt vé
     */
    public function createPayment($datVeId, $amount)
    {
        try {
            $orderId = 'POLYFLIX_' . $datVeId . '_' . uniqid(); // Tạo ID duy nhất
            $requestId = uniqid((string)time(), true);          // Tránh trùng request

            $orderInfo = "Thanh toán vé xem phim PolyFlix - Mã đặt vé: " . $datVeId;
            $redirectUrl = route('client.thanh-toan.momo.callback');
            $ipnUrl = route('client.thanh-toan.momo.callback');

            $extraData = base64_encode(json_encode(['dat_ve_id' => $datVeId])); // Encode đúng cách
            $requestType = "captureWallet";

            // Chuỗi tạo chữ ký (signature)
            $rawHash = "accessKey=" . $this->accessKey .
                "&amount=" . $amount .
                "&extraData=" . $extraData .
                "&ipnUrl=" . $ipnUrl .
                "&orderId=" . $orderId .
                "&orderInfo=" . $orderInfo .
                "&partnerCode=" . $this->partnerCode .
                "&redirectUrl=" . $redirectUrl .
                "&requestId=" . $requestId .
                "&requestType=" . $requestType;

            $signature = hash_hmac("sha256", $rawHash, $this->secretKey);

            $data = [
                'partnerCode' => $this->partnerCode,
                'accessKey' => $this->accessKey,
                'requestId' => $requestId,
                'amount' => $amount,
                'orderId' => $orderId,
                'orderInfo' => $orderInfo,
                'redirectUrl' => $redirectUrl,
                'ipnUrl' => $ipnUrl,
                'extraData' => $extraData,
                'requestType' => $requestType,
                'signature' => $signature,
                'lang' => 'vi'
            ];

            Log::info('MoMo request data:', $data);

            $result = $this->execPostRequest($this->endpoint, json_encode($data));
            $jsonResult = json_decode($result, true);

            Log::info('MoMo response:', $jsonResult);

            // Kiểm tra lỗi phía MoMo (ví dụ lỗi 1005)
            if (isset($jsonResult['resultCode']) && $jsonResult['resultCode'] != 0) {
                Log::warning('MoMo payment error - Code: ' . $jsonResult['resultCode'] . ', Message: ' . $jsonResult['message']);
            }

            return $jsonResult;
        } catch (\Exception $e) {
            Log::error('Lỗi tạo thanh toán MoMo: ' . $e->getMessage());
            throw $e;
        }
    }


    /**
     * Xử lý callback từ MoMo
     */
    public function callback(Request $request)
    {
        Log::info('MoMo callback received:', $request->all());

        try {
            $resultCode = $request->get('resultCode');
            $extraData = $request->get('extraData');
            $orderId = $request->get('orderId');
            $amount = $request->get('amount');
            $message = $request->get('message');

            // Giải mã extraData để lấy dat_ve_id
            $extraDataDecoded = json_decode($extraData, true);
            $datVeId = $extraDataDecoded['dat_ve_id'] ?? null;

            if (!$datVeId) {
                Log::error('Không tìm thấy dat_ve_id trong extraData');
                return redirect()->route('home')->with('error', 'Có lỗi xảy ra trong quá trình thanh toán!');
            }

            $datVe = DatVe::findOrFail($datVeId);

            DB::beginTransaction();
            try {
                if ($resultCode == 0) {
                    // Thanh toán thành công
                    $datVe->update([
                        'trang_thai' => 'Đã thanh toán',
                        'ma_giao_dich' => $orderId,
                        'ngay_thanh_toan' => now()
                    ]);

                    DB::commit();

                    return redirect()->route('client.dat-ve.ket-qua', $datVe->id)
                        ->with('success', 'Thanh toán MoMo thành công!');
                } else {
                    // Thanh toán thất bại
                    $datVe->update([
                        'trang_thai' => 'Thanh toán thất bại',
                        'ghi_chu' => 'MoMo: ' . $message
                    ]);

                    DB::commit();

                    return redirect()->route('client.thanh-toan.index', $datVe->id)
                        ->with('error', 'Thanh toán MoMo thất bại: ' . $message);
                }
            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Lỗi xử lý callback MoMo: ' . $e->getMessage());

            return redirect()->route('home')
                ->with('error', 'Có lỗi xảy ra trong quá trình xử lý thanh toán!');
        }
    }

    /**
     * Trang test MoMo (chỉ cho development)
     */
    public function createTestPayment(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1000'
        ]);

        try {
            $amount = $request->amount;
            $orderId = 'TEST_' . time();
            $requestId = time() . "";
            $orderInfo = "Test thanh toán MoMo - Số tiền: " . number_format($amount) . "đ";
            $redirectUrl = route('momo.test.callback');
            $ipnUrl = route('momo.test.callback');
            $extraData = json_encode(['test' => true]);
            $requestType = "captureWallet";

            // Tạo signature
            $rawHash = "accessKey=" . $this->accessKey .
                "&amount=" . $amount .
                "&extraData=" . $extraData .
                "&ipnUrl=" . $ipnUrl .
                "&orderId=" . $orderId .
                "&orderInfo=" . $orderInfo .
                "&partnerCode=" . $this->partnerCode .
                "&redirectUrl=" . $redirectUrl .
                "&requestId=" . $requestId .
                "&requestType=" . $requestType;

            $signature = hash_hmac("sha256", $rawHash, $this->secretKey);

            $data = [
                'partnerCode' => $this->partnerCode,
                'accessKey' => $this->accessKey,
                'requestId' => $requestId,
                'amount' => $amount,
                'orderId' => $orderId,
                'orderInfo' => $orderInfo,
                'redirectUrl' => $redirectUrl,
                'ipnUrl' => $ipnUrl,
                'extraData' => $extraData,
                'requestType' => $requestType,
                'signature' => $signature,
                'lang' => 'vi'
            ];

            $result = $this->execPostRequest($this->endpoint, json_encode($data));
            $jsonResult = json_decode($result, true);

            if (isset($jsonResult['payUrl']) && $jsonResult['resultCode'] == 0) {
                return redirect($jsonResult['payUrl']);
            } else {
                return back()->with('error', 'Không thể tạo link thanh toán: ' . ($jsonResult['message'] ?? 'Lỗi không xác định'));
            }
        } catch (\Exception $e) {
            Log::error('Lỗi test MoMo: ' . $e->getMessage());
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Callback cho test MoMo
     */
    public function testCallback(Request $request)
    {
        return view('momo-test-result', [
            'result' => $request->all()
        ]);
    }

    /**
     * Gửi HTTP POST request
     */
    private function execPostRequest($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data)
            ],
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false
        ]);

        $result = curl_exec($ch);

        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            throw new \Exception('CURL Error: ' . $error);
        }

        curl_close($ch);
        return $result;
    }


    
}
