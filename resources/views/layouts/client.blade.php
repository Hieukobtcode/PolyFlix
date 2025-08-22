<!DOCTYPE html>
<html lang="en">

<head>
    @php
        use Hashids\Hashids;

        $config = config('hashids');
        $hashids = new Hashids($config['salt'], $config['length'], $config['alphabet']);
    @endphp
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PolyFlix - Hệ thống rạp số 1 thế giới</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-id" content="{{ auth()->id() }}">
    <link rel="icon" type="image/png" href="{{ asset('logo/polyflix_title.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <!-- Font Awesome 6 (miễn phí) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @vite(['resources/css/client.css', 'resources/js/client.js', 'resources/js/chat.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    {{-- <script src="https://cdn.tailwindcss.com"></script> --}}
    {{-- CSS --}}
    @yield('styles')

    <style>
        .rap-wrapper {
            position: relative;
            display: inline-block;
        }

        .rap {
            display: flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            color: white;
            transition: color 0.3s ease;
        }

        .rap:hover {
            color: #ffcc00;
            /* Màu vàng */
        }

        .rap-dropdown {
            display: none;
            position: absolute;
            top: 120%;
            /* canh dưới nút */
            left: 0;
            background-color: #0c1120;
            /* màu nền tối giống giao diện */
            color: white;
            padding: 20px;
            border-radius: 8px;
            min-width: 600px;
            z-index: 999;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            margin-top: -10px;
        }

        .rap-dropdown ul {
            display: grid;
            grid-template-columns: repeat(5, auto);
            /* các cột vừa đủ nội dung */
            justify-content: center;
            /* căn giữa toàn bộ grid */
            gap: 30px 30px;
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .rap-dropdown li {
            white-space: nowrap;
            font-family: 'Montserrat', sans-serif;
            font-size: 15px;
            transition: color 0.3s ease;
        }

        .rap-dropdown li:hover {
            color: #ffcc00;
            cursor: pointer;
        }

        .rap-wrapper:hover .rap-dropdown {
            display: block;
        }

        /* Định dạng chung cho danh sách chi nhánh */
        .chi-nhanh-list {
            list-style: none;
            /* Bỏ dấu đầu dòng */
            padding: 0;
            margin: 0;
        }

        /* Định dạng cho mục chi nhánh */
        .chi-nhanh-list .has-submenu {
            position: relative;
            /* Đặt vị trí tương đối để submenu căn chỉnh đúng */
            cursor: pointer;
            /* Con trỏ chuột khi hover */
            padding: 10px 15px;
            /* Khoảng cách bên trong */
            color: #FFD700;
            /* Màu chữ vàng */
            background-color: #1C2526;
            /* Nền đen đậm */
            transition: background-color 0.3s ease, color 0.3s ease;
            /* Hiệu ứng chuyển đổi mượt */
        }

        /* Hiệu ứng hover cho chi nhánh */
        .chi-nhanh-list .has-submenu:hover {
            background-color: #2E3B3E;
            /* Nền sáng hơn một chút khi hover */
            color: #FFEC8B;
            /* Chữ vàng nhạt hơn khi hover */
        }

        /* Định dạng submenu (danh sách rạp) */
        .chi-nhanh-list .has-submenu .rap-submenu {
            display: none;
            /* Ẩn submenu ban đầu */
            position: absolute;
            /* Đặt vị trí tuyệt đối */
            background-color: #1C2526;
            /* Nền đen đậm cho submenu */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
            /* Bóng nhẹ */
            z-index: 1000;
            /* Đảm bảo submenu nằm trên */
            min-width: 200px;
            /* Chiều rộng tối thiểu */
            border-radius: 4px;
            /* Bo góc nhẹ */
            opacity: 0;
            /* Ẩn bằng độ trong suốt */
            transform: translateY(-10px);
            /* Hơi dịch lên trên khi ẩn */
            transition: opacity 0.3s ease, transform 0.3s ease;
            /* Hiệu ứng chuyển đổi */
        }

        /* Hiển thị submenu khi hover */
        .chi-nhanh-list .has-submenu:hover .rap-submenu {
            display: block;
            /* Hiển thị submenu */
            opacity: 1;
            /* Hiển thị hoàn toàn */
            transform: translateY(0);
            /* Đưa về vị trí ban đầu */
        }

        /* Định dạng các mục trong submenu */
        .chi-nhanh-list .rap-submenu li {
            list-style: none;
            /* Bỏ dấu đầu dòng */
        }

        /* Định dạng liên kết trong submenu */
        .chi-nhanh-list .rap-submenu li a {
            display: block;
            /* Chiếm toàn bộ chiều rộng */
            padding: 10px 15px;
            /* Khoảng cách bên trong */
            color: #FFD700;
            /* Chữ vàng */
            text-decoration: none;
            /* Bỏ gạch chân */
            transition: background-color 0.2s ease, color 0.2s ease;
            /* Hiệu ứng chuyển đổi */
        }

        /* Hiệu ứng hover cho liên kết trong submenu */
        .chi-nhanh-list .rap-submenu li a:hover {
            background-color: #2E3B3E;
            /* Nền sáng hơn khi hover */
            color: #FFEC8B;
            /* Chữ vàng nhạt hơn */
        }
    </style>
</head>

<body>
    <div class="container">
        @if (session('success'))
            <script>
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: "{{ session('success') }}",
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

        @if (session('error'))
            <script>
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: "{{ session('error') }}",
                    background: '#ef4444',
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
                    <div class="rap-wrapper">
                        @auth


                            @if (Auth::user()->vai_tro_id == 4)
                                <ul class="chi-nhanh-list">
                                    <li class="has-submenu">
                                        <a
                                            href="{{ route('showrap', \App\Helpers\IdFormatter::uuidify(Auth::user()->rapPhim->id)) }}">
                                            {{ Auth::user()->rapPhim->ten_rap }}
                                        </a>
                                    </li>
                                </ul>
                            @else
                                <div class="rap">
                                    <i class="fa-solid fa-location-dot"></i>
                                    <p>Chọn rạp</p>
                                </div>

                                <div class="rap-dropdown">
                                    <ul class="chi-nhanh-list">
                                        @foreach ($rapPhims as $chiNhanhId => $dsRap)
                                            <li class="has-submenu">
                                                {{ $dsRap->first()->chiNhanh->ten_chi_nhanh }}
                                                <ul class="rap-submenu">
                                                    @foreach ($dsRap as $rap)
                                                        <li>
                                                            <a
                                                                href="{{ route('showrap', \App\Helpers\IdFormatter::uuidify($rap->id)) }}">
                                                                {{ $rap->ten_rap }}
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </li>
                                        @endforeach
                                    </ul>

                                </div>
                            @endif
                        @endauth
                        @guest
                            <div class="rap">
                                <i class="fa-solid fa-location-dot"></i>
                                <p>Chọn rạp</p>
                            </div>

                            <div class="rap-dropdown">
                                <ul class="chi-nhanh-list">
                                    @foreach ($rapPhims as $chiNhanhId => $dsRap)
                                        <li class="has-submenu">
                                            {{ $dsRap->first()->chiNhanh->ten_chi_nhanh }}
                                            <ul class="rap-submenu">
                                                @foreach ($dsRap as $rap)
                                                    <li>
                                                        <a
                                                            href="{{ route('showrap', \App\Helpers\IdFormatter::uuidify($rap->id)) }}">
                                                            {{ $rap->ten_rap }}
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </li>
                                    @endforeach
                                </ul>

                            </div>
                        @endguest
                    </div>
                </div>
                <div class="nav2">
                    <ul>
                        @php
                            $user = Auth::user();
                        @endphp

                        {{-- Chỉ ẩn menu này khi đăng nhập và vai_tro_id == 4 --}}
                        @if (!$user || $user->vai_tro_id != 4)
                            <li>
                                <a href="{{ route('khuyen-mai.index') }}"
                                    style="text-decoration: none; color: inherit;">
                                    Khuyến mãi
                                </a>
                            </li>
                            <li><a href="{{ route('client.bai-viet') }}">Góc điện ảnh</a></li>
                            <li><a href="{{ route('client.lien-he') }}">Liên hệ</a></li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- Nội dung trang --}}
    @yield('content')

    <div id="ai-toggle" onclick="toggleChat()">🤖</div>

    @auth
        <div id="chatbox-ai">
            <header>
                PolyFlix AI
                <span class="close-btn-ai" onclick="toggleChat()"><i class="fa-solid fa-xmark fa-xl"
                        style="color: #FFD43B;"></i></span>
            </header>
            <div id="chat-messages"></div>
            <footer>
                <input type="text" id="chat-input" placeholder="Nhập nội dung..." maxlength="200"
                    onkeydown="if(event.key==='Enter') sendChat()">
                <button id="send-btn" onclick="sendChat()">Gửi</button>
            </footer>
        </div>
    @endauth

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
            <p><a href="{{ route('phim.dang-chieu') }}" style="color: inherit; text-decoration: none;">Phim đang
                    chiếu</a></p>
            <p><a href="{{ route('phim.sap-chieu') }}" style="color: inherit; text-decoration: none;">Phim sắp
                    chiếu</a></p>
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
