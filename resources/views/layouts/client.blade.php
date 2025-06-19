<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PolyFlix - Hệ thống rạp số 1 thế giới</title>
    <link rel="icon" type="image/png" href="{{ asset('logo/polyflix_title.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    @vite('resources/js/client.js')
</head>

<body>
    <div class="container">
        {{-- Header --}}
        <div class="header">
            <div class="logo">
                <img src="{{ asset('logo/polyflix_title.png') }}" alt="PolyFlix Logo">
                <a href="">
                    <img class="ticket" src="{{ asset('banner/ticket.png') }}" alt="">
                </a>
            </div>
            <div class="list-header">
                <div class="search">
                    <input type="text" name="search" placeholder="Tìm phim...">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>
                <div class="login">
                    <i class="fa-solid fa-user"></i>
                    <span>Đăng nhập</span>
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
                        <li>Khuyến mãi</li>
                        <li>Góc điện ảnh</li>
                        <li>Liên hệ</li>
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
            <img src="{{ asset('logo/polyflix_title.png') }}" width="80px" alt="">
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
            <p>Phim đang chiếu</p>
            <p>Phim sắp chiếu</p>
            <p>Suất chiếu đặc biệt</p>
        </div>
        <div>
            <h3>POLYFLIX</h3>
            <p>Giới thiệu</p>
            <p>Liên hệ</p>
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
