@extends('layouts.client')

@section('styles')
    @php
        use App\Helpers\IdFormatter;
    @endphp
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #1a0933 0%, #3c1a7a 100%);
            --accent-yellow: #ffeb3b;
            --accent-orange: #ff9800;
            --shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            --container-width: 78%;
            --max-width: 1200px;
        }

        /* Cinema Container */
        .cinema-container {
            background: var(--primary-gradient);
            color: #ffffff;
            padding: 20px;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            width: var(--container-width);
            max-width: var(--max-width);
            margin: 0 auto;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.5);
            margin-top: 30px;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
        }

        .cinema-image {
            flex: 1;
            min-width: 300px;
            padding: 20px;
        }

        .cinema-image img {
            width: 100%;
            max-width: 500px;
            border-radius: 15px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
            transition: transform 0.4s ease, box-shadow 0.4s ease;
        }

        .cinema-image img:hover {
            transform: scale(1.05);
            box-shadow: 0 12px 36px rgba(0, 0, 0, 0.4);
        }

        .cinema-details {
            flex: 1;
            min-width: 300px;
            padding: 20px;
            margin-left: 20px;
        }

        .cinema-details h1 {
            color: var(--accent-yellow);
            font-size: 2.5rem;
            font-weight: 700;
            text-transform: uppercase;
            background: linear-gradient(to right, var(--accent-yellow), var(--accent-orange));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 20px;
            letter-spacing: 1px;
        }

        .cinema-details p {
            font-size: 1.125rem;
            line-height: 1.6;
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .cinema-details p i {
            color: var(--accent-yellow);
            margin-right: 12px;
            font-size: 1.25rem;
            width: 24px;
            text-align: center;
        }

        /* Cinema Tabs */
        .cinema-tabs {
            background: #0f0c29;
            color: #ffffff;
            padding: 15px 0;
            display: flex;
            justify-content: center;
            gap: 80px;
            width: var(--container-width);
            max-width: var(--max-width);
            margin: 0 auto;
            box-shadow: var(--shadow);
            border-bottom-left-radius: 12px;
            border-bottom-right-radius: 12px;
        }

        .cinema-tabs a {
            color: #ffffff;
            text-decoration: none;
            padding: 12px 24px;
            font-size: 1rem;
            font-weight: 500;
            text-transform: uppercase;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .cinema-tabs a.active {
            background: var(--accent-yellow);
            color: #0f0c29;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(255, 235, 59, 0.4);
        }

        .cinema-tabs a:hover,
        .cinema-tabs a:focus {
            background: var(--accent-orange);
            color: #ffffff;
            transform: translateY(-2px);
            outline: 2px solid var(--accent-yellow);
            outline-offset: 2px;
        }

        /* Movie List */
        .movie-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
            gap: 30px;
            padding: 25px;
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
        }

        .movie-wrapper {
            display: flex;

            flex-direction: column;
            background: #121633;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: var(--shadow);
        }

        .movie-card {
            display: flex;
            height: 80%;
            gap: 20px;
            background: #121633;
            border-radius: 12px;
            padding: 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5);
            align-items: flex-start;
        }

        .movie-poster {
            flex: 0 0 200px;
        }

        .movie-poster img {
            width: 100%;
            border-radius: 10px;
            object-fit: cover;
            aspect-ratio: 2 / 3;
        }

        .movie-info {
            flex: 1;
            color: #fff;
            display: flex;
            flex-direction: column;
        }

        .movie-info h2 {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 8px;
            color: #ffeb3b;
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 6px;
        }

        .movie-info .badge {
            display: inline-flex;
            align-items: center;
            font-size: 0.85rem;
            color: #fff;
            background-color: #2a2e50;
            padding: 4px 8px;
            border-radius: 6px;
            margin: 2px;
        }

        .movie-info .badge i {
            margin-right: 5px;
        }

        .movie-info p {
            font-size: 0.95rem;
            color: #ddd;
            margin: 6px 0;
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            align-items: center;
        }

        .movie-info p i {
            margin-right: 6px;
            color: #ff9800;
        }

        .select-date {
            width: fit-content;
            padding: 6px 12px;
            margin: 8px 0;
            border-radius: 6px;
            border: none;
            background: #1a1d3a;
            color: #fff;
            cursor: pointer;
        }

        .view-more {
            color: #ffeb3b;
            font-weight: 500;
            text-decoration: underline;
            margin-top: 10px;
            display: inline-block;
            transition: 0.3s ease;
        }

        .view-more:hover {
            color: #ffc107;
        }

        .suat-chieu-container {
            padding: 15px 20px 20px;
            background: #1a1d3a;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        details {
            background: #454578;
            color: white;
            border-radius: 6px;
            margin-bottom: 10px;
            padding: 10px 15px;
        }

        summary {
            font-weight: bold;
            cursor: pointer;
            list-style: none;
        }

        summary::marker {
            display: none;
        }

        .room-title {
            margin-top: 10px;
            font-weight: bold;
            color: #ccc;
        }

        .hour {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 8px;
            margin-top: 6px;
            padding-bottom: 5px;
        }

        .time-btn {
            display: block;
            text-align: center;
            padding: 6px 12px;
            background-color: #292f45;
            color: #fff;
            border-radius: 5px;
            text-decoration: none;
            border: 1px solid transparent;
            font-size: 14px;
        }


        .time-btn:hover {
            background-color: #f1c40f;
            color: #000;
            border-color: #f1c40f;
        }

        .toggle-details {
            position: relative;
            padding-right: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: bold;
            cursor: pointer;
        }

        .arrow-icon {
            display: inline-block;
            transition: transform 0.3s ease;
            width: 10px;
            height: 10px;
            border: solid #ffeb3b;
            border-width: 0 2px 2px 0;
            padding: 3px;
            transform: rotate(45deg);
            margin-left: auto;
        }

        details[open] .arrow-icon {
            transform: rotate(-135deg);
        }

        .no-movie-notice {
            width: 100%;
            padding: 60px 20px;
            text-align: center;
            font-size: 1.4rem;
            color: #ffeb3b;
            background: linear-gradient(135deg, #1c103b 0%, #2a1d5f 100%);
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.4);
            margin-top: 40px;
            margin-bottom: 40px;
            font-weight: 600;
            letter-spacing: 0.5px;
            position: relative;
        }

        .no-movie-notice i {
            display: block;
            font-size: 3rem;
            margin-bottom: 16px;
            color: #ff9800;
        }

        .no-showtime-notice {
            background: #1a1d3a;
            border-radius: 12px;
            padding: 32px 20px;
            text-align: center;
            font-size: 1.15rem;
            font-weight: 500;
            color: #ffeb3b;
            margin-top: 16px;
            margin-bottom: 16px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.4);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
        }

        .no-showtime-notice i {
            font-size: 2rem;
            color: #ff9800;
        }

        .special-label {
            display: inline-block;
            background-color: #ff9800;
            color: #fff;
            font-size: 0.8rem;
            padding: 2px 8px;
            margin-left: 10px;
            border-radius: 10px;
            font-weight: bold;
        }

        /* Khuyến mãi */
        .khuyen-mai {
            width: 100%;
            max-width: 1200px;
            padding: 0 15px;
            box-sizing: border-box;
            margin: 0 auto;
        }

        .khuyen-mai {
            width: 80%;
            margin: 0px auto;
        }

        .khuyen-mai p {
            margin-top: 70px;
            font-family: "Anton", sans-serif;
            color: #ffffff;
            font-size: 30px;
            font-weight: normal;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .khuyen-mai .img {
            margin-top: 30px;
            display: flex;
            gap: 70px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .khuyen-mai .img img {
            width: 330px;
            border-radius: 10px;
        }

        .btn-km {
            display: block;
            margin: 43px auto 0px auto;
            width: 250px;
            height: 44px;
            border: 1px solid yellow;
            border-radius: 5px;
            background-color: #414184;
            color: white;
            font-size: 18px;
            font-weight: bold;
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        .btn-km::before {
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

        .btn-km:hover::before {
            left: 0;
        }

        /* Mini Map Styling */
        .mini-map {
            width: var(--container-width);
            max-width: var(--max-width);
            margin: 40px auto 60px;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: var(--shadow);
        }

        .mini-map iframe {
            width: 100%;
            height: 500px;
            /* hoặc 350px, tùy bạn */
            border: 0;
            display: block;
            border-radius: 12px;
        }
    </style>
@endsection

@section('content')
    <div class="cinema-container">
        <div class="cinema-image">
            <img src="https://api-website.cinestar.com.vn/media/wysiwyg/CinemaImage/01-Quoc-Thanh-masthead.jpg"
                alt="Cinestar Quốc Thanh">
        </div>
        <div class="cinema-details">
            <h1>{{ $rap->ten_rap }}</h1>
            <p><i class="fas fa-map-marker-alt"></i> {{ $rap->dia_chi }}</p>
        </div>
    </div>

    <div class="cinema-tabs">
        <a href="#" class="tab-link active" data-tab="dang-chieu">PHIM ĐANG CHIẾU</a>
        <a href="#" class="tab-link" data-tab="sap-chieu">PHIM SẮP CHIẾU</a>
        <a href="#" class="tab-link" data-tab="dac-biet">SUẤT CHIẾU ĐẶC BIỆT</a>
        {{-- <a href="#">BẢNG GIÁ VÉ</a> --}}
    </div>

    <div id="dang-chieu" class="movie-list tab-content active">
        {{-- Nội dung phim đang chiếu --}}
        @if ($phimDangChieu->isEmpty())
            <div class="no-movie-notice">
                <i class="fas fa-film"></i>
                Không có phim nào đang chiếu tại rạp hiện tại.
            </div>
        @else
            @foreach ($phimDangChieu as $item)
                <div class="movie-wrapper">
                    <div class="movie-card">
                        <div class="movie-poster">
                            <img src="{{ asset('storage/' . $item->poster) }}" alt="{{ $item->ten_phim }}" loading="lazy">
                        </div>

                        <div class="movie-info">
                            <h2>
                                {{ $item->ten_phim }}
                                @if ($item->do_tuoi)
                                    <span class="badge"><i class="fas fa-user-shield"></i> {{ $item->do_tuoi }}</span>
                                @endif
                            </h2>

                            <p><i class="fas fa-globe"></i> {{ $item->quoc_gia }}</p>
                            <p><i class="fas fa-clock"></i> {{ $item->thoi_luong }} phút</p>

                            <p><i class="fas fa-tags"></i>
                                @foreach ($item->theLoais as $theLoai)
                                    <span class="badge"><i class="fas fa-film"></i> {{ $theLoai->ten_the_loai }}</span>
                                @endforeach
                            </p>

                            <p><i class="fas fa-closed-captioning"></i>
                                @foreach ($item->phuDes as $phuDe)
                                    <span class="badge"><i class="fas fa-language"></i> {{ $phuDe->ten_phu_de }}</span>
                                @endforeach
                            </p>
                        </div>
                    </div>

                    <div class="suat-chieu-container">
                        @php
                            $suatChieuPhim = $suatChieuTheoPhim[$item->id] ?? collect();
                        @endphp

                        @if ($suatChieuPhim->isEmpty())
                            <div class="no-showtime-notice">
                                <i class="fas fa-clock"></i>
                                Hiện chưa có suất chiếu nào cho phim này.
                            </div>
                        @else
                            @foreach ($suatChieuPhim->sortBy('ngay_chieu')->groupBy('ngay_chieu') as $ngay => $suatChieusTrongNgay)
                                @php
                                    $ngayFormatted = \Carbon\Carbon::parse($ngay)->translatedFormat('l, d/m/Y');
                                    $ngayFormatted = mb_convert_case($ngayFormatted, MB_CASE_TITLE, 'UTF-8');
                                @endphp

                                <details>
                                    <summary class="toggle-details">
                                        {{ $ngayFormatted }}
                                        <span class="arrow-icon"></span>
                                    </summary>

                                    @foreach ($suatChieusTrongNgay->groupBy('formatted_version') as $version => $suatChieusTheoVersion)
                                        <div class="room-title">
                                            {{ $version }}
                                        </div>
                                        <div class="hour">
                                            @foreach ($suatChieusTheoVersion as $suat)
                                                <a href="{{ route('client.dat-ve', ['params' => encrypt($suat->phim_id . '-' . $suat->id)]) }}" class="time-btn">
                                                    {{ \Carbon\Carbon::parse($suat->bat_dau)->format('H:i') }}
                                                </a>
                                            @endforeach
                                        </div>
                                    @endforeach
                                </details>
                            @endforeach
                            <a href="{{ route('phim.chi-tiet', $item->id) }}" class="view-more">Xem thêm lịch chiếu</a>
                        @endif
                    </div>

                </div>
            @endforeach
        @endif

    </div>

    <div id="sap-chieu" class="movie-list tab-content" style="display: none;">
        {{-- Nội dung phim sắp chiếu --}}
        @if ($phimSapChieu->isEmpty())
            <div class="no-movie-notice">
                <i class="fas fa-film"></i>
                Không có phim nào sắp chiếu tại rạp hiện tại.
            </div>
        @else
            @foreach ($phimSapChieu as $item)
                <div class="movie-wrapper">
                    <div class="movie-card">
                        <div class="movie-poster">
                            <img src="{{ asset('storage/' . $item->poster) }}" alt="{{ $item->ten_phim }}" loading="lazy">
                        </div>

                        <div class="movie-info">
                            <h2>
                                {{ $item->ten_phim }}
                                @if ($item->do_tuoi)
                                    <span class="badge"><i class="fas fa-user-shield"></i> {{ $item->do_tuoi }}</span>
                                @endif
                            </h2>

                            <p><i class="fas fa-globe"></i> {{ $item->quoc_gia }}</p>
                            <p><i class="fas fa-clock"></i> {{ $item->thoi_luong }} phút</p>

                            <p><i class="fas fa-tags"></i>
                                @foreach ($item->theLoais as $theLoai)
                                    <span class="badge"><i class="fas fa-film"></i>
                                        {{ $theLoai->ten_the_loai }}</span>
                                @endforeach
                            </p>

                            <p><i class="fas fa-closed-captioning"></i>
                                @foreach ($item->phuDes as $phuDe)
                                    <span class="badge"><i class="fas fa-language"></i>
                                        {{ $phuDe->ten_phu_de }}</span>
                                @endforeach
                            </p>
                        </div>
                    </div>

                    <div class="suat-chieu-container">
                        @php
                            $suatChieuPhim = $suatChieuTheoPhim[$item->id] ?? collect();
                        @endphp

                        @if ($suatChieuPhim->isEmpty())
                            <div class="no-showtime-notice">
                                <i class="fas fa-clock"></i>
                                Hiện chưa có suất chiếu nào cho phim này.
                            </div>
                        @else
                            @foreach ($suatChieuPhim->sortBy('ngay_chieu')->groupBy('ngay_chieu') as $ngay => $suatChieusTrongNgay)
                                @php
                                    $ngayFormatted = \Carbon\Carbon::parse($ngay)->translatedFormat('l, d/m/Y');
                                    $ngayFormatted = mb_convert_case($ngayFormatted, MB_CASE_TITLE, 'UTF-8');
                                @endphp

                                <details>
                                    <summary class="toggle-details">
                                        {{ $ngayFormatted }}
                                        <span class="arrow-icon"></span>
                                    </summary>

                                    @foreach ($suatChieusTrongNgay->groupBy('phongChieu.loaiPhong.ten_loai_phong') as $tenPhong => $suatChieusTheoPhong)
                                        <div class="room-title">
                                            {{ $suatChieusTheoPhong->first()->formatted_version ?? '' }}
                                        </div>
                                        <div class="hour">
                                            @foreach ($suatChieusTheoPhong as $suat)
                                                <a href="{{ route('client.dat-ve', ['params' => encrypt($suat->phim_id . '-' . $suat->id)]) }}" class="time-btn">
                                                    {{ \Carbon\Carbon::parse($suat->bat_dau)->format('H:i') }}
                                                </a>
                                            @endforeach
                                        </div>
                                    @endforeach

                                </details>
                            @endforeach
                            <a href="{{ route('phim.chi-tiet', $item->id) }}" class="view-more">Xem thêm lịch chiếu</a>
                        @endif
                    </div>

                </div>
            @endforeach
        @endif
    </div>

    <div id="dac-biet" class="movie-list tab-content" style="display: none;">
        {{-- Nội dung suất chiếu đặc biệt --}}
        @if ($phimCoSuatDacBiet->isEmpty())
            <div class="no-movie-notice">
                <i class="fas fa-film"></i>
                Không có suất chiếu đặc biệt tại rạp hiện tại.
            </div>
        @else
            @foreach ($phimCoSuatDacBiet as $item)
                <div class="movie-wrapper">
                    <div class="movie-card">
                        <div class="movie-poster">
                            <img src="{{ asset('storage/' . $item->poster) }}" alt="{{ $item->ten_phim }}" loading="lazy">
                        </div>

                        <div class="movie-info">
                            <h2>
                                {{ $item->ten_phim }}
                                @if ($item->do_tuoi)
                                    <span class="badge"><i class="fas fa-user-shield"></i> {{ $item->do_tuoi }}</span>
                                @endif
                            </h2>

                            <p><i class="fas fa-globe"></i> {{ $item->quoc_gia }}</p>
                            <p><i class="fas fa-clock"></i> {{ $item->thoi_luong }} phút</p>

                            <p><i class="fas fa-tags"></i>
                                @foreach ($item->theLoais as $theLoai)
                                    <span class="badge"><i class="fas fa-film"></i>
                                        {{ $theLoai->ten_the_loai }}</span>
                                @endforeach
                            </p>

                            <p><i class="fas fa-closed-captioning"></i>
                                @foreach ($item->phuDes as $phuDe)
                                    <span class="badge"><i class="fas fa-language"></i>
                                        {{ $phuDe->ten_phu_de }}</span>
                                @endforeach
                            </p>
                        </div>
                    </div>

                    {{-- Suất chiếu đặc biệt --}}
                    <div class="suat-chieu-container">
                        @php
                            $suatChieuPhim = $item->suatChieus ?? collect();
                        @endphp

                        @if ($suatChieuPhim->isEmpty())
                            <div class="no-showtime-notice">
                                <i class="fas fa-clock"></i>
                                Hiện chưa có suất chiếu nào cho phim này.
                            </div>
                        @else
                            @foreach ($suatChieuPhim->sortBy('ngay_chieu')->groupBy('ngay_chieu') as $ngay => $suatChieusTrongNgay)
                                @php
                                    $ngayFormatted = \Carbon\Carbon::parse($ngay)->translatedFormat('l, d/m/Y');
                                    $ngayFormatted = mb_convert_case($ngayFormatted, MB_CASE_TITLE, 'UTF-8');
                                    $isBeforeRelease = \Carbon\Carbon::parse($ngay)->lt(
                                        \Carbon\Carbon::parse($item->ngay_phat_hanh),
                                    );
                                @endphp

                                <details>
                                    <summary class="toggle-details">
                                        {{ $ngayFormatted }}
                                        @if ($isBeforeRelease)
                                            <span class="badge special-label">🎯</span>
                                        @endif
                                        <span class="arrow-icon"></span>
                                    </summary>

                                    @foreach ($suatChieusTrongNgay->groupBy('formatted_version') as $version => $suatChieusTheoVersion)
                                        <div class="room-title">
                                            {{ $version }}
                                        </div>
                                        <div class="hour">
                                            @foreach ($suatChieusTheoVersion as $suat)
                                                <a href="{{ route('client.dat-ve', ['params' => encrypt($suat->phim_id . '-' . $suat->id)]) }}" class="time-btn">
                                                    {{ \Carbon\Carbon::parse($suat->bat_dau)->format('H:i') }}
                                                </a>
                                            @endforeach
                                        </div>
                                    @endforeach
                                </details>
                            @endforeach
                            <a href="{{ route('phim.chi-tiet', $item->id) }}" class="view-more">Xem thêm lịch chiếu</a>
                        @endif
                    </div>
                </div>
            @endforeach
        @endif
    </div>

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

    {{-- Hiện thị bản đồ --}}
    @php
        $diaChiEncoded = urlencode($rap->dia_chi);
    @endphp

    <div class="mini-map">
        <iframe loading="lazy" allowfullscreen referrerpolicy="no-referrer-when-downgrade"
            src="https://www.google.com/maps?q={{ $diaChiEncoded }}&output=embed">
        </iframe>
    </div>


@endsection

@section('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const tabLinks = document.querySelectorAll(".tab-link");
            const tabContents = document.querySelectorAll(".tab-content");

            tabLinks.forEach(link => {
                link.addEventListener("click", function(e) {
                    e.preventDefault();

                    // Xóa class active khỏi tất cả các tab
                    tabLinks.forEach(l => l.classList.remove("active"));

                    // Ẩn tất cả nội dung tab
                    tabContents.forEach(c => c.style.display = "none");

                    // Thêm class active cho tab được chọn
                    this.classList.add("active");

                    // Hiện nội dung tương ứng
                    const targetId = this.dataset.tab;
                    const targetContent = document.getElementById(targetId);
                    if (targetContent) {
                        targetContent.style.display = "grid"; // hoặc "block" tùy cấu trúc
                    }
                });
            });
        });
    </script>
@endsection
