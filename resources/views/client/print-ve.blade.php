<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vé xem phim - {{ $ve->ma_dat_ve }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }

        .ticket-container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            border: 2px solid #333;
            border-radius: 10px;
            overflow: hidden;
        }

        .ticket-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            text-align: center;
        }

        .ticket-header h1 {
            font-size: 24px;
            margin-bottom: 5px;
        }

        .ticket-header p {
            font-size: 14px;
            opacity: 0.9;
        }

        .ticket-body {
            padding: 30px;
            background: white;
        }

        .movie-section {
            display: table;
            width: 100%;
            margin-bottom: 25px;
            border-bottom: 1px solid #eee;
            padding-bottom: 25px;
        }

        .movie-poster {
            display: table-cell;
            width: 120px;
            vertical-align: top;
            padding-right: 20px;
        }

        .movie-poster img {
            width: 100px;
            height: 140px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #ddd;
        }

        .movie-info {
            display: table-cell;
            vertical-align: top;
        }

        .movie-title {
            font-size: 20px;
            font-weight: bold;
            color: #2d3748;
            margin-bottom: 10px;
        }

        .movie-details {
            margin-bottom: 15px;
        }

        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
            margin-right: 8px;
        }

        .badge-primary { background: #3182ce; color: white; }
        .badge-warning { background: #d69e2e; color: white; }

        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 25px;
        }

        .info-row {
            display: table-row;
        }

        .info-cell {
            display: table-cell;
            width: 50%;
            padding: 8px 0;
            vertical-align: top;
        }

        .info-label {
            font-weight: bold;
            color: #4a5568;
            margin-bottom: 3px;
        }

        .info-value {
            color: #2d3748;
        }

        .seats-section, .food-section {
            margin-bottom: 25px;
            border-bottom: 1px solid #eee;
            padding-bottom: 25px;
        }

        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #2d3748;
            margin-bottom: 15px;
            border-left: 4px solid #667eea;
            padding-left: 10px;
        }

        .seats-grid {
            display: table;
            width: 100%;
        }

        .seat-row {
            display: table-row;
        }

        .seat-cell {
            display: table-cell;
            padding: 8px;
            border: 1px solid #e2e8f0;
            background: #f7fafc;
        }

        .seat-number {
            font-weight: bold;
            color: #2d3748;
        }

        .seat-type {
            font-size: 10px;
            padding: 2px 6px;
            border-radius: 3px;
            margin-left: 5px;
        }

        .seat-type-vip { background: #d69e2e; color: white; }
        .seat-type-couple { background: #e53e3e; color: white; }
        .seat-type-normal { background: #718096; color: white; }

        .seat-price {
            font-weight: bold;
            color: #38a169;
            float: right;
        }

        .food-item {
            display: table;
            width: 100%;
            margin-bottom: 8px;
        }

        .food-info {
            display: table-cell;
            width: 70%;
        }

        .food-price {
            display: table-cell;
            width: 30%;
            text-align: right;
            font-weight: bold;
            color: #38a169;
        }

        .payment-section {
            margin-bottom: 25px;
            background: #f7fafc;
            padding: 15px;
            border-radius: 8px;
        }

        .payment-row {
            display: table;
            width: 100%;
            margin-bottom: 8px;
        }

        .payment-label {
            display: table-cell;
            width: 40%;
            font-weight: bold;
        }

        .payment-value {
            display: table-cell;
            width: 60%;
        }

        .total-section {
            text-align: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 25px;
        }

        .total-amount {
            font-size: 24px;
            font-weight: bold;
        }

        .barcode-section {
            text-align: center;
            border-top: 2px dashed #ccc;
            padding-top: 25px;
        }

        .barcode-title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #2d3748;
        }

        .barcode-container {
            margin-bottom: 10px;
        }

        .ticket-code {
            font-family: 'Courier New', monospace;
            font-size: 14px;
            font-weight: bold;
            color: #2d3748;
            margin-bottom: 10px;
        }

        .barcode-note {
            font-size: 10px;
            color: #718096;
            font-style: italic;
        }

        .footer-info {
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #eee;
            font-size: 10px;
            color: #718096;
        }

        .footer-row {
            display: table;
            width: 100%;
            margin-bottom: 5px;
        }

        .footer-label {
            display: table-cell;
            width: 30%;
        }

        .footer-value {
            display: table-cell;
            width: 70%;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="ticket-container">
        <!-- Header -->
        <div class="ticket-header">
            <h1>🎬 POLYFLIX CINEMA</h1>
            <p>Vé xem phim điện tử</p>
        </div>

        <!-- Body -->
        <div class="ticket-body">
            <!-- Movie Section -->
            <div class="movie-section">
                <div class="movie-poster">
                    @if($ve->suatChieu->phim->hinh_anh)
                        <img src="{{ public_path('storage/' . $ve->suatChieu->phim->hinh_anh) }}" 
                             alt="{{ $ve->suatChieu->phim->ten_phim }}">
                    @endif
                </div>
                <div class="movie-info">
                    <div class="movie-title">{{ $ve->suatChieu->phim->ten_phim }}</div>
                    <div class="movie-details">
                        <span class="badge badge-primary">{{ $ve->suatChieu->phien_ban_phim }}</span>
                        <span class="badge badge-warning">{{ $ve->suatChieu->phim->do_tuoi }}+</span>
                        <span style="color: #718096;">⏱ {{ $ve->suatChieu->phim->thoi_luong }} phút</span>
                    </div>
                </div>
            </div>

            <!-- Cinema & Showtime Info -->
            <div class="info-grid">
                <div class="info-row">
                    <div class="info-cell">
                        <div class="info-label">📅 Ngày chiếu:</div>
                        <div class="info-value">{{ \Carbon\Carbon::parse($ve->suatChieu->ngay_bat_dau)->format('d/m/Y') }}</div>
                    </div>
                    <div class="info-cell">
                        <div class="info-label">🕐 Giờ chiếu:</div>
                        <div class="info-value">{{ \Carbon\Carbon::parse($ve->suatChieu->bat_dau)->format('H:i') }} - {{ \Carbon\Carbon::parse($ve->suatChieu->ket_thuc)->format('H:i') }}</div>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-cell">
                        <div class="info-label">📍 Chi nhánh:</div>
                        <div class="info-value">{{ $ve->suatChieu->phongChieu->rapPhim->chiNhanh->ten_chi_nhanh }}</div>
                    </div>
                    <div class="info-cell">
                        <div class="info-label">🎭 Rạp & Phòng:</div>
                        <div class="info-value">{{ $ve->suatChieu->phongChieu->rapPhim->ten_rap }} - Phòng {{ $ve->suatChieu->phongChieu->ten_phong }}</div>
                    </div>
                </div>
            </div>

            <!-- Seats Section -->
            <div class="seats-section">
                <div class="section-title">🪑 Thông tin ghế</div>
                <div class="seats-grid">
                    @foreach($ve->gheNgois as $ghe)
                        <div class="seat-row">
                            <div class="seat-cell">
                                <span class="seat-number">{{ $ghe->ma_ghe }}</span>
                                <span class="seat-type seat-type-{{ strtolower($ghe->loaiGhe->ten_loai_ghe) == 'vip' ? 'vip' : (strtolower($ghe->loaiGhe->ten_loai_ghe) == 'couple' ? 'couple' : 'normal') }}">
                                    {{ $ghe->loaiGhe->ten_loai_ghe }}
                                </span>
                                <span class="seat-price">{{ number_format($ghe->pivot->gia_ve ?? 0, 0, ',', '.') }}đ</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Food & Combo -->
            @if($ve->combos->count() > 0 || $ve->doAns->count() > 0)
            <div class="food-section">
                <div class="section-title">🍿 Combo & Đồ ăn</div>
                
                @if($ve->combos->count() > 0)
                    <div style="margin-bottom: 15px;">
                        <strong>Combo:</strong>
                        @foreach($ve->combos as $combo)
                            <div class="food-item">
                                <div class="food-info">
                                    {{ $combo->tieu_de }} x{{ $combo->pivot->so_luong }}
                                </div>
                                <div class="food-price">
                                    {{ number_format($combo->gia * $combo->pivot->so_luong, 0, ',', '.') }}đ
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                @if($ve->doAns->count() > 0)
                    <div>
                        <strong>Đồ ăn:</strong>
                        @foreach($ve->doAns as $doAn)
                            <div class="food-item">
                                <div class="food-info">
                                    {{ $doAn->tieu_de }} x{{ $doAn->pivot->so_luong }}
                                </div>
                                <div class="food-price">
                                    {{ number_format($doAn->gia * $doAn->pivot->so_luong, 0, ',', '.') }}đ
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
            @endif

            <!-- Payment Info -->
            <div class="payment-section">
                <div class="payment-row">
                    <div class="payment-label">💳 Phương thức thanh toán:</div>
                    <div class="payment-value">{{ $ve->phuong_thuc_tt }}</div>
                </div>
                <div class="payment-row">
                    <div class="payment-label">📊 Trạng thái:</div>
                    <div class="payment-value">{{ $ve->trang_thai }}</div>
                </div>
                @if($ve->successTransaction)
                    <div class="payment-row">
                        <div class="payment-label">🔢 Mã giao dịch:</div>
                        <div class="payment-value">{{ $ve->successTransaction->transaction_code }}</div>
                    </div>
                    <div class="payment-row">
                        <div class="payment-label">⏰ Thời gian thanh toán:</div>
                        <div class="payment-value">{{ $ve->successTransaction->paid_at ? $ve->successTransaction->paid_at->format('H:i d/m/Y') : 'N/A' }}</div>
                    </div>
                @endif
            </div>

            <!-- Total -->
            <div class="total-section">
                <div>Tổng tiền thanh toán</div>
                <div class="total-amount">{{ number_format($ve->tong_tien, 0, ',', '.') }}đ</div>
            </div>

            <!-- Barcode -->
            <div class="barcode-section">
                <div class="barcode-title">Mã vé điện tử</div>
                <div class="barcode-container">
                    {!! $maVachHtml !!}
                </div>
                <div class="ticket-code">{{ $ve->ma_dat_ve }}</div>
                <div class="barcode-note">Vui lòng xuất trình mã này tại quầy để nhận vé</div>
            </div>

            <!-- Footer Info -->
            <div class="footer-info">
                <div class="footer-row">
                    <div class="footer-label">Người đặt:</div>
                    <div class="footer-value">{{ $ve->nguoiDung->name }}</div>
                </div>
                <div class="footer-row">
                    <div class="footer-label">Email:</div>
                    <div class="footer-value">{{ $ve->nguoiDung->email }}</div>
                </div>
                <div class="footer-row">
                    <div class="footer-label">Ngày đặt:</div>
                    <div class="footer-value">{{ $ve->created_at->format('H:i d/m/Y') }}</div>
                </div>
                <div class="footer-row">
                    <div class="footer-label">Địa chỉ rạp:</div>
                    <div class="footer-value">{{ $ve->suatChieu->phongChieu->rapPhim->chiNhanh->dia_chi }}</div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
