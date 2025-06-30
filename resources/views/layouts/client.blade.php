<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PolyFlix - Hệ thống rạp số 1 thế giới</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('logo/polyflix_title.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <!-- Font Awesome 6 (miễn phí) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    @vite('resources/js/client.js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{-- CSS --}}
    @yield('styles')
</head>

<body>
    <div class="container">
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
        {{-- Header --}}
        <div class="header">
            <div class="logo">
                <a href="{{ route('home') }}">
                    <img src="{{ asset('logo/CinematicPolyFlixLogo-removebg-preview-removebg-preview.png') }}"
                        alt="PolyFlix Logo">
                </a>
            </div>
            <div class="list-header">
                <div class="search">
                    <input type="text" name="search" placeholder="Tìm phim...">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>

                <div class="login dropdown">
                    <i class="fa-solid fa-user"></i>

                    @auth
                        <div class="user-toggle" onclick="toggleUserDropdown()">
                            <span class="user-name">{{ Auth::user()->name }}</span>
                            <i class="fa-solid fa-chevron-down"></i>
                        </div>
                        <div class="dropdown-menu" id="userDropdown">
                            <a href="{{ route('profile') }}"><i class="fa-solid fa-user"></i> Thông tin cá nhân</a>
                            <a href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fa-solid fa-arrow-right-from-bracket"></i> Đăng xuất
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    @else
                        <a href="{{ route('login.form') }}"><span>Đăng nhập</span></a>
                    @endauth
                </div>

            </div>

        </div>
    </div>

    <div class="nav-bar">
        <div class="container">
            <div class="nav1">
                <div class="list-nav">
                    <div class="rap">
                        <i class="fa-solid fa-location-dot"></i>
                        <p>Chọn rạp</p>
                    </div>
                    <div class="lich-chieu">
                        <i class="fa-solid fa-calendar-days"></i>
                        <p>Lịch chiếu</p>
                    </div>
                </div>
                <div class="nav2">
                    <ul>
                        <li>
                            <a href="{{ route('khuyen-mai.index') }}" style="text-decoration: none; color: inherit;">
                                Khuyến mãi
                            </a>
                        </li>
                        <li><a href="{{ route('client.bai-viet') }}">Góc điện ảnh</a></li>
                        <li><a href="{{ route('client.lien-he') }}">Liên hệ</a></li>
                        <li>Giới thiệu</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- Nội dung trang --}}
    @yield('content')

    {{-- Footer --}}
    <div class="footer">
        <div class="footer1">
            <img src="{{ asset('logo/CinematicPolyFlixLogo-removebg-preview-removebg-preview.png') }}" width="150px"
                alt="">
            <p class="none">NO SEAT, NO CHILL</p>
            <div class="social">
                <i class="fa-brands fa-facebook"></i>
                <i class="fa-brands fa-tiktok"></i>
                <i class="fa-brands fa-instagram"></i>
            </div>
        </div>
        <div>
            <h3>TÀI KHOẢN</h3>
            <p>Đăng nhập</p>
            <p>Đăng ký</p>
        </div>
        <div>
            <h3>XEM PHIM</h3>
            <p><a href="{{ route('phim.dang-chieu') }}" style="color: inherit; text-decoration: none;">Phim đang chiếu</a></p>
            <p><a href="{{ route('phim.sap-chieu') }}" style="color: inherit; text-decoration: none;">Phim sắp chiếu</a></p>
            <p>Suất chiếu đặc biệt</p>
        </div>
        <div>
            <h3>POLYFLIX</h3>
            <p>Giới thiệu</p>
            <p><a href="{{ route('client.lien-he') }}" style="color: inherit; text-decoration: none;">Liên hệ</a></p>
            <p>Tuyển dụng</p>
        </div>
        <div>
            <h3>HỆ THỐNG RẠP</h3>
            <p>Tất cả hệ thống rạp</p>
            <p>PolyFlix Long Biên - Hà Nội</p>
        </div>
    </div>
</body>

</html>
{{-- JS --}}
@yield('scripts')
