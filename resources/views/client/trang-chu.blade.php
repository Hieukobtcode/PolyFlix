@extends('layouts.client')
@section('content')
    <link href="https://fonts.googleapis.com/css2?family=Anton&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
        integrity="sha512-dYkA5Kj8SGrWJQ2r7S4JblmQo2+3ZJfzv+y5eA6TeK4kD4i2yHMyhzTKoH9yKxKdRYg3C1f58TbzOdKJejO3dg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    @vite('resources/js/trang-chu.js')

    <div class="banner">
        <img src="{{ asset('banner/1215wx365h_6_.jpg') }}" alt="">
    </div>

    <div class="booking-fast">
        <div class="btn">
            <span>ĐẶT VÉ NHANH</span>
        </div>
        <div class="select">
            <select required name="select-rap" class="movie-select">
                <option value="" disabled selected>1-Chọn rạp</option>
                <option value="">Poly Long Biên</option>
            </select>
            <select required name="select-phim" class="movie-select">
                <option disabled selected value="">2-Chọn phim</option>
                <option value="">Mượn hồn đoạt xác</option>
            </select>
            <select required name="select-date" class="movie-select">
                <option disabled selected value="">3-Chọn ngày</option>
                <option value="">Thứ tư - 18/6</option>
            </select>
            <select required name="select-suat" class="movie-select">
                <option disabled selected value="">4-Chọn suất</option>
                <option value="">21h50 - 3D Vietsub</option>
            </select>
            <button>Đặt ngay</button>
        </div>
    </div>

    <div class="menu">
        <button type="button"></button>
        <p class="movie">PHIM</p>
        <div class="list">
            <p>Đang chiếu</p>
            <p>Sắp chiếu</p>
        </div>
    </div>

    <div class="list-movie">
        <div class="movie">
            <div class="img-wrapper">
                <img src="https://cdn.galaxycine.vn/media/2025/2/17/bi-kip-luyen-rong-500_1739776695143.jpg" alt="">
                <div class="overlay">
                    <button class="btn buy"><i class="fa-solid fa-ticket"></i> Mua vé</button>
                    <button class="btn trailer"><i class="fa-solid fa-video"></i> Trailer</button>
                </div>
            </div>
            <p>Bí Kíp Luyện Rồng</p>
        </div>
        <div class="movie">
            <div class="img-wrapper">
                <img src="https://cdn.galaxycine.vn/media/2025/2/17/bi-kip-luyen-rong-500_1739776695143.jpg" alt="">
                <div class="overlay">
                    <button class="btn buy"><i class="fa-solid fa-ticket"></i> Mua vé</button>
                    <button class="btn trailer"><i class="fa-solid fa-video"></i> Trailer</button>
                </div>
            </div>
            <p>Bí Kíp Luyện Rồng</p>
        </div>
        <div class="movie">
            <div class="img-wrapper">
                <img src="https://cdn.galaxycine.vn/media/2025/2/17/bi-kip-luyen-rong-500_1739776695143.jpg" alt="">
                <div class="overlay">
                    <button class="btn buy"><i class="fa-solid fa-ticket"></i> Mua vé</button>
                    <button class="btn trailer"><i class="fa-solid fa-video"></i> Trailer</button>
                </div>
            </div>
            <p>Bí Kíp Luyện Rồng</p>
        </div>
        <div class="movie">
            <div class="img-wrapper">
                <img src="https://cdn.galaxycine.vn/media/2025/2/17/bi-kip-luyen-rong-500_1739776695143.jpg" alt="">
                <div class="overlay">
                    <button class="btn buy"><i class="fa-solid fa-ticket"></i> Mua vé</button>
                    <button class="btn trailer"><i class="fa-solid fa-video"></i> Trailer</button>
                </div>
            </div>
            <p>Bí Kíp Luyện Rồng</p>
        </div>

    </div>
    <button class="btn-see">XEM THÊM</button>

    <div class="khuyen-mai">
        <p>KHUYẾN MÃI</p>
        <div class="img">
            <img width="350px" src="{{ asset('khuyen-mai/c_student.png') }}" alt="">
            <img width="350px" src="{{ asset('khuyen-mai/C_TEN.png') }}" alt="">
            <img width="350px" src="{{ asset('khuyen-mai/monday_1_.jpg') }}" alt="">
        </div>
    </div>

    <button class="btn-km">TẤT CẢ ƯU ĐÃI</button>

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
                    <a href="{{ route('show-bai-viet', $baiViet[0]->id) }}">
                        <h3>{{ $baiViet[0]->tieu_de }}</h3>
                    </a>
                </div>

                {{-- Danh sách bài viết còn lại --}}
                <div class="tin-tuc-danh-sach">
                    @foreach ($baiViet->skip(1) as $bv)
                        <div class="tin-tuc-item">
                            <div class="thumb">
                                <a href="{{ route('show-bai-viet', $bv->id) }}">
                                    <img src="{{ asset('storage/' . $bv->hinh_anh) }}" alt="{{ $bv->tieu_de }}">
                                </a>
                            </div>
                            <div class="info">
                                <a href="{{ route('show-bai-viet', $bv->id) }}">
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
                document.getElementById('tab-' + this.dataset.tab).classList.add('active');
            });
        });
    </script>

@endsection
