@extends('layouts.client')

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .container-pass {
            width: 80%;
            margin: 70px auto 0px auto;
            text-align: center;
            margin-bottom: 150px;
        }

        .container-pass h3 {
            margin-top: 70px;
            font-family: "Anton", sans-serif;
            color: #ffffff;
            font-size: 35px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .container-pass p {
            max-width: 500px;
            margin: 10px auto 30px auto;
            text-align: center;
            white-space: normal;
            word-wrap: break-word;
            overflow-wrap: break-word;
            line-height: 1.5;
        }

        .container-pass input {
            width: 30%;
            height: 45px;
            border-radius: 7px;
            border: none;
            padding: 0 12px;
        }

        .container-pass button {
            display: block;
            margin: 0px auto;
            position: relative;
            width: 30%;
            height: 45px;
            border-radius: 7px;
            background-color: #e2d115;
            font-family: "Poppins", sans-serif;
            color: rgb(0, 0, 0);
            font-weight: bold;
            overflow: hidden;
            border: none;
            cursor: pointer;
            z-index: 1;
            transition: color 0.4s ease;
            font-size: 18px;
            margin-top: 10px;
        }

        .container-pass button::before {
            content: "";
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background-image: linear-gradient(to right, #6a11cb, #2575fc);
            z-index: -1;
            transition: left 0.4s ease;
        }

        .container-pass button:hover::before {
            left: 0;
        }

        .container-pass button:hover {
            color: #fff;
        }

        .text-danger {
            color: #ffcccc;
            font-size: 14px;
            margin-top: 8px;
            display: block;
        }

        .swal2-icon.swal2-error {
            background-color: transparent !important;
            color: red;
        }

        .custom-toast {
            padding: 16px 24px;
            font-size: 14px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        .custom-toast {
            padding: 16px 24px;
            font-size: 14px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        .swal2-icon.swal2-success {
            color: #22c55e;
            border-color: #22c55e;
        }
    </style>

    <div class="container-pass">
        @if (session('success'))
            <script>
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: '{{ session('success') }}',
                    background: '#10b981',
                    color: '#fff',
                    showCloseButton: true,
                    timer: 7000,
                    timerProgressBar: true,
                    showConfirmButton: false,
                    customClass: {
                        popup: 'custom-toast'
                    }
                });
            </script>
        @endif

        @if ($errors->any())
            <script>
                let errorMessages = `{!! implode('<br>', $errors->all()) !!}`;
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    iconHtml: '<i class="fa-solid fa-exclamation fa-bounce" style="color: #facc15; font-size: 18px;"></i>',
                    title: errorMessages,
                    background: '#7c3aed',
                    color: '#fff',
                    showCloseButton: true,
                    timer: 7000,
                    timerProgressBar: true,
                    showConfirmButton: false,
                    customClass: {
                        popup: 'custom-toast',
                        icon: 'no-icon-bg',
                        title: 'text-start'
                    }
                });
            </script>
        @endif

        <form action="{{ route('forgot-pass') }}" method="POST">
            @if (session('success'))
                <a href="{{ route('login') }}">
                    <button type="button">TIẾN HÀNH ĐĂNG NHẬP</button>
                </a>
            @else
                <h3>QUÊN MẬT KHẨU</h3>
                <p>Nhập địa chỉ email của bạn và chúng tôi sẽ gửi cho bạn hướng dẫn để tạo mật khẩu mới.</p>

                <form action="{{ route('forgot-pass') }}" method="POST">
                    @csrf
                    <input type="email" name="email" placeholder="Email của bạn" required>
                    <button type="submit">XÁC NHẬN</button>
                </form>
            @endif
        </form>
    </div>
@endsection
