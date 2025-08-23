@extends('layouts.client')
@section('content')
    @php
        use App\Helpers\IdFormatter;
    @endphp
    <link href="https://fonts.googleapis.com/css2?family=Anton&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
        integrity="sha512-Avb2QiuDEEvB4bZJYdft2mNjVShBftLdPG8FJ0V7irTLQ8Uo0qcPxh4Plq7G5tGm0rU+1SPhVotteLpBERwTkw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />


    @vite('resources/js/trang-chu.js')

    {{-- <div class="banner"> --}}
    {{-- <img src="{{ asset('banner/1215wx365h_6_.jpg') }}" alt="">
    </div> --}}
    <style>
        .ten-phim {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            /* Giới hạn 2 dòng */
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

    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <!-- Banner Slider -->
    <div class="swiper banner-slider"
        style="max-width: 79%; ;height:450px; margin: 20px auto 20px auto; border-radius: 10px; overflow: hidden;">
        <div class="swiper-wrapper">
            @foreach ($banners as $banner)
                <div class="swiper-slide" style="width: 100%">
                    <img src="{{ asset('storage/' . $banner->hinh_anh) }}" alt="Banner {{ $banner->id }}"
                        style="width: 1200px; height: 450px;">
                </div>
            @endforeach
        </div>

        <div class="swiper-pagination"></div>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    </div>

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <!-- Swiper Init -->
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

    <div class="menu">
        <button type="button"></button>
        <p class="movie">PHIM</p>

        <div class="list">
            <p>
                <a href="#" class="tab-phim-item active" data-tab="dang-chieu">Đang chiếu</a>
            </p>
            <p>
                <a href="#" class="tab-phim-item" data-tab="sap-chieu">Sắp chiếu</a>
            </p>
        </div>
    </div>

    <div class="list-movie">
        @foreach ($allPhims as $phim)
            <div class="movie">
                <div class="img-wrapper">
                    <img src="{{ asset('storage/' . $phim->poster) }}" alt="{{ $phim->ten_phim }}">
                    <div class="age-label">{{ $phim->do_tuoi }}</div>
                    <div class="overlay">
                        @php
                            $user = Auth::user();
                        @endphp

                        @if (!$user || $user->vai_tro_id != 4)
                            <a href="{{ route('phim.chi-tiet', urlencode($phim->ten_phim)) }}#lich-chieu">
                                <button class="btn buy">
                                    <i class="fa-solid fa-ticket"></i> Mua vé
                                </button>
                            </a>
                        @endif
                        <button class="btn trailer" data-video="{{ $phim->trailer }}"><i class="fa-solid fa-video"></i>
                            Trailer</button>
                    </div>
                </div>
                <p class="ten-phim">{{ $phim->ten_phim }}</p>
            </div>
        @endforeach
    </div>

    <a href="{{ route('phim.dang-chieu') }}" class="btn-see-more">
        <button class="btn-see">XEM THÊM</button>
    </a>
    @php
        $user = Auth::user();
    @endphp

    {{-- Chỉ hiển thị nếu chưa đăng nhập hoặc user không có vai_tro_id = 4 --}}
    @if (!$user || $user->vai_tro_id != 4)
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
        <div class="goc-dien-anh-wrapper">
            @include('client.partials.goc-dien-anh', [
                'phims' => $phims,
                'ratings' => $ratings,
                'baiViet' => $baiViet ?? [],
            ])
        </div>
    @else
    <br>
    @endif

    <!-- Popup trailer -->
    <div id="trailerPopup"
        style="display:none; position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);
background:#000;padding:10px;border-radius:8px;z-index:999;">
        <iframe id="trailerIframe" width="800" height="450" frameborder="0" allowfullscreen></iframe>
    </div>
    <!-- Overlay mờ nền -->
    <div id="overlayBg"
        style="display:none; position:fixed;top:0;left:0;width:100%;height:100%;
background:rgba(0,0,0,0.7);z-index:998;">
    </div>

    <script>
        document.querySelectorAll('.menu .list a.tab-phim-item').forEach(tab => {
            tab.addEventListener('click', function(e) {
                e.preventDefault();

                const selectedTab = this.dataset.tab;

                fetch(`/phim-tab?tab=${selectedTab}`)
                    .then(response => response.json())
                    .then(data => {
                        const movieList = document.querySelector('.list-movie');
                        if (movieList) {
                            movieList.innerHTML = '';

                            data.phims.forEach(phim => {
                                const poster = phim.poster ? `/storage/${phim.poster}` :
                                    '/logo/no-image.png';
                                const movieItem = `
                            <div class="movie">
                                <div class="img-wrapper">
                                    <img src="${poster}" alt="${phim.ten_phim}">
                                    <div class="age-label">${phim.do_tuoi ?? ''}</div>
                                    <div class="overlay">
                                        <button class="btn buy"><i class="fa-solid fa-ticket"></i> Mua vé</button>
                                       <button class="btn trailer" data-video="${phim.trailer}"><i class="fa-solid fa-video"></i> Trailer</button>
            </div>
                                </div>
                                <p class="ten-phim">${phim.ten_phim}</p>
                            </div>
                        `;
                                movieList.insertAdjacentHTML('beforeend', movieItem);
                            });


                            document.querySelectorAll('.btn.trailer').forEach(btn => {
                                btn.addEventListener('click', function() {
                                    let videoUrl = this.getAttribute('data-video');
                                    // Nếu là link youtube -> chuyển về dạng embed
                                    videoUrl = convertYoutubeUrl(videoUrl);

                                    document.getElementById('trailerIframe').src =
                                        videoUrl + '?autoplay=1';
                                    document.getElementById('trailerPopup').style
                                        .display = 'block';
                                    document.getElementById('overlayBg').style.display =
                                        'block';
                                });
                            });

                            // Cập nhật active tab
                            document.querySelectorAll('.menu .list a.tab-phim-item').forEach(t => t
                                .classList.remove('active'));
                            this.classList.add('active');

                            // ✅ Cập nhật nút Xem thêm:
                            const btnSeeMore = document.querySelector('.btn-see-more');
                            if (btnSeeMore) {
                                if (selectedTab === 'sap-chieu') {
                                    btnSeeMore.href = '/phim-sap-chieu';
                                } else {
                                    btnSeeMore.href = '/phim-dang-chieu';
                                }
                            }
                        }
                    });
            });
        });
        document.querySelectorAll('.btn.trailer').forEach(btn => {
            btn.addEventListener('click', function() {
                let videoUrl = this.getAttribute('data-video');
                // Nếu là link youtube -> chuyển về dạng embed
                videoUrl = convertYoutubeUrl(videoUrl);

                document.getElementById('trailerIframe').src = videoUrl + '?autoplay=1';
                document.getElementById('trailerPopup').style.display = 'block';
                document.getElementById('overlayBg').style.display = 'block';
            });
        });



        // Tắt popup khi click nền
        document.getElementById('overlayBg').addEventListener('click', function() {
            document.getElementById('trailerIframe').src = '';
            document.getElementById('trailerPopup').style.display = 'none';
            this.style.display = 'none';
        });

        // Hàm chuyển link youtube -> embed
        function convertYoutubeUrl(url) {
            let video_id = '';
            if (url.includes('watch?v=')) {
                video_id = url.split('watch?v=')[1];
            } else if (url.includes('youtu.be/')) {
                video_id = url.split('youtu.be/')[1];
            } else {
                video_id = url; // nếu đã là embed
            }

            return 'https://www.youtube.com/embed/' + video_id;
        }

        // Copy code functionality for promotions
        document.querySelectorAll('.copy-code-home').forEach(btn => {
            btn.addEventListener('click', function() {
                const code = this.dataset.code;
                navigator.clipboard.writeText(code).then(() => {
                    const originalText = this.innerHTML;
                    this.innerHTML = '<i class="fas fa-check"></i> Đã sao chép!';
                    this.style.backgroundColor = '#28a745';

                    setTimeout(() => {
                        this.innerHTML = originalText;
                        this.style.backgroundColor = '';
                    }, 2000);
                });
            });
        });

        // Hover effect for promotion cards
        document.querySelectorAll('.promotion-card-home').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-10px) scale(1.05)';
                this.style.boxShadow = '0 20px 40px rgba(0,0,0,0.3)';
            });

            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
                this.style.boxShadow = 'none';
            });
        });
    </script>
@endsection
