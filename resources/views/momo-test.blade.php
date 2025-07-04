<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Thanh Toán MoMo - PolyFlix</title>
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

        .btn-momo {
            background: linear-gradient(45deg, #d91a72, #a93592);
            border: none;
            color: white;
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .btn-momo:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(217, 26, 114, 0.4);
            color: white;
        }

        .momo-logo {
            width: 60px;
            height: 60px;
            background: linear-gradient(45deg, #d91a72, #a93592);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 18px;
            margin: 0 auto 20px;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body p-5 text-center">
                        <div class="momo-logo">
                            MoMo
                        </div>

                        <h3 class="mb-4">Test Thanh Toán MoMo Sandbox</h3>
                        <p class="text-muted mb-4">Sử dụng môi trường thử nghiệm của MoMo để test thanh toán</p>

                        @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                        @endif

                        <form action="{{ route('momo.test.create') }}" method="POST">
                            @csrf

                            <div class="form-group mb-4">
                                <label for="amount" class="form-label fw-bold">Số tiền cần thanh toán (VNĐ)</label>
                                <input type="number"
                                    class="form-control form-control-lg text-center"
                                    id="amount"
                                    name="amount"
                                    value="10000"
                                    min="1000"
                                    max="50000000"
                                    required
                                    style="border-radius: 15px;">
                                <small class="text-muted">Tối thiểu 1,000đ - Tối đa 50,000,000đ</small>
                            </div>

                            <button type="submit" class="btn btn-momo btn-lg w-100">
                                <i class="fas fa-credit-card me-2"></i>
                                Thanh Toán Bằng MoMo
                            </button>
                        </form>

                        <div class="mt-4 pt-4 border-top">
                            <h6 class="text-primary">🎭 Test Simulate (Bypass MoMo)</h6>
                            <p class="text-muted small">Giả lập kết quả thanh toán để test logic callback</p>

                            <div class="d-grid gap-2 mb-3">
                                <a href="{{ route('momo.test.callback') }}?resultCode=0&orderId=TEST_SUCCESS_{{ time() }}&amount=50000&message=Successful.&extraData=%7B%22test%22%3Atrue%7D"
                                    class="btn btn-success">
                                    ✅ Simulate Thành công
                                </a>

                                <a href="{{ route('momo.test.callback') }}?resultCode=1006&orderId=TEST_FAILED_{{ time() }}&amount=50000&message=Transaction failed&extraData=%7B%22test%22%3Atrue%7D"
                                    class="btn btn-danger">
                                    ❌ Simulate Thất bại
                                </a>

                                <a href="{{ route('momo.test.callback') }}?resultCode=1000&orderId=TEST_CANCEL_{{ time() }}&amount=50000&message=Transaction cancelled&extraData=%7B%22test%22%3Atrue%7D"
                                    class="btn btn-warning">
                                    🚫 Simulate Hủy bỏ
                                </a>
                            </div>
                        </div>

                        <div class="mt-4 pt-4 border-top">
                            <h6 class="text-muted">📖 Hướng dẫn test</h6>
                            <div class="alert alert-info text-start">
                                <strong>⚠️ QR Code không hoạt động với app MoMo mobile!</strong><br>
                                <small>
                                    • <strong>Cách 1:</strong> Trên trang MoMo, click nút "Thanh toán" (không quét QR)<br>
                                    • <strong>Cách 2:</strong> Dùng các nút Simulate ở trên<br>
                                    • <strong>Cách 3:</strong> Test trên mobile browser (không phải app)
                                </small>
                            </div>
                        </div>

                        <div class="mt-3 pt-3 border-top">
                            <h6 class="text-muted">Thông tin Sandbox</h6>
                            <small class="text-muted">
                                • Partner Code: MOMO<br>
                                • Môi trường: Test (Sandbox)<br>
                                • Không thu phí thực tế
                            </small>
                        </div>

                        <div class="mt-3">
                            <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                                ← Quay về trang chủ
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