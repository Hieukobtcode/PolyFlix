@extends('layouts.client')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Anton&display=swap" rel="stylesheet">
<style>
    html {
        scroll-behavior: smooth;
    }

    .btn-more {
        display: block;
        margin: 0 auto;
        width: 100px;
        height: 30px;
        border: 1px solid yellow;
        border-radius: 5px;
        background-color: #414184;
        color: white;
        font-size: 13px;
        font-weight: bold;
        position: relative;
        overflow: hidden;
        z-index: 1;
    }

    .btn-more::before {
        content: "";
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(to right, #ff6600, #ffcc00);
        z-index: -1;
        transition: left 0.4s ease;
    }

    .btn-more:hover::before {
        left: 0;
    }

    .movie-detail-wrapper {
        display: flex;
    }

    .movie-detail-bg {
        min-height: 100vh;
        padding: 38px 0;
    }

    .movie-detail-card {
        width: 300px;
        background: rgba(255, 255, 255, 0.08);
        border-radius: 18px;
        box-shadow: 0 10px 32px rgba(18, 21, 39, 0.22);
        padding: 28px 18px 28px 32px;
        display: flex;
        flex-direction: row;
        align-items: flex-start;
    }

    .movie-detail-card,
    .sidebar-nowshowing {
        background: #181d2f;
        border-radius: 16px;
        box-shadow: 0 4px 22px #0d10205e;
    }

    .movie-poster-wrapper {
        position: relative;
        width: 300px;
        flex-shrink: 0;
    }

    .movie-poster {
        box-shadow: 0 8px 28px rgba(18, 21, 39, 0.36);
        border-radius: 14px;
        width: 100%;
        aspect-ratio: 2/3;
        object-fit: cover;
    }

    .btn-trailer {
        position: absolute;
        top: 18px;
        right: 18px;
        background: rgba(255, 236, 56, 0.92);
        color: #222;
        border-radius: 38px;
        padding: 6px 20px;
        font-size: 1.03rem;
        font-weight: bold;
        box-shadow: 0 2px 14px #febe2799;
        transition: 0.16s;
        border: none;
        z-index: 2;
    }

    .btn-trailer:hover {
        background: #febe27;
        color: #10152e;
        transform: scale(1.05);
    }

    .movie-info {
        flex-grow: 1;
        margin-left: 36px;
    }

    .movie-title {
        font-family: 'Anton', Arial, sans-serif;
        font-size: 25px;
        color: #ffec38;
        letter-spacing: 1px;
        margin-bottom: 18px;
        text-shadow: 1px 3px 18px #222;
    }

    .movie-label {
        font-weight: bold;
        color: #febe27;
        min-width: 120px;
        display: inline-block;
    }

    .movie-info-col {
        margin-top: 20px;
        min-width: 240px;
        margin-bottom: 30px;
        font-size: 1.08rem;
    }

    .movie-desc-container {
        position: relative;
        margin-bottom: 20px;
    }

    .movie-desc {
        margin-top: 10px;
        font-size: 1.07rem;
        line-height: 1.6;
        color: #ffe;
        background: rgba(0, 0, 0, 0.12);
        border-radius: 9px;
        padding: 13px 14px;
        position: relative;
        cursor: pointer;
        max-height: 130px;
        overflow: hidden;
        transition: max-height 0.3s ease;
    }

    .movie-desc.expanded {
        max-height: none;
    }

    .movie-desc::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 50px;
        background: linear-gradient(transparent, rgba(0, 0, 0, 0.9));
        pointer-events: none;
        transition: opacity 0.3s ease;
    }

    .movie-desc.expanded::after {
        opacity: 0;
    }

    .description-content {
        margin-top: 10px;
        line-height: 1.6;
    }

    .read-more-btn {
        display: block;
        margin-top: 8px;
        color: rgb(192, 252, 227);
        cursor: pointer;
    }


    .btn-galaxy {
        background: linear-gradient(90deg, #febe27 30%, #ffec38 100%);
        color: #191c36;
        border: none;
        font-weight: bold;
        border-radius: 28px;
        padding: 10px 36px;
        font-size: 1.07rem;
        box-shadow: 0 4px 16px #febe277a;
        margin-top: 24px;
        transition: 0.15s;
    }

    .btn-galaxy:hover {
        background: #ffd800;
        color: #1e2538;
        box-shadow: 0 8px 26px #ffec3844;
        transform: translateY(-2px) scale(1.06);
    }

    @media (max-width: 1100px) {
        .movie-detail-card {
            flex-direction: column;
        }

        .sidebar-nowshowing {
            max-height: 300px;
        }

        .movie-info {
            margin-left: 0;
        }
    }

    @media (max-width: 900px) {
        .movie-title {
            font-size: 1.1rem;
        }

        .movie-detail-card {
            padding: 10px 2px;
        }

        .movie-poster-wrapper {
            width: 100%;
            max-width: 340px;
            margin: auto;
        }
    }

    #filter-schedule .form-select {
        min-height: 46px;
        font-size: 1.08rem;
    }

    #btnLoc {
        min-height: 46px;
    }

    @media (max-width: 767px) {
        #filter-schedule .form-label {
            font-size: 1rem;
        }
    }

    #filter-schedule .form-select {
        min-height: 48px;
        font-size: 1.11rem;
        box-shadow: 0 2px 12px #10152e18;
        border: 1.5px solid #e0e0e0;
    }

    #filter-schedule label.form-label {
        font-size: 1.03rem;
        color: #222;
        margin-bottom: .45rem;
    }

    #btnLoc {
        min-height: 48px;
        font-size: 1.09rem;
        font-weight: bold;
        box-shadow: 0 4px 14px #febe2760;
    }

    @media (max-width: 991px) {
        #filter-schedule>div {
            margin-bottom: 12px;
        }
    }

    .lich-chieu-tabs {
        gap: 0.25rem;
    }

    .tab-day,
    .gio-chieu-btn {
        background: #191c36 !important;
        color: #fff !important;
        border: 1.5px solid #353862 !important;
        border-radius: 5px;
        margin: 0 4px 6px 0;
        font-weight: 600;
        width: 150px;
        height: 38px;
        transition: background 0.14s, color 0.14s, border 0.14s;
        box-shadow: 0 2px 10px #10152e22;
    }

    .tab-day.active,
    .tab-day:hover,
    .gio-chieu-btn.active,
    .gio-chieu-btn:hover {
        background: #ffec38 !important;
        color: #181d2f !important;
        border-color: #ffec38 !important;
        box-shadow: 0 2px 16px #ffec3855;
    }

    /* Optional: Giảm width cho nút ngày nhỏ hơn nếu muốn */
    .tab-day {
        min-width: 82px;
    }

    /* Responsive: Cho nút đều nhau trên mobile */
    @media (max-width: 767px) {

        .tab-day,
        .gio-chieu-btn {
            min-width: 60px;
            font-size: 0.95rem;
        }
    }

    .tab-date {
        font-size: 0.98rem;
        font-weight: 400;
    }

    .tab-nav {
        background: #fff;
        border: 1px solid #d7e6f9;
        border-radius: 50%;
        width: 36px;
        height: 36px;
        color: #154ea8;
        margin: 0 4px;
        font-weight: bold;
        font-size: 1.12rem;
    }

    .lich-chieu-tabs-scroller {
        overflow-x: auto;
        scrollbar-width: thin;
    }

    .tab-day {
        background: #fff;
        border: 2px solid #e7eaf3;
        border-radius: 10px;
        color: #154ea8;
        margin-right: 8px;
        font-weight: 600;
        min-width: 92px;
        transition: 0.16s;
        cursor: pointer;
        outline: none;
    }

    .tab-day.active,
    .tab-day:hover {
        background: #144fa6;
        color: #fff;
        border-color: #154ea8;
        box-shadow: 0 3px 18px #1246a620;
    }

    .tab-nav {
        background: #fff;
        border: 1.5px solid #d7e6f9;
        border-radius: 50%;
        width: 36px;
        height: 36px;
        color: #154ea8;
        font-size: 1.16rem;
        box-shadow: 0 2px 8px #d3e5f97a;
        outline: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .filter-select {
        min-height: 44px;
        border-radius: 8px;
        border: 1.4px solid #d7e6f9;
        background: #fff;
        color: #11407e;
        font-weight: 600;
        font-size: 1.09rem;
        box-shadow: 0 2px 14px #e3ecff21;
        transition: 0.14s;
    }

    .filter-group {
        overflow-x: auto;
        scrollbar-width: thin;
        margin-bottom: 8px;
    }

    .filter-btn {
        background: #191c36 !important;
        color: #fff !important;
        border: 1.5px solid #353862 !important;
        border-radius: 10px !important;
        font-weight: 600;
        min-width: 92px;
        min-height: 38px;
        margin: 0 4px 6px 0;
        transition: background 0.14s, color 0.14s, border 0.14s;
        box-shadow: 0 2px 10px #10152e22;
        white-space: nowrap;
    }

    .filter-btn.active,
    .filter-btn:hover {
        background: #ffec38 !important;
        color: #181d2f !important;
        border-color: #ffec38 !important;
        box-shadow: 0 2px 16px #ffec3855;
    }

    .lich-chieu-box {
        background: rgba(16, 21, 46, 0.95);
        /* tím đậm, hoặc đổi thành màu khác nếu muốn */
        border-radius: 16px;
        color: #fff;
        box-shadow: 0 4px 24px #181d2f44;
        margin-top: 32px;
    }

    .tab-chi-nhanh {
        background: #191c36 !important;
        color: #fff !important;
        border: 1.5px solid #353862 !important;
        border-radius: 10px !important;
        margin: 0 4px 6px 0;
        font-weight: 600;
        width: 150px;
        min-height: 38px;
        transition: background 0.14s, color 0.14s, border 0.14s;
        box-shadow: 0 2px 10px #10152e22;
        cursor: pointer;
    }

    .tab-chi-nhanh.active,
    .tab-chi-nhanh:hover {
        background: #ffec38 !important;
        color: #181d2f !important;
        border-color: #ffec38 !important;
        box-shadow: 0 2px 16px #ffec3855;
    }

    .showtime {
        width: 100%;
        margin: 40px auto;
        background: #181d2f;
        color: #fff;
        border-radius: 16px;
        padding: 28px 30px;
        position: relative;
        font-family: 'Arial', sans-serif;
        box-shadow: 0 8px 18px rgba(0, 0, 0, 0.4);
        border: 1px dashed #ffec38;
        overflow: hidden;
    }

    .showtime::before,
    .showtime::after {
        content: '';
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 32px;
        height: 32px;
        background: #f5f5f5;
        border-radius: 50%;
        z-index: 2;
    }

    .showtime::before {
        left: -16px;
    }

    .showtime::after {
        right: -16px;
    }

    .lich-chieu-tabs {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .tab-day,
    .tab-chi-nhanh {
        width: 170px;
        background: #2c3248;
        color: #fff;
        border: 1px solid #555;
        border-radius: 10px;
        font-size: 0.92rem;
        transition: 0.2s ease-in-out;
    }

    .tab-day.active,
    .tab-chi-nhanh.active {
        background: #febe27;
        color: #181d2f;
        font-weight: bold;
    }

    .section-title {
        color: #ffec38;
        font-family: 'Anton', Arial, sans-serif;
        margin-bottom: 18px;
        font-size: 20px;
        letter-spacing: 1px;
    }

    .lich-chieu-rap-box {
        width: 100%;
        background: #20243a;
        border-radius: 12px;
        padding: 25px 20px;
        color: #fff;
        margin-bottom: 30px;
    }

    .lich-chieu-rap-box .badge {
        font-size: 0.9rem;
        color: white;
        font-weight: bold;
    }

    .gio-chieu-btn {
        display: inline-block;
        width: 120px;
        margin: 4px 3px;
        padding: 8px 16px;
        background: #2c3248;
        border-radius: 10px;
        color: #fff;
        font-weight: 500;
        text-decoration: none;
        transition: 0.2s ease-in-out;
        font-size: 0.95rem;
        text-align: center
    }

    .gio-chieu-btn:hover {
        background-color: #febe27;
        color: #181d2f;
    }

    .custom-loading .spinner {
        width: 48px;
        height: 48px;
        border: 5px solid #444;
        border-top: 5px solid #ffec38;
        border-radius: 50%;
        animation: spin 0.9s linear infinite;
        margin: 0 auto;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }
</style>
<div class="container py-3">
    <input type="hidden" id="phimIdInput" value="{{ $phim->id }}">
    <div class="movie-detail-wrapper"
        style="background:rgba(35,40,74,0.90); width:100%;  border-radius:16px; box-shadow:0 4px 22px #0d10205e; padding: 28px 22px;">

        <div class="row g-3" style="width: 100%">
            <!-- Card phim chi tiết -->
            <div class="col-lg-9" style="width:100%">
                <div class="movie-detail-card"
                    style="width:100%; background:transparent; box-shadow:none; border-radius:0;">
                    <!-- Poster & Trailer -->
                    <div class="movie-poster-wrapper">
                        <img src="{{ asset('storage/' . $phim->poster) }}" alt="{{ $phim->ten_phim }}"
                            class="movie-poster">
                        @if ($phim->trailer)
                        <button class="btn-trailer" data-video="{{ $phim->trailer }}" onclick="showTrailer(this)">
                            <i class="fa-solid fa-play"></i> Trailer
                        </button>
                        @endif
                    </div>
                    <!-- Thông tin phim -->
                    <div class="movie-info" style="width: 100%">
                        <div class="movie-title"
                            style="width:100%; display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                            <div class="movie-name" style="word-break: break-word;">
                                {{ $phim->ten_phim }}
                            </div>
                            @if ($phim->do_tuoi)
                            <span class="badge rounded-pill"
                                style="background: #fa3535; color: #fff; font-size: 1.03rem; font-weight: 700; padding: 6px 15px; box-shadow: 0 2px 10px #fa353521; letter-spacing: 1px; display: inline-block; vertical-align: middle; border-radius: 5px;">
                                {{ $phim->do_tuoi }}
                            </span>
                            @endif
                        </div>

                        <div class="mb-2">
                            @if ($phim->so_danh_gia > 0)
                            <span style="font-size:1.2rem; color:#ffc107;">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <=round($phim->diem_trung_binh))
                                    <i class="fa-solid fa-star"></i>
                                    @else
                                    <i class="fa-regular fa-star"></i>
                                    @endif
                                    @endfor
                            </span>
                            <span class="text-warning fw-bold ms-1">{{ $phim->diem_trung_binh }}/5</span>
                            <span class="text-muted ms-2" style="font-size:0.98rem;">
                                ({{ $phim->so_danh_gia }} đánh giá)
                            </span>
                            @else
                            <span class="text-muted">Chưa có đánh giá</span>
                            @endif
                        </div>

                        <div class="movie-info-col">
                            <span class="movie-label">Thể loại:</span>
                            @foreach ($phim->theLoais as $index => $theLoai)
                            <a href="{{ route('theloai.show', $theLoai->id) }}" class="badge bg-info">
                                {{ $theLoai->ten_the_loai }}
                            </a>
                            @if (!$loop->last)
                            ,
                            @endif
                            @endforeach
                        </div>

                        <div class="movie-info-col">
                            <span class="movie-label">Đạo diễn:</span>
                            {{ $phim->dao_dien ?? 'Đang cập nhật' }}
                        </div>

                        <div class="movie-info-col" style="display: flex; margin-bottom: 6px;">
                            <span class="movie-label" style="min-width: 90px; font-weight: bold; color: #fbbf24;">Diễn
                                viên:</span>
                            <div class="movie-value" style="color: #fff; margin-left:35px;">
                                {{ $phim->dien_vien ?? 'Đang cập nhật' }}
                            </div>
                        </div>

                        <div class="movie-info-col">
                            <span class="movie-label">Thời lượng:</span>
                            {{ $phim->thoi_luong ?? 'Đang cập nhật' }} phút
                        </div>

                        <div class="movie-info-col">
                            <span class="movie-label">Khởi chiếu:</span>
                            {{ $phim->ngay_phat_hanh ? \Carbon\Carbon::parse($phim->ngay_phat_hanh)->format('d/m/Y') : 'Đang cập nhật' }}
                        </div>
                    </div>
                </div>
                <div class="movie-desc-container">
                    <div class="movie-desc" id="movieDesc">
                        <span class="movie-label" style="color:#fff;">Nội dung:</span><br>
                        <div class="description-content">
                            {!! nl2br(e($phim->mo_ta)) !!}
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>

    <div id="lich-chieu" class="showtime">

        <div>
            <h4 class="section-title">
                LỊCH CHIẾU
            </h4>

            <div class="lich-chieu-tabs
                mb-3">
                @foreach ($days as $i => $item)
                <button type="button" class="tab-day btn px-3 py-2 mx-1 {{ $i == $currentIndex ? 'active' : '' }}"
                    data-date="{{ $item['date'] }}">
                    <span class="fw-bold">{{ $item['label'] }}, </span>
                    <span class="tab-date small">{{ $item['show'] }}</span>
                </button>
                @endforeach
            </div>
        </div>

        <div style="margin-top: 30px">
            <h4 class="section-title">
                CHI NHÁNH
            </h4>

            <div class="lich-chieu-tabs mb-3">
                @foreach ($chiNhanhs as $j => $chiNhanh)
                <button type="button" class="tab-chi-nhanh btn px-3 py-2 mx-1 {{ $j == 0 ? 'active' : '' }}"
                    data-chinhanh="{{ $chiNhanh->id }}">
                    <span class="fw-bold">{{ $chiNhanh->ten_chi_nhanh }}</span>
                </button>
                @endforeach
            </div>
        </div>

        <div style=" width:100%; margin-top: 30px">
            <h4 class="section-title">
                SUẤT CHIẾU
            </h4>

            <div id="lich-chieu-list">

            </div>
        </div>

    </div>

</div>
</div>
</div>

<div id="trailerPopup"
    style="display:none; width:auto; height:auto; position:fixed; top:50%; left:50%; transform:translate(-50%, -50%);
           background:#000; padding:10px 0 0 0; border-radius:12px; z-index:999;">

    <iframe id="trailerIframe" width="760" height="430" frameborder="0" allowfullscreen></iframe>
    <button onclick="hideTrailer()"
        style="display:block;margin:12px auto 10px auto;background:#febe27;color:#181d2f;font-weight:bold;padding:8px 32px;border:none;border-radius:22px;">Đóng</button>
</div>

<div id="overlayBg"
    style="display:none; position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.7);z-index:998;">
</div>

<script>
    function convertYoutubeUrl(url) {
        if (!url) return '';
        let match = url.match(/(?:youtu\.be\/|youtube\.com\/(?:watch\?(?:.*&)?v=|embed\/|v\/))([a-zA-Z0-9_-]{11})/);
        return match ? "https://www.youtube.com/embed/" + match[1] : url;
    }

    function showTrailer(btn) {
        let videoUrl = $(btn).data('video');
        videoUrl = convertYoutubeUrl(videoUrl);
        $('#trailerIframe').attr('src', videoUrl + '?autoplay=1');
        $('#trailerPopup').show();
        $('#overlayBg').show();
    }

    function hideTrailer() {
        $('#trailerIframe').attr('src', '');
        $('#trailerPopup').hide();
        $('#overlayBg').hide();
    }

    $(function() {
        let phimId = $('#phimIdInput').val();
        let currentDate = $('.tab-day.active').data('date') || '';
        let currentChiNhanh = $('.tab-chi-nhanh.active').data('chinhanh') || '';

        $('.tab-day').on('click', function() {
            $('.tab-day').removeClass('active');
            $(this).addClass('active');
            currentDate = $(this).data('date');
            loadLichChieu();
        });

        $('.tab-chi-nhanh').on('click', function() {
            $('.tab-chi-nhanh').removeClass('active');
            $(this).addClass('active');
            currentChiNhanh = $(this).data('chinhanh');
            loadLichChieu();
        });

        function loadLichChieu() {
            $('#lich-chieu-list').html(`
        <div class="custom-loading text-center py-4">
            <div class="spinner"></div>
            <p class="text-light mt-3" style="font-size: 1.05rem;">Đang tìm kiếm suất chiếu...</p>
        </div>
    `);

            let url = `/phim/${phimId}/lich-chieu?ngay_chieu=${currentDate}&chi_nhanh_id=${currentChiNhanh}`;
            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    // Hiển thị ngay lập tức thay vì delay 3 giây
                    if (!data.html || data.html.trim() === '' || data.html.includes('Không có suất chiếu')) {
                        $('#lich-chieu-list').html(
                            '<div class="alert alert-warning">Không có suất chiếu cho ngày này.</div>'
                        );
                    } else {
                        $('#lich-chieu-list').html(data.html);
                    }
                },
                error: function() {
                    // Hiển thị lỗi ngay lập tức
                    $('#lich-chieu-list').html(
                        '<div class="alert alert-danger">Có lỗi xảy ra khi tải lịch chiếu.</div>'
                    );
                }
            });
        }


        loadLichChieu();
    });

    document.addEventListener('DOMContentLoaded', function() {
        const movieDesc = document.getElementById('movieDesc');

        if (movieDesc) {
            movieDesc.addEventListener('click', function() {
                this.classList.toggle('expanded');
            });
        }
    });
</script>


@endsection