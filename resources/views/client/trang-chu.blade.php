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
                        <a href="{{ route('phim.chi-tiet', urlencode($phim->ten_phim)) }}#lich-chieu">
                            <button class="btn buy">
                                <i class="fa-solid fa-ticket"></i> Mua vé
                            </button>
                        </a>
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



    <style>
        .promotions-section {
            padding: 50px 0;
            background: linear-gradient(135deg, #3f2b96 0%, #454578 50%, #3b3b96 100%);
            color: white;
        }
        .promotions-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr); /* 2 cards per row on desktop */
            gap: 25px;
            margin-top: 30px;
        }
        .promotion-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .promotion-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
        }
        .promotion-title {
            font-size: 1.5rem; /* Increased font size */
            font-weight: bold;
            margin-bottom: 10px;
        }
        .promotion-description {
            font-size: 0.9rem;
            opacity: 0.9;
            margin-bottom: 15px;
        }
        .promo-code-wrapper {
            background-color: rgba(0, 0, 0, 0.2);
            border-radius: 8px;
            padding: 10px;
            margin: 20px 0;
        }
        .promo-code {
            font-family: 'Courier New', Courier, monospace;
            font-size: 1.2rem;
            font-weight: bold;
            color: #f1c40f;
            letter-spacing: 2px;
        }
        .promo-buttons {
            display: flex;
            gap: 10px;
            justify-content: center;
        }
        .btn-promo {
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 600;
            text-transform: uppercase;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .btn-details {
            background-color: transparent;
            border: 2px solid #f1c40f;
            color: #f1c40f;
        }
        .btn-details:hover {
            background-color: #f1c40f;
            color: #3f2b96;
        }
        .btn-copy {
            background-color: #f1c40f;
            color: #3f2b96;
        }
        .btn-copy:hover {
            background-color: #e1b30a;
        }

        /* Responsive: 1 card per row on mobile */
        @media (max-width: 768px) {
            .promotions-grid {
                grid-template-columns: 1fr;
            }
            .promotion-title {
                font-size: 1.3rem;
            }
        }
    </style>

    <!-- Khuyến mãi nổi bật -->
    @if(isset($khuyenMaisNoiBat) && $khuyenMaisNoiBat->count() > 0)
    <section class="promotions-section">
        <div class="container">
            <div class="text-center">
                <h2 class="section-title">KHUYẾN MÃI HOT</h2>
                <p class="section-subtitle">Những ưu đãi siêu hấp dẫn chỉ có tại PolyFlix!</p>
            </div>

            <div class="promotions-grid">
                @foreach($khuyenMaisNoiBat as $km)
                <div class="promotion-card">
                    <h3 class="promotion-title">{{ $km->ten }}</h3>
                    <p class="promotion-description">{{ $km->mo_ta }}</p>

                    <div class="promo-code-wrapper">
                        <span>MÃ KHUYẾN MÃI</span>
                        <div class="promo-code">{{ $km->ma_khuyen_mai }}</div>
                    </div>

                    <div class="promo-buttons">
                        <a href="{{ route('client.khuyen-mai.show', $km->id) }}" class="btn-promo btn-details">Chi tiết</a>
                        <button class="btn-promo btn-copy copy-code-home" data-code="{{ $km->ma_khuyen_mai }}">Sao chép</button>
                    </div>
                </div>
                @endforeach
            </div>

             <div class="text-center mt-4">
                <a href="{{ route('client.khuyen-mai.index') }}" class="btn-see-more">
                    <button class="btn-see">XEM TẤT CẢ ƯU ĐÃI</button>
                </a>
            </div>
        </div>
    </section>
    @endif

    <div class="goc-dien-anh-wrapper">
        @include('client.partials.goc-dien-anh', [
            'phims' => $phims,
            'ratings' => $ratings,
            'baiViet' => $baiViet ?? [],
        ])
    </div>

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
