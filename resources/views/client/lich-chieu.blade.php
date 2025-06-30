@extends('layouts.client')

@section('content')
    <link href="https://fonts.googleapis.com/css2?family=Anton&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
        integrity="sha512-dYkA5Kj8SGrWJQ2r7S4JblmQo2+3ZJfzv+y5eA6TeK4kD4i2yHMyhzTKoH9yKxKdRYg3C1f58TbzOdKJejO3dg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <!-- CSS của Choices -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />

    <!-- JS của Choices -->
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const elements = document.querySelectorAll('.my-select');
            elements.forEach(el => {
                new Choices(el, {
                    searchEnabled: false, // tắt ô search nếu không cần
                    itemSelectText: '', // không hiện chữ chọn
                });
            });
        });
    </script>

    @vite(['resources/css/trang-chu.css'])



    <style>
        /* CSS cho thanh filter */
        .filter-bar {
            display: flex;
            gap: 16px;
            margin-top: 20px;
            margin-bottom: 20px;
        }

        /* box filter */
        .filter-box {
            background-color: #0f1525;
            /* màu nền tối như ảnh đầu */
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 12px;
            color: #fff;
            height: 100%;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        /* header của box */
        .filter-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
            font-weight: bold;
            color: #ff0;
            /* màu vàng */
            font-size: 1.1rem;
            line-height: 1;
            /* giúp icon và text thẳng hàng */
        }

        /* --- Ô select ban đầu (chưa chọn) --- */
        .choices__inner {
            background-color: #0f1525 !important;
            /* nền đen */
            border-radius: 8px;
            padding: 10px 12px;
            border: 1px solid #ccc;
            color: #fff;
            /* chữ trắng */
            font-weight: bold;
            transition: all 0.3s ease;
            min-height: 45px;
        }

        /* --- Khi đã chọn option: ô trên chuyển vàng --- */
        .choices__inner.has-items {
            background-color: #ffc107 !important;
            /* nền vàng */
            color: #000 !important;
            /* chữ đen */
            border-color: #ffc107;
            box-shadow: 0 0 6px rgba(255, 193, 7, 0.6);
        }

        /* Cho .choices relative để dropdown lấy vị trí */
        .choices {
            position: relative;
            z-index: 10;
        }

        /* Cho dropdown tuyệt đối, nổi trên */
        .choices__list--dropdown {
            position: absolute;
            z-index: 9999;
            top: 100%;
            left: 0;
            right: 0;

            background-color: #0f1525 !important;
            border-radius: 8px !important;
            border: 1px solid #ffc107;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            padding: 8px 0;

            /* Thêm hiệu ứng trượt mượt */
            opacity: 0;
            transform: translateY(-10px);
            transition: opacity 0.3s ease, transform 0.3s ease;
        }

        /* Khi mở dropdown */
        .choices.is-open .choices__list--dropdown {
            opacity: 1;
            transform: translateY(0);
        }



        /* --- Item trong dropdown --- */
        .choices__list--dropdown .choices__item--selectable {
            background-color: transparent;
            color: #fff;
            padding: 10px 14px;
            border-radius: 4px;
            margin: 4px 8px;
            transition: background-color 0.3s ease, color 0.3s ease;
            cursor: pointer;
        }

        /* --- Hover option --- */
        .choices__list--dropdown .choices__item--selectable.is-highlighted {
            background-color: #ffc107 !important;
            color: #000 !important;
        }

        /* --- Option đã chọn --- */
        .choices__list--dropdown .choices__item--selectable.is-selected {
            background-color: #ffc107 !important;
            color: #000 !important;
        }

        /* --- Placeholder (chưa chọn) --- */
        .choices__placeholder {
            color: #ffc107;
        }

        .list-movie {
            width: 73.5%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 12px;
            overflow: visible;
            position: relative;
            /* để nút canh được theo list-movie */
        }

        .movie-swiper {
            padding: 20px 0;
        }

        .movie-swiper .swiper-wrapper {
            display: flex;
            align-items: stretch;
        }

        .movie-swiper .swiper-slide {
            flex: 0 0 24%;
            max-width: 24%;
            box-sizing: border-box;
            padding: 0 7px;
            /* bạn có thể chỉnh 6px hoặc 8px */
        }

        .movie-swiper .movie {
            border-radius: 8px;
            overflow: hidden;
            text-align: center;
            color: #fff;
            transition: transform 0.3s ease;
            height: 100%;
        }

        .movie-swiper .movie:hover {
            transform: translateY(-8px);
        }

        .swiper-button-prev {
            position: absolute;
            left: -25px;
            /* ra ngoài 1 xíu */
            top: 50%;
            transform: translateY(-50%);
            z-index: 10;
        }

        .swiper-button-next {
            position: absolute;
            right: -25px;
            /* ra ngoài 1 xíu */
            top: 50%;
            transform: translateY(-50%);
            z-index: 10;
        }

        .no-schedule {
            width: 73.5%;
            margin: 40px auto;
            padding: 20px 0;
            border-top: 1px solid #ccc;
            text-align: center;
            color: #ffeb3b;
            /* màu vàng */
            font-size: 1.8rem;
            font-weight: bold;
            display: flex;
            flex-direction: row;
            /* sửa chỗ này */
            align-items: center;
            /* căn giữa theo chiều cao */
            justify-content: center;
            /* căn giữa theo ngang */
            gap: 12px;
            /* khoảng cách giữa icon và text */
        }

        .no-schedule i {
            font-size: 2.2rem;
            color: #ffeb3b;
        }

        .no-schedule span {
            font-family: 'Anton', sans-serif;
            letter-spacing: 1px;
        }

    </style>
    <div class="container py-4" style="width:73.5%">
        <div class="filter-bar d-flex align-items-center gap-3 my-4">
            <!-- Box 1: Ngày -->
            <div class="filter-box flex-grow-1" style="flex-basis: 20%;">
                <div class="filter-header d-flex justify-content-between align-items-center mb-2">
                    <div class="text-warning fw-bold fs-5">1. Ngày</div>
                    <i class="fa-solid fa-calendar-days text-warning fs-5"></i>
                </div>
                <select class="form-select fw-bold my-select" id="select-ngay">
                    <option value="">Chọn Ngày</option>
                    @foreach ($ngayChieus as $ngay)
                        <option value="{{ $ngay }}">{{ \Carbon\Carbon::parse($ngay)->format('d/m/Y') }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Box 2: Phim -->
            <div class="filter-box flex-grow-1" style="flex-basis: 55%;">
                <div class="filter-header d-flex justify-content-between align-items-center mb-2">
                    <div class="text-warning fw-bold fs-5">2. Phim</div>
                    <i class="fa-solid fa-clapperboard text-warning fs-5"></i>
                </div>
                <select class="form-select fw-bold my-select" id="select-phim">
                    <option value="">Chọn Phim</option>
                    @foreach ($phims as $phim)
                        <option value="{{ $phim->id }}">{{ $phim->ten_phim }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Box 3: Rạp -->
            <div class="filter-box flex-grow-1" style="flex-basis: 25%;">
                <div class="filter-header d-flex justify-content-between align-items-center mb-2">
                    <div class="text-warning fw-bold fs-5">3. Rạp</div>
                    <i class="fa-solid fa-location-dot text-warning fs-5"></i>
                </div>
                <select class="form-select fw-bold my-select" id="select-rap">
                    <option value="">Chọn Rạp</option>
                    @foreach ($raps as $rap)
                        <option value="{{ $rap->id }}">{{ $rap->ten_rap }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="list-movie" style="width: 73.5%; margin: 0 auto;">
        <div class="swiper movie-swiper">
            <div class="swiper-wrapper">
                              <!-- PHIM 1 -->
                              @foreach ($phims as $p)
                              <div class="swiper-slide">
                                  <div class="movie">
                                      <div class="img-wrapper">
                                          <img src="{{ asset('storage/' . $p->poster) }}" alt="{{ $p->ten_phim }}">
                                          <div class="overlay">
                                              <button class="btn buy"><i class="fa-solid fa-ticket"></i> Mua vé</button>
                                              <button class="btn trailer"><i class="fa-solid fa-video"></i> Trailer</button>
                                          </div>
                                      </div>
                                      <p>{{ $p->ten_phim }}</p>
                                  </div>
                              </div>
                          @endforeach
            </div>

        </div>
        <!-- Nút điều hướng -->
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    </div>

    <div class="no-schedule">
        <i class="fa-solid fa-calendar-xmark"></i>
        <span>HIỆN CHƯA CÓ LỊCH CHIẾU</span>
    </div>


    <br><br>
    <div class="list-movie" style="width: 73.5%; margin: 0 auto;">
        <div class="swiper movie-swiper">
            <div class="swiper-wrapper">
                              <!-- PHIM 1 -->
                              @foreach ($phims as $p)
                              <div class="swiper-slide">
                                  <div class="movie">
                                      <div class="img-wrapper">
                                          <img src="{{ asset('storage/' . $p->poster) }}" alt="{{ $p->ten_phim }}">
                                          <div class="overlay">
                                              <button class="btn buy"><i class="fa-solid fa-ticket"></i> Mua vé</button>
                                              <button class="btn trailer"><i class="fa-solid fa-video"></i> Trailer</button>
                                          </div>
                                      </div>
                                      <p>{{ $p->ten_phim }}</p>
                                  </div>
                              </div>
                          @endforeach
                        </div>

        </div>
        <!-- Nút điều hướng -->
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const movieSwiper = new Swiper('.movie-swiper', {
                slidesPerView: 4,
                spaceBetween: 16,
                loop: true,
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                autoplay: {
                    delay: 3000,
                    disableOnInteraction: false,
                },
                speed: 600,
            });
        });
    </script>
    
@endsection
