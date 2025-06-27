@extends('layouts.client')

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite('resources/js/login.js')
    <div class="login-container">
        <div class="tab-main">
            <div class="tab {{ session('active_tab') != 'register' ? 'active' : '' }}" data-tab="login"
                onclick="showTab('login')">ĐĂNG NHẬP</div>
            <div class="tab {{ session('active_tab') == 'register' ? 'active' : '' }}" data-tab="register"
                onclick="showTab('register')">ĐĂNG KÝ</div>
        </div>

        @if (session('success'))
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    Swal.fire({
                        title: '{{ session('success') }}',
                        confirmButtonText: 'OK',
                        customClass: {
                            popup: 'custom-popup',
                            confirmButton: 'custom-confirm-button'
                        },
                        buttonsStyling: false
                    });
                });
            </script>
        @endif

        @if ($errors->any())
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    Swal.fire({
                        html: `{!! implode('<br>', $errors->all()) !!}`,
                        confirmButtonText: 'OK',
                        customClass: {
                            popup: 'custom-popup',
                            confirmButton: 'custom-confirm-button'
                        },
                        buttonsStyling: false
                    });
                });
            </script>
        @endif

        <div class="tab-content">
            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="tab-pane {{ session('active_tab') != 'register' ? 'show slide-down' : '' }}" id="login">
                    <!-- Đăng nhập -->
                    <div class="form-group">
                        <label for="login_email">Email</label>
                        <input type="email" id="login_email" name="email" class="form-control" autocomplete="email"
                            placeholder="Nhập email" value="{{ old('email') }}">
                    </div>

                    <div class="form-group">
                        <label for="login_pass">Mật khẩu</label>
                        <div class="password-wrapper">
                            <input type="password" id="login_pass" name="password" autocomplete="current-password"
                                class="form-control" placeholder="Nhập mật khẩu">
                            <span class="toggle-password" data-target="#login_pass">
                                <i class="fa-solid fa-eye-slash" style="color: #B197FC;"></i>
                            </span>
                        </div>
                    </div>

                    <div class="remember-pass">
                        <label class="custom-checkbox">
                            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                            <span class="checkmark"></span>
                            <p>Ghi nhớ đăng nhập</p>
                        </label>
                    </div>

                    <a class="forgot-pass" href="{{ route('forgot-form') }}">Quên mật khẩu?</a>

                    <button type="submit">Đăng nhập</button>
                </div>
            </form>

            <form action="{{ route('register') }}" method="POST">
                @csrf
                <div class="tab-pane {{ session('active_tab') == 'register' ? 'show slide-up' : '' }}" id="register">
                    <div class="form-group">
                        <label for="name">Họ và tên</label>
                        <input type="text" id="name" name="name" class="form-control"
                            value="{{ old('name') }}" placeholder="Nhập họ và tên">
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="dob">Ngày sinh</label>
                        <input type="date" id="dob" name="dob" class="form-control"
                            value="{{ old('dob') }}">
                        @error('dob')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="phone">Số điện thoại</label>
                        <input type="tel" id="phone" name="phone" class="form-control"
                            value="{{ old('phone') }}" placeholder="Nhập số điện thoại">
                        @error('phone')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="register_email">Email</label>
                        <input type="email" id="register_email" name="email" class="form-control"
                            value="{{ old('email') }}" placeholder="Nhập email">
                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="register_password">Mật khẩu</label>
                        <div class="password-wrapper">
                            <input type="password" id="register_password" name="password" class="form-control"
                                placeholder="Nhập mật khẩu">
                            <span class="toggle-password" data-target="#register_password">
                                <i class="fa-solid fa-eye-slash" style="color: #B197FC;"></i>
                            </span>
                        </div>
                        @error('password')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="confirm_password">Xác nhận mật khẩu</label>
                        <div class="password-wrapper">
                            <input type="password" id="confirm_password" name="password_confirmation" class="form-control"
                                placeholder="Nhập lại mật khẩu" value="{{ old('password_confirmation') }}">
                            <span class="toggle-password" data-target="#confirm_password">
                                <i class="fa-solid fa-eye-slash" style="color: #B197FC;"></i>
                            </span>
                        </div>
                    </div>

                    {!! NoCaptcha::display() !!}
                    @if ($errors->has('g-recaptcha-response'))
                        <span class="text-danger">
                            {{ $errors->first('g-recaptcha-response') }}
                        </span>
                    @endif


                    <button type="submit">Đăng ký</button>
                </div>
            </form>


            @if (session('active_tab'))
                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        showTab("{{ session('active_tab') }}");
                    });
                </script>
            @endif

        </div>
    </div>

    {{-- THÊM DÒNG NÀY Ở ĐÂY --}}
    {!! NoCaptcha::renderJs() !!}
@endsection
