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

    {{-- <div class="banner"> --}}
    {{-- <img src="{{ asset('banner/1215wx365h_6_.jpg') }}" alt="">
    </div> --}}
    <style>
.ten-phim {
    display: -webkit-box;
    -webkit-line-clamp: 2; /* Giới hạn 2 dòng */
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

</style>

    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <!-- Banner Slider -->
    <div class="swiper banner-slider"
        style="max-width: 79%; ;height:450px; margin: 20px auto 20px auto; border-radius: 10px; overflow: hidden;">
        <div class="swiper-wrapper">
            @foreach ($banners as $banner)
                <div class="swiper-slide" style="width: 100%">
                    <img src="{{ asset($banner->hinh_anh) }}" alt="Banner {{ $banner->id }}"
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


    <div class="booking-fast">
        <div class="btn">
            <span>ĐẶT VÉ NHANH</span>
        </div>
        <div class="select">
            <select required name="select-chi-nhanh" id="select-chi-nhanh" class="movie-select">
                <option value="" disabled selected>1-Chọn rạp</option>
            </select>
            <select required name="select-phim" id="select-phim" class="movie-select" disabled>
                <option disabled selected value="">2-Chọn phim</option>
            </select>
            <select required name="select-date" id="select-date" class="movie-select" disabled>
                <option disabled selected value="">3-Chọn ngày</option>
            </select>
            <select required name="select-suat" id="select-suat" class="movie-select" disabled>
                <option disabled selected value="">4-Chọn suất</option>
            </select>
            <button id="btn-dat-ngay" disabled>Đặt ngay</button>
        </div>

        <!-- Loading indicator -->
        <div id="booking-loading" class="booking-loading" style="display: none;">
            <div class="spinner"></div>
            <span>Đang tải...</span>
        </div>
    </div>

    <div class="menu">
        <button type="button"></button>
        <p class="movie">PHIM</p>
        {{-- <div class="list">
        <a href="{{ route('home', ['tab' => 'dang-chieu']) }}" class="tab-item {{ $tab == 'dang-chieu' ? 'active' : '' }}">Đang chiếu</a>
        <a href="{{ route('home', ['tab' => 'sap-chieu']) }}" class="tab-item {{ $tab == 'sap-chieu' ? 'active' : '' }}">Sắp chiếu</a>
    </div> --}}
    
    <div class="list">
        <p>
        <a href="#" class="tab-item active" data-tab="dang-chieu">Đang chiếu</a></p>
       <p>
        <a href="#" class="tab-item" data-tab="sap-chieu">Sắp chiếu</a>
       </p>
    </div>
</div>

    </div>

    <div class="list-movie">
    @foreach ($allPhims as $phim)
        <div class="movie">
            <div class="img-wrapper">
                <img src="{{ asset('storage/' . $phim->poster) }}" alt="{{ $phim->ten_phim }}">
                  <div class="age-label">{{ $phim->do_tuoi }}</div> 
                <div class="overlay">
                    <button class="btn buy"><i class="fa-solid fa-ticket"></i> Mua vé</button>
                    <button class="btn trailer" data-video="{{ $phim->trailer }}"><i class="fa-solid fa-video"></i> Trailer</button>
                </div>
            </div>
            <p class="ten-phim">{{ $phim->ten_phim }}</p>
        </div>
    @endforeach
</div>


<a href="{{ route('phim.dang-chieu') }}" class="btn-see-more">
    <button class="btn-see">XEM THÊM</button>
</a>


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
    <div class="new">
        <button type="button"></button>
        <p>GÓC ĐIỆN ẢNH</p>
        <div class="list">
            <p class="tab-item active" data-tab="binhluan">Bình luận phim</p>
            <p class="tab-item" data-tab="blog">Blog điện ảnh</p>
        </div>
    </div>

    <div id="tab-binhluan" class="tab-content active">
        <div id="slide-container">
            @foreach ($phims as $index => $phim)
                <div class="slide" style="{{ $index === 0 ? '' : 'display:none;' }}">
                    <div class="khung-binh-luan">

                        {{-- Poster --}}
                        <div class="poster">
                            <img src="{{ asset('storage/' . $phim->poster) }}" alt="{{ $phim->ten_phim }}">
                        </div>

                        {{-- Bình luận --}}
                        <div class="binh-luan">
                            <h4 class="ten-phim">{{ $phim->ten_phim }}</h4>
                            @forelse ($phim->comments as $binhLuan)
                                @php
                                    $userRating = $ratings->first(function ($r) use ($binhLuan) {
                                        return $r->user_id === $binhLuan->user_id && $r->phim_id === $binhLuan->phim_id;
                                    });
                                @endphp
                                <div class="binh-luan-item">
                                    <div class="avatar">
                                        <img src="{{ $binhLuan->user && $binhLuan->user->avatar
                                            ? asset('storage/' . $binhLuan->user->avatar)
                                            : asset('logo/user.jpg') }}"
                                            alt="{{ $binhLuan->user->name ?? 'Người dùng' }}">
                                    </div>
                                    <div class="noi-dung">
                                        <strong>{{ $binhLuan->user->name ?? 'Ẩn danh' }}</strong>
                                        @if ($userRating)
                                            <span style="color: orange;">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    {{ $i <= $userRating->rating ? '★' : '☆' }}
                                                @endfor
                                            </span>
                                        @endif
                                        <p>{{ $binhLuan->content }}</p>
                                        <small>{{ $binhLuan->created_at->format('d/m/Y H:i') }}</small>
                                    </div>
                                </div>
                            @empty
                                <p style="color: #ccc;">Chưa có bình luận cho phim này.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="slide-controls">
            <div class="slide-button-group">
                <button class="btn-see1 left" onclick="changeSlide(-1)">‹</button>
                <button class="btn-see1 right" onclick="changeSlide(1)">›</button>
            </div>
        </div>
    </div>

    <div id="tab-blog" class="tab-content">
        @if ($baiViet && count($baiViet))
            <div class="tin-tuc-wrapper">
                {{-- Bài viết nổi bật --}}
                <div class="tin-tuc-noi-bat">
                    <img src="{{ asset('storage/' . $baiViet[0]->hinh_anh) }}" alt="{{ $baiViet[0]->tieu_de }}">
                    <a href="{{ route('show-bai-viet', IdFormatter::uuidify($baiViet[0]->id)) }}">
                        <h3>{{ $baiViet[0]->tieu_de }}</h3>
                    </a>
                </div>

                {{-- Danh sách bài viết còn lại --}}
                <div class="tin-tuc-danh-sach">
                    @foreach ($baiViet->skip(1) as $bv)
                        <div class="tin-tuc-item">
                            <div class="thumb">
                                <a href="{{ route('show-bai-viet', IdFormatter::uuidify($bv->id)) }}">
                                    <img src="{{ asset('storage/' . $bv->hinh_anh) }}" alt="{{ $bv->tieu_de }}">
                                </a>
                            </div>
                            <div class="info">
                                <a href="{{ route('show-bai-viet', IdFormatter::uuidify($bv->id)) }}">
                                    <h4>{{ $bv->tieu_de }}</h4>
                                    <p class="noi-dung">{{ $bv->noi_dung }}</p>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <a href="{{ route('client.bai-viet') }}">
                    <button class="btn-see">Xem thêm</button>
                </a>
            </div>
        @else
            <p>Chưa có bài viết nào.</p>
        @endif
    </div>
    <!-- Popup trailer -->
