@extends('layouts.admin')

@section('title', 'Đặt vé thành công')
@section('page-title', 'Đặt vé thành công')
@section('breadcrumb', 'Đặt vé thành công')

@section('content')
    <style>
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
    <style>
        .ticket {
            background-color: linear-gradient(to bottom right, #fff0f0, #ffffff);
            border: 2px dashed #e0e0e0;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            padding: 1.5rem;
        }

        .ticket-title {
            font-size: 1.5rem;
            font-weight: bold;
            color: #1f2937;
            text-align: center;
            margin-bottom: 1rem;
        }

        .ticket-line {
            font-size: 1rem;
            color: #374151;
            margin: 0.5rem 0;
        }

        .barcode {
            text-align: center;
            margin-top: 1rem;
        }

        .divider {
            border-top: 1px dashed #9ca3af;
            margin: 1rem 0;
        }

        @media print {
            .no-print {
                display: none;
            }

            .ticket {
                page-break-inside: avoid;
                margin-bottom: 0.5in;
            }
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
                        {{-- ✅ Thời gian đặt vé --}}
                        <div class="detail-item">
                            <i class="fas fa-hourglass-start"></i>
                            <span>Đặt lúc:
                                {{ $datVe->created_at ? $datVe->created_at->format('d/m/Y - H:i') : '---' }}
                            </span>
                        </div>

                        {{-- ✅ Thời gian thanh toán --}}
                        <div class="detail-item">
                            <i class="fas fa-credit-card"></i>
                            <span>Thanh toán:
                                {{ $datVe->ngay_thanh_toan ? \Carbon\Carbon::parse($datVe->ngay_thanh_toan)->format('d/m/Y - H:i') : '---' }}
                            </span>
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
                        <span>Phương thức thanh toán:</span>
                        <span>{{ $datVe->phuong_thuc_tt }}</span>
                    </div>
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

    {{-- <div id="invoice-section" class="p-4">
        <!-- Vé xem phim (mỗi ghế một vé) -->
        @foreach ($datVe->gheNgois as $ghe)
            <div class="ticket">
                <div class="ticket-title">🎟️ VÉ XEM PHIM</div>
                <p class="ticket-line"><strong>Phim:</strong> {{ $datVe->suatChieu?->phim?->ten_phim ?? 'Không xác định' }}</p>
                <p class="ticket-line"><strong>Thời gian:</strong> 
                    {{ optional($datVe->suatChieu)->ngay_chieu ? \Carbon\Carbon::parse($datVe->suatChieu->ngay_chieu)->format('d/m/Y') : '---' }} 
                    - {{ $datVe->suatChieu?->bat_dau ?? '---' }}
                </p>
                <p class="ticket-line"><strong>Rạp:</strong> {{ $datVe->suatChieu?->phongChieu?->rapPhim?->ten_rap ?? 'Không xác định' }}</p>
                <p class="ticket-line"><strong>Chi nhánh:</strong> {{ $datVe->suatChieu?->phongChieu?->rapPhim?->chiNhanh?->ten_chi_nhanh ?? '---' }}</p>
                <p class="ticket-line"><strong>Phòng:</strong> {{ $datVe->suatChieu?->phongChieu?->ten_phong ?? '---' }}</p>
                <p class="ticket-line"><strong>Ghế:</strong> {{ $ghe->ma_ghe }}</p>
                <p class="ticket-line"><strong>Loại ghế:</strong> {{ $ghe->loaiGhe?->ten_loai_ghe ?? 'Không xác định' }}</p>
                <p class="ticket-line"><strong>Giá vé:</strong> {{ number_format($ghe->loaiGhe?->gia_ve ?? 0, 0, ',', '.') }} VND</p>
                <p class="ticket-line"><strong>Khách hàng:</strong> {{ $datVe->nguoiDung?->email ?? 'Không có email' }}</p>
                <p class="ticket-line"><strong>Mã vé:</strong> {{ $datVe->ma_dat_ve }}-{{ $ghe->ma_ghe }}</p>
                <div class="barcode">
                    {!! DNS1D::getBarcodeHTML($datVe->ma_dat_ve, 'C128', 2, 60) !!}
                </div>
            </div>
        @endforeach

        <!-- Hóa đơn combo và đồ ăn -->
        @if ($tongTienCombo > 0 || $tongTienDoAn > 0)
            <div class="ticket">
                <div class="ticket-title">🍽️ HÓA ĐƠN ĐỒ ĂN</div>
                <p class="ticket-line"><strong>Mã đặt vé:</strong> {{ $datVe->ma_dat_ve }}</p>
                <p class="ticket-line"><strong>Khách hàng:</strong> {{ $datVe->nguoiDung?->email ?? 'Không có email' }}</p>
                <div class="divider"></div>

                @if ($tongTienCombo > 0)
                    <div class="ticket-line font-bold">🍿 Combo</div>
                    @foreach ($datVe->combos as $combo)
                        <p class="ticket-line">{{ $combo->tieu_de }} (x{{ $combo->pivot->so_luong }}) - 
                            {{ number_format($combo->gia * $combo->pivot->so_luong, 0, ',', '.') }} VND</p>
                    @endforeach
                @endif

                @if ($tongTienDoAn > 0)
                    <div class="ticket-line font-bold">🥤 Đồ ăn & nước uống</div>
                    @foreach ($datVe->doAns as $doAn)
                        <p class="ticket-line">{{ $doAn->tieu_de }} (x{{ $doAn->pivot->so_luong }}) - 
                            {{ number_format($doAn->gia * $doAn->pivot->so_luong, 0, ',', '.') }} VND</p>
                    @endforeach
                @endif

                <div class="divider"></div>
                <p class="ticket-line font-bold"><strong>Tổng tiền đồ ăn:</strong> 
                    {{ number_format($tongTienCombo + $tongTienDoAn, 0, ',', '.') }} VND</p>
                <div class="barcode">
                    {!! DNS1D::getBarcodeHTML($datVe->ma_dat_ve . '-FOOD', 'C128', 2, 60) !!}
                </div>
            </div>
        @endif

        <!-- Hóa đơn tổng -->
        <div class="ticket">
            <div class="ticket-title">🧾 HÓA ĐƠN TỔNG</div>
            <p class="ticket-line"><strong>Mã đặt vé:</strong> {{ $datVe->ma_dat_ve }}</p>
            <p class="ticket-line"><strong>Khách hàng:</strong> {{ $datVe->nguoiDung?->email ?? 'Không có email' }}</p>
            <div class="divider"></div>

            <div class="ticket-line font-bold">🎟️ Vé xem phim</div>
            <p class="ticket-line">Phim: {{ $datVe->suatChieu?->phim?->ten_phim ?? '---' }}</p>
            <p class="ticket-line">Thời gian: 
                {{ optional($datVe->suatChieu)->ngay_chieu ? \Carbon\Carbon::parse($datVe->suatChieu->ngay_chieu)->format('d/m/Y') : '---' }} 
                - {{ $datVe->suatChieu?->bat_dau ?? '---' }}
            </p>
            <p class="ticket-line">Phòng: {{ $datVe->suatChieu?->phongChieu?->ten_phong ?? '---' }}</p>
            <p class="ticket-line">Ghế: {{ $datVe->gheNgois->pluck('ma_ghe')->join(', ') ?? 'Không có ghế' }}</p>
            <p class="ticket-line">Tiền vé: {{ number_format($tongTienGhe, 0, ',', '.') }} VND</p>

            @if ($tongTienCombo > 0)
                <div class="ticket-line font-bold">🍿 Combo</div>
                @foreach ($datVe->combos as $combo)
                    <p class="ticket-line">{{ $combo->tieu_de }} (x{{ $combo->pivot->so_luong }}) - 
                        {{ number_format($combo->gia * $combo->pivot->so_luong, 0, ',', '.') }} VND</p>
                @endforeach
            @endif

            @if ($tongTienDoAn > 0)
                <div class="ticket-line font-bold">🥤 Đồ ăn & nước uống</div>
                @foreach ($datVe->doAns as $doAn)
                    <p class="ticket-line">{{ $doAn->tieu_de }} (x{{ $doAn->pivot->so_luong }}) - 
                        {{ number_format($doAn->gia * $doAn->pivot->so_luong, 0, ',', '.') }} VND</p>
                @endforeach
            @endif

            <div class="divider"></div>
            <p class="ticket-line font-bold"><strong>Tổng thanh toán:</strong> 
                {{ number_format($tongThanhTien, 0, ',', '.') }} VND</p>
        </div>
    </div> --}}

    <script>
        function printInvoice() {
            // Lấy ID của đặt vé từ biến hoặc DOM
            const datVeId = "{{ $datVe->id }}"; // Giả sử $datVe->id có sẵn trong Blade
            const url = "{{ route('admin.dat-ve.print', ':id') }}".replace(':id', datVeId);

            // Gửi yêu cầu AJAX để lấy PDF
            fetch(url, {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/pdf'
                    }
                })
                .then(response => response.blob())
                .then(blob => {
                    // Tạo URL tạm thời cho PDF
                    const pdfUrl = window.URL.createObjectURL(blob);
                    // Mở PDF trong tab mới hoặc iframe
                    window.open(pdfUrl, '_blank');
                    // Giải phóng URL sau khi sử dụng
                    setTimeout(() => window.URL.revokeObjectURL(pdfUrl), 100);
                })
                .catch(error => {
                    console.error('Lỗi khi tải PDF:', error);
                    alert('Không thể tải hóa đơn. Vui lòng thử lại.');
                });
        }
    </script>

@endsection
