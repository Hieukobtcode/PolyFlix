<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kết Quả Test MoMo - PolyFlix</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Arial', sans-serif;
        }

        .card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .result-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin: 0 auto 20px;
        }

        .success {
            background: linear-gradient(45deg, #28a745, #20c997);
            color: white;
        }

        .failed {
            background: linear-gradient(45deg, #dc3545, #fd7e14);
            color: white;
        }

        .info-table {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
        }

        .btn-home {
            background: linear-gradient(45deg, #667eea, #764ba2);
            border: none;
            color: white;
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .btn-home:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .btn-test-again {
            background: linear-gradient(45deg, #d91a72, #a93592);
            border: none;
            color: white;
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .btn-test-again:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(217, 26, 114, 0.4);
            color: white;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body p-5 text-center">
                        @if(isset($result['resultCode']) && $result['resultCode'] == 0)
                        <div class="result-icon success">
                            <i class="fas fa-check"></i>
                        </div>
                        <h3 class="text-success mb-4">Thanh Toán Thành Công!</h3>
                        <p class="text-muted mb-4">Giao dịch MoMo đã được xử lý thành công</p>
                        @else
                        <div class="result-icon failed">
                            <i class="fas fa-times"></i>
                        </div>
                        <h3 class="text-danger mb-4">Thanh Toán Thất Bại!</h3>
                        <p class="text-muted mb-4">
                            {{ $result['message'] ?? 'Giao dịch không thành công' }}
                        </p>
                        @endif

                        <div class="info-table text-start">
                            <h5 class="mb-3">Thông tin giao dịch:</h5>
                            <div class="row">
                                <div class="col-12">
                                    <table class="table table-borderless">
                                        <tbody>
                                            @if(isset($result['orderId']))
                                            <tr>
                                                <td><strong>Mã đơn hàng:</strong></td>
                                                <td>{{ $result['orderId'] }}</td>
                                            </tr>
                                            @endif

                                            @if(isset($result['amount']))
                                            <tr>
                                                <td><strong>Số tiền:</strong></td>
                                                <td>{{ number_format($result['amount']) }}đ</td>
                                            </tr>
                                            @endif

                                            @if(isset($result['transId']))
                                            <tr>
                                                <td><strong>Mã giao dịch MoMo:</strong></td>
                                                <td>{{ $result['transId'] }}</td>
                                            </tr>
                                            @endif

                                            @if(isset($result['resultCode']))
                                            <tr>
                                                <td><strong>Mã kết quả:</strong></td>
                                                <td>
                                                    <span class="badge {{ $result['resultCode'] == 0 ? 'bg-success' : 'bg-danger' }}">
                                                        {{ $result['resultCode'] }}
                                                    </span>
                                                </td>
                                            </tr>
                                            @endif

                                            @if(isset($result['responseTime']))
                                            <tr>
                                                <td><strong>Thời gian phản hồi:</strong></td>
                                                <td>{{ $result['responseTime'] }}</td>
                                            </tr>
                                            @endif

                                            @if(isset($result['payType']))
                                            <tr>
                                                <td><strong>Phương thức thanh toán:</strong></td>
                                                <td>{{ $result['payType'] }}</td>
                                            </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 pt-4 border-top">
                            <h6 class="text-muted mb-3">Dữ liệu callback đầy đủ:</h6>
                            <details class="text-start">
                                <summary class="btn btn-sm btn-outline-secondary">Xem chi tiết</summary>
                                <pre class="mt-3 p-3 bg-light rounded text-sm">{{ json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                            </details>
                        </div>

                        <div class="mt-4 d-flex gap-3 justify-content-center">
                            <a href="{{ route('momo.test') }}" class="btn btn-test-again">
                                <i class="fas fa-redo me-2"></i>
                                Test Lại
                            </a>
                            <a href="{{ route('home') }}" class="btn btn-home">
                                <i class="fas fa-home me-2"></i>
                                Về Trang Chủ
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/your-fontawesome-kit.js" crossorigin="anonymous"></script>
</body>

</html>