<div id="trailerPopup" style="display:none; position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);
background:#000;padding:10px;border-radius:8px;z-index:999;">
    <iframe id="trailerIframe" width="800" height="450" frameborder="0" allowfullscreen></iframe>
</div>
<!-- Overlay mờ nền -->
<div id="overlayBg" style="display:none; position:fixed;top:0;left:0;width:100%;height:100%;
background:rgba(0,0,0,0.7);z-index:998;"></div>

    <script>
        let currentSlide = 0;
        const slides = document.querySelectorAll('.slide');

        function showSlide(index) {
            slides.forEach((slide, i) => {
                slide.style.display = (i === index) ? 'block' : 'none';
            });
        }

        function changeSlide(direction) {
            currentSlide += direction;
            if (currentSlide >= slides.length) currentSlide = 0;
            if (currentSlide < 0) currentSlide = slides.length - 1;
            showSlide(currentSlide);
        }

        setInterval(() => changeSlide(1), 5000);

        document.querySelectorAll('.tab-item').forEach(tab => {
    tab.addEventListener('click', function() {
        document.querySelectorAll('.tab-item').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));

        this.classList.add('active');

        const tabContent = document.getElementById('tab-' + this.dataset.tab);
        if (tabContent) {
            tabContent.classList.add('active');
        }
    });
});

document.querySelectorAll('.menu .list a.tab-item').forEach(tab => {
    tab.addEventListener('click', function (e) {
        e.preventDefault();

        const selectedTab = this.dataset.tab;

        fetch(`/phim-tab?tab=${selectedTab}`)
            .then(response => response.json())
            .then(data => {
                const movieList = document.querySelector('.list-movie');
                if (movieList) {
                    movieList.innerHTML = '';

                    data.phims.forEach(phim => {
                        const poster = phim.poster ? `/storage/${phim.poster}` : '/logo/no-image.png';
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

        document.getElementById('trailerIframe').src = videoUrl + '?autoplay=1';
        document.getElementById('trailerPopup').style.display = 'block';
        document.getElementById('overlayBg').style.display = 'block';
    });
});

                    // Cập nhật active tab
                    document.querySelectorAll('.menu .list a.tab-item').forEach(t => t.classList.remove('active'));
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


</script>
@endsection