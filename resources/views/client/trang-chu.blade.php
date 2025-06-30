@extends('layouts.client')
@section('content')
    @php
        use App\Helpers\IdFormatter;
    @endphp
    <link href="https://fonts.googleapis.com/css2?family=Anton&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
        integrity="sha512-dYkA5Kj8SGrWJQ2r7S4JblmQo2+3ZJfzv+y5eA6TeK4kD4i2yHMyhzTKoH9yKxKdRYg3C1f58TbzOdKJejO3dg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    @vite('resources/js/trang-chu.js')

    <style>
        html {
            scroll-behavior: smooth;
        }

        .ten-phim {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            line-height: 1.5em;
            height: 3em;
        }

        .img-wrapper {
            position: relative;
            display: inline-block;
        }

        .age-label {
            position: absolute;
            top: 8px;
            left: 8px;
            background: red;
            color: white;
            font-weight: bold;
            padding: 4px 8px;
            font-size: 14px;
            border-radius: 4px;
            z-index: 10;
        }

        .tab-phim-item {
            padding: 10px;
            cursor: pointer;
            text-decoration: none;
        }

        .tab-phim-item.active {
            color: #FFD700;
            font-weight: bold;
            text-decoration: underline;
            text-underline-offset: 4px;
        }
    </style>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <!-- Banner -->
    <div class="swiper banner-slider" style="max-width: 1200px; width: 100%; height: 500px; margin: 0 auto 20px auto;">
        <div class="swiper-wrapper">
            @foreach ($banners as $banner)
                <div class="swiper-slide">
                    <img src="{{ asset($banner->hinh_anh) }}" alt="Banner {{ $banner->id }}"
                        style="width: 100%; height: 100%; object-fit: cover;">
                </div>
            @endforeach
        </div>
        <div class="swiper-pagination"></div>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        const swiper = new Swiper('.banner-slider', {
            loop: true,
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
        });
    </script>

    <!-- Tabs phim -->
    <div class="menu">
        <button type="button"></button>
        <p class="movie">PHIM</p>
        <div class="list">
            <p><a href="#" class="tab-phim-item active" data-tab="dang-chieu">Đang chiếu</a></p>
            <p><a href="#" class="tab-phim-item" data-tab="sap-chieu">Sắp chiếu</a></p>
        </div>
    </div>

    <!-- Danh sách phim -->
    <div class="list-movie">
        @foreach ($allPhims as $phim)
            <div class="movie clickable-movie" data-href="{{ route('phim.chi-tiet', $phim->id) }}">
                <div class="img-wrapper">
                    <a href="{{ route('phim.chi-tiet', $phim->id) }}">
                        <img src="{{ asset('storage/' . $phim->poster) }}" alt="{{ $phim->ten_phim }}">
                    </a>
                    <div class="age-label">{{ $phim->do_tuoi }}</div>
                    <div class="overlay">
                        <a href="{{ route('phim.chi-tiet', $phim->id) }}#lich-chieu">
                            <button class="btn buy">
                                <i class="fa-solid fa-ticket"></i> Mua vé
                            </button>
                        </a>
                        <button class="btn trailer" data-video="{{ $phim->trailer }}">
                            <i class="fa-solid fa-video"></i> Trailer
                        </button>
                    </div>
                </div>
                <a href="{{ route('phim.chi-tiet', $phim->id) }}">
                    <p class="ten-phim">{{ $phim->ten_phim }}</p>
                </a>
            </div>
        @endforeach
    </div>

    <a href="{{ route('phim.dang-chieu') }}" class="btn-see-more">
        <button class="btn-see">XEM THÊM</button>
    </a>

    <!-- Khuyến mãi -->
    <div class="khuyen-mai">
        <p>KHUYẾN MÃI</p>
        <div class="img">
            <img width="350px" src="{{ asset('khuyen-mai/c_student.png') }}" alt="">
            <img width="350px" src="{{ asset('khuyen-mai/C_TEN.png') }}" alt="">
            <img width="350px" src="{{ asset('khuyen-mai/monday_1_.jpg') }}" alt="">
        </div>
    </div>
    <a href="{{ route('khuyen-mai.index') }}">
        <button class="btn-km">TẤT CẢ ƯU ĐÃI</button>
    </a>

    <!-- Góc điện ảnh -->
    <div class="goc-dien-anh-wrapper">
        @include('client.partials.goc-dien-anh', [
            'phims' => $phims,
            'ratings' => $ratings,
            'baiViet' => $baiViet ?? [],
        ])
    </div>

    <!-- Trailer Popup -->
    <div id="trailerPopup"
        style="display:none; position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);background:#000;padding:10px;border-radius:8px;z-index:999;">
        <iframe id="trailerIframe" width="800" height="450" frameborder="0" allowfullscreen></iframe>
    </div>
    <div id="overlayBg"
        style="display:none; position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.7);z-index:998;">
    </div>

    <script>
        function bindTrailerEvents() {
            document.querySelectorAll('.btn.trailer').forEach(btn => {
                btn.addEventListener('click', function() {
                    let videoUrl = convertYoutubeUrl(this.getAttribute('data-video'));
                    document.getElementById('trailerIframe').src = videoUrl + '?autoplay=1';
                    document.getElementById('trailerPopup').style.display = 'block';
                    document.getElementById('overlayBg').style.display = 'block';
                });
            });
        }

        function convertYoutubeUrl(url) {
            if (url.includes('watch?v=')) return 'https://www.youtube.com/embed/' + url.split('watch?v=')[1];
            if (url.includes('youtu.be/')) return 'https://www.youtube.com/embed/' + url.split('youtu.be/')[1];
            return url;
        }

        document.addEventListener('DOMContentLoaded', function() {
            bindTrailerEvents();

            document.querySelectorAll('.clickable-movie').forEach(div => {
                div.addEventListener('click', function(e) {
                    if (e.target.closest('.btn') || e.target.closest('a')) return;
                    window.location.href = this.dataset.href;
                });
            });

            document.querySelectorAll('.menu .list a.tab-phim-item').forEach(tab => {
                tab.addEventListener('click', function(e) {
                    e.preventDefault();
                    const selectedTab = this.dataset.tab;

                    fetch(`/phim-tab?tab=${selectedTab}`)
                        .then(res => res.json())
                        .then(data => {
                            const movieList = document.querySelector('.list-movie');
                            movieList.innerHTML = '';
                            data.phims.forEach(phim => {
                                const poster = phim.poster ? `/storage/${phim.poster}` :
                                    '/logo/no-image.png';
                                const item = `
                            <div class="movie clickable-movie" data-href="/phim/${phim.id}">
                                <div class="img-wrapper">
                                    <img src="${poster}" alt="${phim.ten_phim}">
                                    <div class="age-label">${phim.do_tuoi ?? ''}</div>
                                    <div class="overlay">
                                        <a href="/phim/${phim.id}#lich-chieu">
                                            <button class="btn buy"><i class="fa-solid fa-ticket"></i> Mua vé</button>
                                        </a>
                                        <button class="btn trailer" data-video="${phim.trailer}">
                                            <i class="fa-solid fa-video"></i> Trailer
                                        </button>
                                    </div>
                                </div>
                                <p class="ten-phim">${phim.ten_phim}</p>
                            </div>`;
                                movieList.insertAdjacentHTML('beforeend', item);
                            });

                            bindTrailerEvents(); // ✅ gán lại sự kiện Trailer

                            document.querySelectorAll('.clickable-movie').forEach(div => {
                                div.addEventListener('click', function(e) {
                                    if (e.target.closest('.btn') || e.target
                                        .closest('a')) return;
                                    window.location.href = this.dataset.href;
                                });
                            });

                            const btnSeeMore = document.querySelector('.btn-see-more');
                            if (btnSeeMore) {
                                btnSeeMore.href = selectedTab === 'sap-chieu' ?
                                    '/phim-sap-chieu' : '/phim-dang-chieu';
                            }

                            document.querySelectorAll('.menu .list a.tab-phim-item').forEach(
                                t => t.classList.remove('active'));
                            tab.classList.add('active');
                        });
                });
            });
        });

        document.getElementById('overlayBg').addEventListener('click', function() {
            document.getElementById('trailerIframe').src = '';
            document.getElementById('trailerPopup').style.display = 'none';
            this.style.display = 'none';
        });
    </script>
@endsection
