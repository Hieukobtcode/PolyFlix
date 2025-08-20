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

        /* CSS cho hiển thị suất chiếu */
        .schedule-content {
            color: #fff;
        }

        .date-group {
            margin-bottom: 30px;
        }

        .movie-showtimes {
            transition: all 0.3s ease;
        }

        .movie-showtimes:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(255, 235, 59, 0.2);
        }

        .showtime-btn {
            min-width: 80px;
            padding: 8px 12px;
            border-radius: 6px;
            transition: all 0.3s ease;
            text-align: center;
        }

        .showtime-btn:hover {
            background-color: #ffeb3b;
            color: #000;
            transform: translateY(-1px);
        }

        .showtime-btn small {
            font-size: 0.7em;
            opacity: 0.8;
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

    <!-- Hiển thị suất chiếu -->
    <div id="suat-chieu-container" style="width: 73.5%; margin: 20px auto;">
        @if($suatChieus->count() > 0)
            <div class="schedule-content">
                @php
                    $groupedByDate = $suatChieus->groupBy('ngay_bat_dau');
                @endphp

                @foreach($groupedByDate as $date => $suatChieusInDate)
                    <div class="date-group mb-4" data-date="{{ $date }}">
                        <h3 class="text-warning mb-3">
                            <i class="fa-solid fa-calendar-days me-2"></i>
                            {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($date)->locale('vi')->dayName }}
                        </h3>

                        @php
                            $groupedByMovie = $suatChieusInDate->groupBy('phim_id');
                        @endphp

                        @foreach($groupedByMovie as $movieId => $movieShowtimes)
                            @php
                                $movie = $movieShowtimes->first()->phim;
                            @endphp
                            <div class="movie-showtimes mb-3 p-3" style="background: #1a1a2e; border-radius: 8px; border-left: 4px solid #ffeb3b;">
                                <h5 class="text-white mb-2">{{ $movie->ten_phim }}</h5>
                                <div class="showtimes-list d-flex flex-wrap gap-2">
                                    @foreach($movieShowtimes as $showtime)
                                        <button class="btn btn-outline-warning btn-sm showtime-btn"
                                                data-showtime-id="{{ $showtime->id }}"
                                                data-movie-id="{{ $showtime->phim_id }}"
                                                data-date="{{ $showtime->ngay_bat_dau }}"
                                                data-time="{{ $showtime->bat_dau }}">
                                            {{ \Carbon\Carbon::parse($showtime->bat_dau)->format('H:i') }}
                                            <small class="d-block">{{ $showtime->phongChieu->rapPhim->ten_rap ?? 'N/A' }}</small>
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        @else
            <div class="no-schedule">
                <i class="fa-solid fa-calendar-xmark"></i>
                <span>HIỆN CHƯA CÓ LỊCH CHIẾU</span>
            </div>
        @endif
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

            // Xử lý filter
            const selectNgay = document.getElementById('select-ngay');
            const selectPhim = document.getElementById('select-phim');
            const selectRap = document.getElementById('select-rap');
            const scheduleContainer = document.getElementById('suat-chieu-container');

            function filterShowtimes() {
                const selectedDate = selectNgay.value;
                const selectedMovie = selectPhim.value;
                const selectedRap = selectRap.value;

                const dateGroups = scheduleContainer.querySelectorAll('.date-group');
                let hasVisibleContent = false;

                dateGroups.forEach(dateGroup => {
                    const groupDate = dateGroup.getAttribute('data-date');
                    let showDateGroup = true;

                    // Filter theo ngày
                    if (selectedDate && groupDate !== selectedDate) {
                        showDateGroup = false;
                    }

                    if (showDateGroup) {
                        const movieShowtimes = dateGroup.querySelectorAll('.movie-showtimes');
                        let hasVisibleMovies = false;

                        movieShowtimes.forEach(movieShowtime => {
                            const showtimeBtns = movieShowtime.querySelectorAll('.showtime-btn');
                            let hasVisibleShowtimes = false;

                            showtimeBtns.forEach(btn => {
                                const movieId = btn.getAttribute('data-movie-id');
                                const rapText = btn.querySelector('small').textContent;
                                let showBtn = true;

                                // Filter theo phim
                                if (selectedMovie && movieId !== selectedMovie) {
                                    showBtn = false;
                                }

                                // Filter theo rạp (tìm kiếm trong text)
                                if (selectedRap && !rapText.toLowerCase().includes(selectedRap.toLowerCase())) {
                                    showBtn = false;
                                }

                                btn.style.display = showBtn ? 'inline-block' : 'none';
                                if (showBtn) hasVisibleShowtimes = true;
                            });

                            movieShowtime.style.display = hasVisibleShowtimes ? 'block' : 'none';
                            if (hasVisibleShowtimes) hasVisibleMovies = true;
                        });

                        dateGroup.style.display = hasVisibleMovies ? 'block' : 'none';
                        if (hasVisibleMovies) hasVisibleContent = true;
                    } else {
                        dateGroup.style.display = 'none';
                    }
                });

                // Hiển thị thông báo nếu không có kết quả
                const noScheduleDiv = scheduleContainer.querySelector('.no-schedule');
                const scheduleContent = scheduleContainer.querySelector('.schedule-content');

                if (hasVisibleContent) {
                    if (noScheduleDiv) noScheduleDiv.style.display = 'none';
                    if (scheduleContent) scheduleContent.style.display = 'block';
                } else {
                    if (scheduleContent) scheduleContent.style.display = 'none';
                    if (noScheduleDiv) {
                        noScheduleDiv.style.display = 'flex';
                    } else {
                        // Tạo thông báo mới nếu chưa có
                        const newNoSchedule = document.createElement('div');
                        newNoSchedule.className = 'no-schedule';
                        newNoSchedule.innerHTML = '<i class="fa-solid fa-calendar-xmark"></i><span>KHÔNG TÌM THẤY SUẤT CHIẾU PHÙ HỢP</span>';
                        scheduleContainer.appendChild(newNoSchedule);
                    }
                }
            }

            // Gắn sự kiện cho các select
            selectNgay.addEventListener('change', filterShowtimes);
            selectPhim.addEventListener('change', filterShowtimes);
            selectRap.addEventListener('change', filterShowtimes);

            // Xử lý click vào nút suất chiếu
            scheduleContainer.addEventListener('click', function(e) {
                if (e.target.classList.contains('showtime-btn') || e.target.closest('.showtime-btn')) {
                    const btn = e.target.classList.contains('showtime-btn') ? e.target : e.target.closest('.showtime-btn');
                    const showtimeId = btn.getAttribute('data-showtime-id');
                    const movieId = btn.getAttribute('data-movie-id');
                    const date = btn.getAttribute('data-date');
                    const time = btn.getAttribute('data-time');

                    // Redirect đến trang đặt vé
                    window.location.href = `/dat-ve?suat_chieu_id=${showtimeId}`;
                }
            });
        });
    </script>
    
@endsection
