@extends('layouts.admin')

@section('title', 'Đặt vé thành công')
@section('page-title', 'Đặt vé thành công')
@section('breadcrumb', 'Đặt vé thành công')

@section('content')
    <style>
        .success-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .success-header {
            text-align: center;
            margin-bottom: 40px;
            padding: 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            color: white;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .success-icon {
            font-size: 4rem;
            margin-bottom: 20px;
            animation: bounce 2s infinite;
        }

        @keyframes bounce {

            0%,
            20%,
            50%,
            80%,
            100% {
                transform: translateY(0);
            }

            40% {
                transform: translateY(-10px);
            }

            60% {
                transform: translateY(-5px);
            }
        }

        .success-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .success-subtitle {
            font-size: 1.2rem;
            opacity: 0.9;
        }

        .ticket-container {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }

        .ticket-main {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            border: 1px solid #e0e6ed;
        }

        .movie-info {
            display: flex;
            gap: 25px;
            margin-bottom: 30px;
            padding-bottom: 25px;
            border-bottom: 2px dashed #e0e6ed;
        }

        .movie-poster {
            width: 120px;
            height: 180px;
            border-radius: 15px;
            object-fit: cover;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .movie-details h3 {
            font-size: 1.8rem;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 15px;
        }

        .detail-item {
            display: flex;
            align-items: center;
            margin-bottom: 12px;
            font-size: 1rem;
        }

        .detail-item i {
            width: 20px;
            margin-right: 12px;
            color: #667eea;
        }

        .seats-section,
        .combo-section {
            margin-bottom: 25px;
        }

        .section-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }

        .section-title i {
            margin-right: 10px;
            color: #667eea;
        }

        .seats-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .seat-item {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 8px 16px;
            border-radius: 25px;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .combo-item {
            background: #f7fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 10px;
        }

        .combo-name {
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 8px;
        }

        .combo-details {
            font-size: 0.9rem;
            color: #718096;
        }

        .ticket-sidebar {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .qr-section {
            background: white;
            border-radius: 20px;
            padding: 25px;
            text-align: center;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            border: 1px solid #e0e6ed;
        }

        .qr-code {
            margin: 20px 0;
        }

        .ticket-code {
            font-family: 'Courier New', monospace;
            font-weight: 700;
            font-size: 1.1rem;
            color: #2d3748;
            letter-spacing: 2px;
            margin-top: 10px;
        }

        .customer-info {
            background: white;
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            border: 1px solid #e0e6ed;
        }

        .customer-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            margin: 0 auto 15px;
            display: block;
            border: 3px solid #667eea;
        }

        .customer-detail {
            text-align: center;
            margin-bottom: 10px;
            color: #4a5568;
        }

        .price-summary {
            background: white;
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            border: 1px solid #e0e6ed;
        }

        .price-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 1px dashed #e2e8f0;
        }

        .price-row:last-child {
            border-bottom: none;
            font-weight: 700;
            font-size: 1.2rem;
            color: #667eea;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 2px solid #e2e8f0;
        }

        .action-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
        }

        .btn-modern {
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary-modern {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }

        .btn-primary-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .btn-outline-modern {
            background: transparent;
            color: #667eea;
            border: 2px solid #667eea;
        }

        .btn-outline-modern:hover {
            background: #667eea;
            color: white;
            transform: translateY(-2px);
        }

        .status-badge {
            display: inline-block;
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            background: linear-gradient(135deg, #48bb78, #38a169);
            color: white;
        }

        @media (max-width: 768px) {
            .ticket-container {
                grid-template-columns: 1fr;
            }

            .movie-info {
                flex-direction: column;
                text-align: center;
            }

            .success-title {
                font-size: 2rem;
            }

            .action-buttons {
                flex-direction: column;
            }
        }

        @media print {
            .action-buttons {
                display: none !important;
            }

            body {
                margin: 20px;
            }
        }

        #ticket-invoice {
            display: none;
        }

        @media print {
            body * {
                visibility: hidden !important;
            }

            #invoice-section,
            #invoice-section * {
                visibility: visible !important;
            }

            #invoice-section {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
        }

        #invoice-section {
            font-family: 'Arial', sans-serif;
            max-width: 800px;
            margin: auto;
            padding: 20px;
            color: #333;
        }

        .invoice-box {
            border: 1px solid #ccc;
            padding: 20px;
            margin-bottom: 40px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
            page-break-after: always;
        }

        .invoice-box:last-child {
            page-break-after: auto;
        }

        .invoice-title {
            text-align: center;
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .invoice-section-title {
            font-size: 18px;
            margin-top: 20px;
            margin-bottom: 10px;
            color: #555;
        }

        .invoice-line {
            margin: 5px 0;
        }

        hr {
            margin: 15px 0;
            border-top: 1px dashed #aaa;
        }
    </style>
    @php
        $phuThuRap = $datVe->suatChieu->phongChieu->rapPhim->phu_thu ?? 0;
        $tongTienGhe = 0;
        foreach ($datVe->gheNgois as $ghe) {
            $tongTienGhe += $ghe->loaiGhe->phu_thu ?? 0;
        }
        $tongTienGhe += $phuThuRap * $datVe->gheNgois->count();

        $tongTienCombo = 0;
        foreach ($datVe->combos as $combo) {
            $tongTienCombo += ($combo->gia ?? 0) * ($combo->pivot->so_luong ?? 1);
        }

        $tongTienDoAn = 0;
        foreach ($datVe->doAns as $doAn) {
            $tongTienDoAn += ($doAn->gia ?? 0) * ($doAn->pivot->so_luong ?? 1);
        }

        $tongThanhTien = $tongTienGhe + $tongTienCombo + $tongTienDoAn;
    @endphp

    <div class="success-container">
        <!-- Container chính -->
        <div class="ticket-container">
            <!-- Thông tin vé chính -->
            <div class="ticket-main">
                <!-- Thông tin phim -->
                <div class="movie-info">
                    <img src="{{ asset('storage/' . $datVe->suatChieu?->phim?->poster) }}"
                        alt="{{ $datVe->suatChieu?->phim?->ten_phim }}" class="movie-poster">
                    <div class="movie-details">
                        <h3>{{ $datVe->suatChieu?->phim?->ten_phim }}</h3>
                        <div class="detail-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>{{ $datVe->suatChieu?->phongChieu?->rapPhim?->ten_rap }} -
                                {{ $datVe->suatChieu?->phongChieu?->rapPhim?->chiNhanh?->ten_chi_nhanh }}</span>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-door-open"></i>
                            <span>Phòng {{ $datVe->suatChieu?->phongChieu?->ten_phong }}</span>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-calendar-alt"></i>
                            <span>{{ \Carbon\Carbon::parse($datVe->suatChieu?->ngay_bat_dau)->format('d/m/Y') }}</span>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-clock"></i>
                            <span>{{ $datVe->suatChieu?->bat_dau }} - {{ $datVe->suatChieu?->ket_thuc }}</span>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-tag"></i>
                            <span class="status-badge">{{ $datVe->trang_thai }}</span>
                        </div>
                    </div>
                </div>

                <!-- Thông tin ghế -->
                <div class="seats-section">
                    <h4 class="section-title">
                        <i class="fas fa-couch"></i>
                        Ghế đã chọn
                    </h4>
                    <div class="seats-grid">
                        @foreach ($datVe->gheNgois as $ghe)
                            <span class="seat-item">{{ $ghe->ma_ghe }}
                                ({{ $ghe->loaiGhe->ten_loai_ghe ?? 'Thường' }})
                            </span>
                        @endforeach
                    </div>
                </div>

                <!-- Thông tin combo/đồ ăn -->
                @if ($datVe->combos->count() > 0 || $datVe->doAns->count() > 0)
                    <div class="combo-section">
                        <h4 class="section-title">
                            <i class="fas fa-utensils"></i>
                            Combo & Đồ ăn
                        </h4>

                        @foreach ($datVe->combos as $combo)
                            <div class="combo-item">
                                <div class="combo-name">{{ $combo->tieu_de }} (x{{ $combo->pivot->so_luong }})</div>
                                <div class="combo-details">{{ number_format($combo->gia ?? 0, 0, ',', '.') }}đ</div>
                            </div>
                        @endforeach

                        @foreach ($datVe->doAns as $doAn)
                            <div class="combo-item">
                                <div class="combo-name">{{ $doAn->tieu_de }} (x{{ $doAn->pivot->so_luong }})</div>
                                <div class="combo-details">{{ number_format($doAn->gia ?? 0, 0, ',', '.') }}đ</div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="ticket-sidebar">
                <!-- Mã QR -->
                <div class="qr-section">
                    <h5 style="margin-bottom: 15px; color: #2d3748;">Mã vé điện tử</h5>
                    <div class="qr-code">
                        {!! DNS1D::getBarcodeHTML($datVe->ma_dat_ve, 'C128', 2, 60) !!}
                    </div>
                    <div class="ticket-code">{{ $datVe->ma_dat_ve }}</div>
                </div>

                <!-- Thông tin khách hàng -->
                <div class="customer-info">
                    <h5 style="margin-bottom: 15px; color: #2d3748; text-align: center;">Thông tin khách hàng</h5>
                    <img src="{{ $datVe->nguoiDung?->avatar ? asset('storage/' . $datVe->nguoiDung->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($datVe->nguoiDung?->ho_ten ?? 'User') . '&background=667eea&color=fff' }}"
                        alt="Avatar" class="customer-avatar">
                    <div class="customer-detail">
                        <strong>{{ $datVe->nguoiDung?->ho_ten ?? 'Khách hàng' }}</strong>
                    </div>
                    <div class="customer-detail">
                        <i class="fas fa-envelope"></i> {{ $datVe->nguoiDung?->email ?? 'Không có email' }}
                    </div>
                    <div class="customer-detail">
                        <i class="fas fa-phone"></i> {{ $datVe->nguoiDung?->so_dien_thoai ?? 'Không có SĐT' }}
                    </div>
                </div>

                <!-- Tóm tắt giá -->
                <div class="price-summary">
                    <h5 style="margin-bottom: 15px; color: #2d3748;">Tóm tắt thanh toán</h5>
                    <div class="price-row">
                        <span>Tiền vé ({{ $datVe->gheNgois->count() }} ghế)</span>
                        <span>{{ number_format($tongTienGhe, 0, ',', '.') }}đ</span>
                    </div>
                    @if ($tongTienCombo > 0)
                        <div class="price-row">
                            <span>Combo</span>
                            <span>{{ number_format($tongTienCombo, 0, ',', '.') }}đ</span>
                        </div>
                    @endif
                    @if ($tongTienDoAn > 0)
                        <div class="price-row">
                            <span>Đồ ăn & nước uống</span>
                            <span>{{ number_format($tongTienDoAn, 0, ',', '.') }}đ</span>
                        </div>
                    @endif
                    <div class="price-row">
                        <span>Tổng cộng</span>
                        <span>{{ number_format($tongThanhTien, 0, ',', '.') }}đ</span>
                    </div>
                </div>
            </div>
        </div>


        <div class="action-buttons">

            <button onclick="printInvoice()" class="btn-modern btn-outline-modern">
                <i class="fas fa-print"></i> In hóa đơn
            </button>
            <a href="{{ route('admin.dat-ves.index') }}" class="btn-modern btn-outline-modern">
                <i class="fas fa-list"></i>
                Quản lý vé
            </a>
        </div>
    </div>

    <div id="invoice-section" style="display: none">
        <!-- 🎟️ Vé xem phim -->
        <div class="invoice-box">
            <div class="invoice-title">🎟️ VÉ XEM PHIM</div>
            <p class="invoice-line"><strong>Phim:</strong> {{ $datVe->suatChieu?->phim?->ten_phim ?? 'Không xác định' }}
            </p>
            <p class="invoice-line"><strong>Thời gian:</strong>
                {{ \Carbon\Carbon::parse($datVe->suatChieu?->ngay_chieu)->format('d/m/Y') }}
                - {{ $datVe->suatChieu?->bat_dau ?? '---' }}
            </p>
            <p class="invoice-line"><strong>Rạp:</strong>
                {{ $datVe->suatChieu?->phongChieu?->rapPhim?->ten_rap ?? 'Không xác định' }}</p>
            <p class="invoice-line"><strong>Chi nhánh:</strong>
                {{ $datVe->suatChieu?->phongChieu?->rapPhim?->chiNhanh?->ten_chi_nhanh ?? '---' }}</p>
            <p class="invoice-line"><strong>Phòng:</strong> {{ $datVe->suatChieu?->phongChieu?->ten_phong ?? '---' }}</p>
            <p class="invoice-line"><strong>Ghế:</strong>
                {{ $datVe->gheNgois->pluck('ma_ghe')->join(', ') ?? 'Không có ghế' }}</p>
            <p class="invoice-line"><strong>Khách hàng (Email):</strong>
                {{ $datVe->nguoiDung?->email ?? 'Không có email' }}</p>
            <p class="invoice-line"><strong>Mã vé:</strong> {{ $datVe->ma_dat_ve }}</p>
            <div style="margin-top: 20px; text-align: center;">
                {!! DNS1D::getBarcodeHTML($datVe->ma_dat_ve, 'C128', 2, 60) !!}
            </div>
        </div>

        <!-- 🧾 Hóa đơn tổng -->
        <div class="invoice-box">
            <div class="invoice-title">🧾 HÓA ĐƠN THANH TOÁN</div>
            <p class="invoice-line"><strong>Mã đặt vé:</strong> {{ $datVe->ma_dat_ve }}</p>
            <p class="invoice-line"><strong>Khách hàng:</strong> {{ $datVe->nguoiDung?->email ?? 'Không có email' }}
            </p>

            <hr>
            <div class="invoice-section-title">🎟️ Vé xem phim</div>
            <p class="invoice-line">Phim: {{ $datVe->suatChieu?->phim?->ten_phim ?? '---' }}</p>
            <p class="invoice-line">Thời gian:
                {{ optional($datVe->suatChieu)->ngay_chieu ? \Carbon\Carbon::parse($datVe->suatChieu->ngay_chieu)->format('d/m/Y') : '---' }}
                - {{ $datVe->suatChieu?->bat_dau ?? '---' }}
            </p>
            <p class="invoice-line">Phòng: {{ $datVe->suatChieu?->phongChieu?->ten_phong ?? '---' }}</p>
            <p class="invoice-line">Ghế: {{ $datVe->gheNgois->pluck('ma_ghe')->join(', ') ?? 'Không có ghế' }}</p>
            <p class="invoice-line">Tiền vé: {{ number_format($tongTienGhe ?? 0, 0, ',', '.') }} VND</p>

            @if ($tongTienCombo > 0)
                <div class="invoice-section-title">🍿 Combo</div>
                @foreach ($datVe->combos as $combo)
                    <p class="invoice-line">{{ $combo->tieu_de }} (x{{ $combo->pivot->so_luong }}) -
                        {{ number_format($combo->gia ?? 0, 0, ',', '.') }}đ</p>
                @endforeach
            @endif

            @if ($tongTienDoAn > 0)
                <div class="invoice-section-title">🥤 Đồ ăn & nước uống</div>
                @foreach ($datVe->doAns as $doAn)
                    <p class="invoice-line">{{ $doAn->tieu_de }} (x{{ $doAn->pivot->so_luong }}) -
                        {{ number_format($doAn->gia ?? 0, 0, ',', '.') }}đ</p>
                @endforeach
            @endif

            <hr>
            <p class="invoice-line"><strong>Tổng tiền:</strong> {{ number_format($tongThanhTien ?? 0, 0, ',', '.') }} VND
            </p>
        </div>

        <!-- 🍽️ Hóa đơn đồ ăn -->
        <div class="invoice-box">
            <div class="invoice-title">🍽️ HÓA ĐƠN ĐỒ ĂN</div>
            <p class="invoice-line"><strong>Mã đặt vé:</strong> {{ $datVe->ma_dat_ve }}</p>
            <p class="invoice-line"><strong>Khách hàng:</strong> {{ $datVe->nguoiDung?->email ?? 'Không có email' }}</p>

            <hr>

            @if ($tongTienCombo > 0)
                <div class="invoice-section-title">🍿 Combo</div>
                @foreach ($datVe->combos as $combo)
                    <p class="invoice-line">{{ $combo->tieu_de }} (x{{ $combo->pivot->so_luong }}) -
                        {{ number_format($combo->gia ?? 0, 0, ',', '.') }}đ</p>
                @endforeach
            @endif

            @if ($tongTienDoAn > 0)
                <div class="invoice-section-title">🥤 Đồ ăn & nước uống</div>
                @foreach ($datVe->doAns as $doAn)
                    <p class="invoice-line">{{ $doAn->tieu_de }} (x{{ $doAn->pivot->so_luong }}) -
                        {{ number_format($doAn->gia ?? 0, 0, ',', '.') }}đ</p>
                @endforeach
            @endif

            <hr>
            <p class="invoice-line"><strong>Tổng tiền đồ ăn:</strong>
                {{ number_format($tongTienCombo + $tongTienDoAn ?? 0, 0, ',', '.') }} VND
            </p>
        </div>
    </div>

@endsection
<script>
    function printInvoice() {
        const invoice = document.getElementById("invoice-section");

        // Hiện hóa đơn
        invoice.style.display = "block";

        // In sau 100ms
        setTimeout(() => {
            window.print();
        }, 100);

        // Dù onafterprint hoạt động hay không, sau 1.5s vẫn ẩn hóa đơn lại
        const revertDisplay = () => {
            invoice.style.display = "none";
        };

        // Trình duyệt hỗ trợ
        if (window.matchMedia) {
            const mediaQueryList = window.matchMedia('print');
            mediaQueryList.addListener(function(mql) {
                if (!mql.matches) revertDisplay();
            });
        }

        // Fallback nếu onafterprint không hoạt động
        window.onafterprint = revertDisplay;

        // Thêm fallback bằng timeout sau 3s (nếu mọi thứ kia không chạy)
        setTimeout(revertDisplay, 3000);
    }
</script>
