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



    <!-- Khuyến mãi nổi bật -->
    @if(isset($khuyenMaisNoiBat) && $khuyenMaisNoiBat->count() > 0)
    <section class="promotions-modern" style="padding: 40px 0; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); margin: 40px 0; position: relative; overflow: hidden;">
        <!-- Background decoration -->
        <div style="position: absolute; top: -50%; right: -10%; width: 300px; height: 300px; background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%); border-radius: 50%;"></div>
        <div style="position: absolute; bottom: -30%; left: -5%; width: 200px; height: 200px; background: radial-gradient(circle, rgba(255,255,255,0.05) 0%, transparent 70%); border-radius: 50%;"></div>

        <div class="container">
            <div class="text-center mb-4">
                <div style="display: inline-block; background: rgba(255,255,255,0.1); padding: 12px 24px; border-radius: 50px; margin-bottom: 15px; backdrop-filter: blur(10px);">
                    <h2 style="color: white; font-family: 'Poppins', sans-serif; font-size: 1.8rem; margin: 0; font-weight: 700; letter-spacing: 1px;">
                        <i class="fas fa-fire" style="color: #ff6b35; margin-right: 8px; animation: pulse 2s infinite;"></i>KHUYẾN MÃI HOT
                    </h2>
                </div>
                <p style="color: rgba(255,255,255,0.9); font-size: 0.95rem; font-weight: 400; margin: 0; max-width: 500px; margin: 0 auto;">Những ưu đãi siêu hấp dẫn chỉ có tại PolyFlix!</p>
            </div>

            <div class="row g-3">
                @foreach($khuyenMaisNoiBat as $km)
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="promotion-card-modern" style="background: rgba(255,255,255,0.15); backdrop-filter: blur(15px);
                         border-radius: 20px; padding: 20px; text-align: center; color: white;
                         transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
                         border: 1px solid rgba(255,255,255,0.25);
                         position: relative; overflow: hidden;
                         box-shadow: 0 8px 32px rgba(0,0,0,0.1);">

                        <!-- Card glow effect -->
                        <div style="position: absolute; top: 0; left: 0; right: 0; height: 2px; background: linear-gradient(90deg, #ff6b35, #ffd700, #ff6b35); opacity: 0.8;"></div>

                        <!-- Discount badge -->
                        <div style="background: linear-gradient(135deg, #ff6b35, #ff8c42); color: white;
                             padding: 8px 16px; border-radius: 25px; display: inline-block; margin-bottom: 15px;
                             font-weight: 700; font-size: 1rem; box-shadow: 0 4px 15px rgba(255,107,53,0.3);
                             position: relative; overflow: hidden;">
                            <div style="position: absolute; top: 0; left: -100%; width: 100%; height: 100%;
                                 background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
                                 animation: shimmer 2s infinite;"></div>
                            @if($km->loai_giam_gia == 'phan_tram')
                                -{{ $km->gia_tri_giam }}%
                            @else
                                -{{ number_format($km->gia_tri_giam) }}K
                            @endif
                        </div>

                        <!-- Title -->
                        <h5 style="margin-bottom: 12px; font-weight: 600; font-size: 1.1rem; line-height: 1.4;
                           font-family: 'Poppins', sans-serif;">{{ Str::limit($km->ten, 30) }}</h5>

                        <!-- Description -->
                        <p style="opacity: 0.85; margin-bottom: 15px; font-size: 0.85rem; line-height: 1.5;
                           color: rgba(255,255,255,0.9);">
                            {{ Str::limit($km->mo_ta, 50) }}
                        </p>

                        <!-- Code box -->
                        <div style="background: linear-gradient(135deg, rgba(255,255,255,0.2), rgba(255,255,255,0.1));
                             padding: 12px; border-radius: 15px; margin-bottom: 18px; border: 1px solid rgba(255,255,255,0.3);
                             position: relative;">
                            <div style="font-size: 0.7rem; color: rgba(255,255,255,0.7); margin-bottom: 4px; text-transform: uppercase; letter-spacing: 1px;">Mã khuyến mãi</div>
                            <strong style="font-family: 'Courier New', monospace; letter-spacing: 2px; font-size: 1rem; color: #ffd700;">
                                {{ $km->ma_khuyen_mai }}
                            </strong>
                        </div>

                        <!-- Action buttons -->
                        <div class="d-flex gap-2">
                            <a href="{{ route('client.khuyen-mai.show', $km->id) }}"
                               class="btn btn-outline-light btn-sm flex-fill modern-btn"
                               style="border-radius: 15px; font-weight: 600; font-size: 0.8rem; padding: 10px 8px;
                                      border: 2px solid rgba(255,255,255,0.3); transition: all 0.3s ease;">
                                <i class="fas fa-info-circle"></i> Chi tiết
                            </a>
                            <button class="btn btn-warning btn-sm copy-code-home flex-fill modern-btn"
                                    data-code="{{ $km->ma_khuyen_mai }}"
                                    style="border-radius: 15px; font-weight: 600; font-size: 0.8rem; padding: 10px 8px;
                                           background: linear-gradient(135deg, #ffd700, #ffed4e); border: none; color: #333;
                                           transition: all 0.3s ease;">
                                <i class="fas fa-copy"></i> Sao chép
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="text-center mt-4">
                <a href="{{ route('client.khuyen-mai.index') }}"
                   class="btn btn-modern-cta"
                   style="border-radius: 30px; padding: 14px 35px; font-weight: 700; font-size: 1rem;
                          background: linear-gradient(135deg, #ffd700, #ffed4e, #ffd700);
                          border: none; color: #333; text-decoration: none;
                          box-shadow: 0 6px 20px rgba(255, 215, 0, 0.4);
                          transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
                          position: relative; overflow: hidden;
                          text-transform: uppercase; letter-spacing: 1px;"
                   onmouseover="this.style.transform='translateY(-3px) scale(1.05)'; this.style.boxShadow='0 8px 25px rgba(255, 215, 0, 0.5)'"
                   onmouseout="this.style.transform='translateY(0) scale(1)'; this.style.boxShadow='0 6px 20px rgba(255, 215, 0, 0.4)'">
                    <div style="position: absolute; top: 0; left: -100%; width: 100%; height: 100%;
                         background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
                         animation: shimmer 3s infinite;"></div>
                    <i class="fas fa-star" style="color: #ff6b35; margin-right: 8px; animation: pulse 2s infinite;"></i>
                    Xem tất cả ưu đãi
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
