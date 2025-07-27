@extends('layouts.auth')
@section('styles')
    <style>
        /* Giao diện tổng thể */
        .login-container {
            width: 600px;
            margin: 50px auto;
            padding: 20px;
            border-radius: 10px;
        }

        /* Phần tiêu đề "HOÀN TẤT" */
        .tab-main {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .tab-main .tab {
            background: white;
            color: black;
            font-weight: bold;
            padding: 12px 30px;
            border-radius: 8px 8px 0 0;
            font-size: 18px;
            border: 2px solid #eee;
            border-bottom: none;
        }

        /* Nội dung bên trong */
        .tab-content {
            background-color: #fff;
            padding: 30px;
            border: 2px solid #eee;
            border-radius: 0 0 10px 10px;
        }

        /* Hộp thông báo cảm ơn */
        .custom-popup {
            background: linear-gradient(135deg, #5a3fd8, #2673dd);
            color: white;
            border-radius: 12px;
            padding: 30px 20px;
            width: 100%;
            font-family: "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            font-size: 16px;
            animation: fadeIn 0.5s ease-in-out;
        }

        /* Nút trở về */
        .custom-confirm-button {
            position: relative;
            width: 100%;
            height: 50px;
            margin-top: 30px;
            border-radius: 8px;
            background-color: transparent;
            color: #ffd700;
            font-weight: bold;
            font-size: 16px;
            border: 2px solid #ffd700;
            transition: all 0.3s ease-in-out;
            cursor: pointer;
            z-index: 1;
            overflow: hidden;
        }

        .custom-confirm-button::before {
            content: "";
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(to right, #ff6600, #ffcc00);
            z-index: -1;
            transition: left 0.4s ease;
        }

        .custom-confirm-button:hover::before {
            left: 0;
        }

        .custom-confirm-button:hover {
            color: white;
        }

        /* Hiệu ứng xuất hiện mượt */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endsection
@section('content')
    <div class="login-container">
        <div class="tab-main">
            <div class="tab active">HOÀN TẤT</div>
        </div>

        <div class="tab-content">
            <div class="tab-pane show slide-up" id="done">
                <div class="alert alert-success custom-popup text-center">
                    <h3 class="mb-3">🎉 Thông tin đã được gửi!</h3>
                    <p>Cảm ơn bạn đã hoàn tất cung cấp thông tin cá nhân.</p>
                    <p class="mt-3">Quản trị viên hệ thống sẽ <strong>xem xét và kích hoạt tài khoản</strong> của bạn trong
                        thời gian sớm nhất.</p>
                </div>

                <div class="text-center">
                    <a href="{{ route('login') }}">
                        <button class="custom-confirm-button">Quay về trang đăng nhập</button>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
