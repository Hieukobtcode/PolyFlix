<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\DatVe;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class VNPayController extends Controller
{
    protected $config;

    public function __construct()
    {
        $this->config = config('payments.vnpay');
    }

    /**
     * Tạo payment URL cho VNPay
     */
    public function createPayment($datVeId, $amount, $description = null, $bankCode = '')
    {
        try {
            // Tạo transaction record
            $transactionCode = Transaction::generateTransactionCode('VNP');
            
            $transaction = Transaction::create([
                'transaction_code' => $transactionCode,
                'dat_ve_id' => $datVeId,
                'user_id' => Auth::id(),
                'payment_method' => 'vnpay',
                'amount' => $amount,
                'status' => 'pending'
            ]);

            // Chuẩn bị dữ liệu cho VNPay
            $orderId = date('YmdHis') . '_' . $transactionCode;
            $orderInfo = $description ?: 'Thanh toan ve xem phim PolyFlix #' . $datVeId;
            $orderType = 'billpayment';

            $inputData = [
                'vnp_Version' => $this->config['version'],
                'vnp_TmnCode' => $this->config['tmn_code'],
                'vnp_Amount' => $amount * 100, // VNPay yêu cầu amount * 100
                'vnp_Command' => $this->config['command'],
                'vnp_CreateDate' => date('YmdHis'),
                'vnp_CurrCode' => $this->config['currency_code'],
                'vnp_IpAddr' => request()->ip(),
                'vnp_Locale' => $this->config['locale'],
                'vnp_OrderInfo' => $orderInfo,
                'vnp_OrderType' => $orderType,
                'vnp_ReturnUrl' => $this->config['return_url'] . '?transaction=' . $transactionCode,
                'vnp_TxnRef' => $orderId,
            ];

            // Thêm bank code nếu có (cho thanh toán thẻ ATM)
            if (!empty($bankCode)) {
                $inputData['vnp_BankCode'] = $bankCode;
            }

            // Sắp xếp dữ liệu theo thứ tự alphabet
            ksort($inputData);
            
            $hashData = '';
            $query = '';
            
            foreach ($inputData as $key => $value) {
                $hashData .= ($hashData ? '&' : '') . urlencode($key) . '=' . urlencode($value);
                $query .= ($query ? '&' : '') . urlencode($key) . '=' . urlencode($value);
            }

            // Tạo chữ ký bảo mật
            $secureHash = hash_hmac('sha512', $hashData, $this->config['hash_secret']);
            $paymentUrl = $this->config['url'] . '?' . $query . '&vnp_SecureHash=' . $secureHash;

            // Cập nhật transaction với thông tin từ VNPay
            $transaction->update([
                'gateway_order_id' => $orderId,
                'payment_url' => $paymentUrl,
                'gateway_response' => $inputData,
                'status' => 'processing'
            ]);

            Log::info('VNPay Payment URL Created:', [
                'transaction_code' => $transactionCode,
                'order_id' => $orderId,
                'amount' => $amount,
                'payment_url' => $paymentUrl
            ]);
