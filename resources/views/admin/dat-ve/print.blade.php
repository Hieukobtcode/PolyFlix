<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        @font-face {
            font-family: 'DejaVu Sans';
            src: url('{{ public_path('fonts/DejaVuSans.ttf') }}') format('truetype');
            font-weight: normal;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'DejaVu Sans', Arial, sans-serif;
        }

        table {
            border-collapse: collapse;
        }

        .ticket-container {
            position: relative;
            background-image:
                url('/logo/CinematicPolyFlixLogo-removebg-preview-removebg-preview.png'),
                url('/logo/CinematicPolyFlixLogo-removebg-preview-removebg-preview.png'),
                url('/logo/CinematicPolyFlixLogo-removebg-preview-removebg-preview.png'),
                url('/logo/CinematicPolyFlixLogo-removebg-preview-removebg-preview.png');
            background-repeat: no-repeat;
            background-size:
                80px auto,
                90px auto,
                70px auto,
                85px auto;
            background-position:
                15% 20%,
                85% 30%,
                20% 75%,
                80% 80%;
            background-color: #fff5f5;
            opacity: 0.95;
            background-color: #fff5f5;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 6px 24px rgba(0, 0, 0, 0.13);
            border: 3px dashed #e0e0e0;
            width: 700px;
            margin: 40px auto;
            page-break-inside: avoid;
        }

        .ticket-container-ve {
            page-break-after: always;
        }

        .barcode-section {
            padding: 0 32px 24px;
            text-align: center;
        }

        .barcode-img {
            max-width: 180px;
            display: block;
            margin: 0 auto 8px;
        }

        .ticket-code {
            margin: 12px 0 6px;
            font-size: 13px;
            font-weight: bold;
            color: #888;
        }

        .instruction {
            font-size: 13px;
            color: #888;
            margin-bottom: 15px;
        }

        .details-table {
            width: 100%;
            font-size: 15px;
            color: #333;
            padding: 0 40px 20px;
        }

        .details-table td {
            padding: 6px;
        }

        .footer {
            padding: 20px 32px 28px;
            text-align: center;
            font-size: 14px;
            color: #888;
            border-top: 1px dashed #e0e0e0;
        }

        .footer strong {
            color: #2b6cb0;
        }

        .divider {
            border-top: 1px dashed #9ca3af;
            margin: 1rem 0;
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

        .ticket-line.font-bold {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <!-- Vé xem phim: ghế thường mỗi ghế 1 vé; ghế đôi gộp 2 ghế vào 1 vé -->
    @php
        $gheThuong = [];
        $gheDoi = [];

        foreach ($datVe->gheNgois as $ghe) {
            $tenLoai = trim($ghe->loaiGhe->ten_loai_ghe ?? '');
            if (mb_strtolower($tenLoai) === mb_strtolower('Ghế đôi')) {
                $gheDoi[] = $ghe;
            } else {
                $gheThuong[] = $ghe;
            }
        }

        // Sắp xếp để hiển thị đẹp (vd: A5, A6)
        usort($gheDoi, fn($a, $b) => strcmp($a->ma_ghe, $b->ma_ghe));

        // Chia ghế đôi thành từng cặp 2 ghế
        $gheDoiChunks = array_chunk($gheDoi, 2);
    @endphp

    {{-- ===== In vé cho ghế thường (mỗi ghế 1 vé) ===== --}}
    @foreach ($gheThuong as $ghe)
        <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <td align="center" style="padding:40px 0;">
                    <table role="presentation" border="0" cellpadding="0" cellspacing="0"
                        class="ticket-container ticket-container-ve">
                        <tr>
                            <td height="30" style="line-height:30px; font-size:0;">&nbsp;</td>
                        </tr>
                        <!-- Barcode -->
                        <tr>
                            <td class="barcode-section">
                                @if (!empty($barcodeFileName))
                                    <img src="{{ public_path('temp/' . $barcodeFileName) }}" alt="Mã vạch"
                                        class="barcode-img">
                                @else
                                    <p style="color: red;">Không thể hiển thị mã vạch.</p>
                                @endif
                                <div class="ticket-code">{{ $datVe->ma_dat_ve }}</div>
                            </td>
                        </tr>
                        <!-- Nội dung vé -->
                        <tr>
                            <td class="details-table">
                                <table width="100%" cellpadding="6">
                                    <tr>
                                        <td align="left"><strong>Phim</strong></td>
                                        <td align="right">{{ $datVe->suatChieu?->phim?->ten_phim ?? 'Không rõ' }}</td>
                                    </tr>
                                    <tr>
                                        <td align="left"><strong>Rạp</strong></td>
                                        <td align="right">
                                            {{ $datVe->suatChieu?->phongChieu?->rapPhim?->ten_rap ?? 'Không rõ' }}</td>
                                    </tr>
                                    <tr>
                                        <td align="left"><strong>Chi nhánh</strong></td>
                                        <td align="right">
                                            {{ $datVe->suatChieu?->phongChieu?->rapPhim?->chiNhanh?->ten_chi_nhanh ?? 'Không rõ' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="left"><strong>Phòng</strong></td>
                                        <td align="right">
                                            {{ $datVe->suatChieu?->phongChieu?->ten_phong ?? 'Không rõ' }}</td>
                                    </tr>
                                    <tr>
                                        <td align="left"><strong>Suất chiếu</strong></td>
                                        <td align="right">
                                            Ngày:
                                            {{ \Carbon\Carbon::parse($datVe->suatChieu?->ngay_bat_dau)->format('d/m/Y') ?? '' }}<br>
                                            @if ($datVe->suatChieu?->bat_dau && $datVe->suatChieu?->ket_thuc)
                                                Giờ:
                                                {{ \Carbon\Carbon::parse($datVe->suatChieu->bat_dau)->format('H:i') }}
                                                -
                                                {{ \Carbon\Carbon::parse($datVe->suatChieu->ket_thuc)->format('H:i') }}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="left"><strong>Ghế</strong></td>
                                        <td align="right">{{ $ghe->ma_ghe }}</td>
                                    </tr>
                                    <tr>
                                        <td align="left"><strong>Loại ghế</strong></td>
                                        <td align="right">{{ $ghe->loaiGhe?->ten_loai_ghe ?? 'Không rõ' }}</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td class="footer" style="text-align:center; font-size:13px; line-height:1.6; color:#555;">
                                <div style="margin-bottom:6px;">
                                    Cảm ơn bạn đã đặt vé tại
                                    <strong>{{ $cauHinh->ten_thuong_hieu ?? 'PolyFlix' }}</strong>
                                </div>
                                <div style="margin-bottom:6px;">
                                    Hotline: {{ $cauHinh->so_dien_thoai ?? '---' }} •
                                    Email: {{ $cauHinh->email ?? '---' }}
                                </div>
                                <div style="margin-bottom:6px; font-size:12px; color:#d9534f;">
                                    * Vé điện tử – vui lòng không chia sẻ mã vé / QR cho người khác.
                                </div>
                                <div style="font-size:12px; margin-top:6px; color:#888;">
                                    &copy; {{ now()->year }} {{ $cauHinh->ban_quyen ?? 'PolyFlix' }}
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    @endforeach

    {{-- ===== In vé cho ghế đôi (mỗi CẶP ghế 1 vé) ===== --}}
    @foreach ($gheDoiChunks as $cap)
        @php
            // cap có thể 1 hoặc 2 phần tử (trường hợp lẻ vẫn in 1 vé)
            $dsGhe = collect($cap)->pluck('ma_ghe')->implode(', ');
            $tenLoai = $cap[0]->loaiGhe->ten_loai_ghe ?? 'Ghế đôi';
        @endphp
        <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <td align="center" style="padding:40px 0;">
                    <table role="presentation" border="0" cellpadding="0" cellspacing="0"
                        class="ticket-container ticket-container-ve">
                        <tr>
                            <td height="30" style="line-height:30px; font-size:0;">&nbsp;</td>
                        </tr>
                        <!-- Barcode -->
                        <tr>
                            <td class="barcode-section">
                                @if (!empty($barcodeFileName))
                                    <img src="{{ public_path('temp/' . $barcodeFileName) }}" alt="Mã vạch"
                                        class="barcode-img">
                                @else
                                    <p style="color: red;">Không thể hiển thị mã vạch.</p>
                                @endif
                                <div class="ticket-code">{{ $datVe->ma_dat_ve }}</div>
                            </td>
                        </tr>
                        <!-- Nội dung vé -->
                        <tr>
                            <td class="details-table">
                                <table width="100%" cellpadding="6">
                                    <tr>
                                        <td align="left"><strong>Phim</strong></td>
                                        <td align="right">{{ $datVe->suatChieu?->phim?->ten_phim ?? 'Không rõ' }}</td>
                                    </tr>
                                    <tr>
                                        <td align="left"><strong>Rạp</strong></td>
                                        <td align="right">
                                            {{ $datVe->suatChieu?->phongChieu?->rapPhim?->ten_rap ?? 'Không rõ' }}</td>
                                    </tr>
                                    <tr>
                                        <td align="left"><strong>Chi nhánh</strong></td>
                                        <td align="right">
                                            {{ $datVe->suatChieu?->phongChieu?->rapPhim?->chiNhanh?->ten_chi_nhanh ?? 'Không rõ' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="left"><strong>Phòng</strong></td>
                                        <td align="right">
                                            {{ $datVe->suatChieu?->phongChieu?->ten_phong ?? 'Không rõ' }}</td>
                                    </tr>
                                    <tr>
                                        <td align="left"><strong>Suất chiếu</strong></td>
                                        <td align="right">
                                            Ngày:
                                            {{ \Carbon\Carbon::parse($datVe->suatChieu?->ngay_bat_dau)->format('d/m/Y') ?? '' }}<br>
                                            @if ($datVe->suatChieu?->bat_dau && $datVe->suatChieu?->ket_thuc)
                                                Giờ:
                                                {{ \Carbon\Carbon::parse($datVe->suatChieu->bat_dau)->format('H:i') }}
                                                -
                                                {{ \Carbon\Carbon::parse($datVe->suatChieu->ket_thuc)->format('H:i') }}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="left"><strong>Ghế</strong></td>
                                        <td align="right">{{ $dsGhe }}</td>
                                    </tr>
                                    <tr>
                                        <td align="left"><strong>Loại ghế</strong></td>
                                        <td align="right">{{ $tenLoai }}</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td class="footer" style="text-align:center; font-size:13px; line-height:1.6; color:#555;">
                                <div style="margin-bottom:6px;">
                                    Cảm ơn bạn đã đặt vé tại
                                    <strong>{{ $cauHinh->ten_thuong_hieu ?? 'PolyFlix' }}</strong>
                                </div>
                                <div style="margin-bottom:6px;">
                                    Hotline: {{ $cauHinh->so_dien_thoai ?? '---' }} •
                                    Email: {{ $cauHinh->email ?? '---' }}
                                </div>
                                <div style="margin-bottom:6px; font-size:12px; color:#d9534f;">
                                    * Vé điện tử – vui lòng không chia sẻ mã vé / QR cho người khác.
                                </div>
                                <div style="font-size:12px; margin-top:6px; color:#888;">
                                    &copy; {{ now()->year }} {{ $cauHinh->ban_quyen ?? 'PolyFlix' }}
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    @endforeach

    <!-- Hóa đơn đồ ăn -->
    @if ($tongTienCombo > 0 || $tongTienDoAn > 0)
        <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <td align="center" style="padding:40px 0;">
                    <table role="presentation" border="0" cellpadding="0" cellspacing="0" class="ticket-container">
                        <tr>
                            <td height="30" style="line-height:30px; font-size:0;">&nbsp;</td>
                        </tr>
                        <tr>
                            <td class="barcode-section">
                                @if (!empty($barcodeFileName))
                                    <img src="{{ public_path('temp/' . $barcodeFileName) }}" alt="Mã vạch"
                                        class="barcode-img">
                                @else
                                    <p style="color: red;">Không thể hiển thị mã vạch.</p>
                                @endif
                                <div class="ticket-code">{{ $datVe->ma_dat_ve }}</div>
                            </td>
                        </tr>
                        <tr>
                            <td class="details-table">
                                <div class="ticket-title">HÓA ĐƠN ĐỒ ĂN</div>
                                <p class="ticket-line"><strong>Mã đặt vé:</strong> {{ $datVe->ma_dat_ve }}</p>
                                <p class="ticket-line"><strong>Khách hàng:</strong>
                                    {{ $datVe->nguoiDung?->email ?? 'Không có email' }}</p>
                                <div class="divider"></div>

                                @if ($tongTienCombo > 0)
                                    <p class="ticket-line font-bold">Combo</p>
                                    @foreach ($datVe->combos as $combo)
                                        <p class="ticket-line">{{ $combo->tieu_de }} (x{{ $combo->pivot->so_luong }})
                                            -
                                            {{ number_format($combo->gia * $combo->pivot->so_luong, 0, ',', '.') }} VND
                                        </p>
                                    @endforeach
                                @endif

                                @if ($tongTienDoAn > 0)
                                    <p class="ticket-line font-bold">Đồ ăn & nước uống</p>
                                    @foreach ($datVe->doAns as $doAn)
                                        <p class="ticket-line">{{ $doAn->tieu_de }} (x{{ $doAn->pivot->so_luong }}) -
                                            {{ number_format($doAn->gia * $doAn->pivot->so_luong, 0, ',', '.') }} VND
                                        </p>
                                    @endforeach
                                @endif

                                <div class="divider"></div>
                                <p class="ticket-line font-bold"><strong>Tổng tiền đồ ăn:</strong>
                                    {{ number_format($tongTienCombo + $tongTienDoAn, 0, ',', '.') }} VND</p>
                            </td>
                        </tr>
                        <tr>
                            <td class="footer"
                                style="text-align:center; font-size:13px; line-height:1.6; color:#555;">
                                <div style="margin-bottom:6px;">
                                    Cảm ơn bạn đã đặt vé tại
                                    <strong>{{ $cauHinh->ten_thuong_hieu ?? 'PolyFlix' }}</strong>
                                </div>
                                <div style="margin-bottom:6px;">
                                    Hotline: {{ $cauHinh->so_dien_thoai ?? '---' }} •
                                    Email: {{ $cauHinh->email ?? '---' }}
                                </div>
                                <div style="margin-bottom:6px; font-size:12px; color:#d9534f;">
                                    * Vé điện tử – vui lòng không chia sẻ mã vé / QR cho người khác.
                                </div>
                                <div style="font-size:12px; margin-top:6px; color:#888;">
                                    &copy; {{ now()->year }} {{ $cauHinh->ban_quyen ?? 'PolyFlix' }}
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    @endif

    <!-- Hóa đơn tổng -->
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td align="center" style="padding:40px 0;">
                <table role="presentation" border="0" cellpadding="0" cellspacing="0" class="ticket-container">
                    <tr>
                        <td height="30" style="line-height:30px; font-size:0;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td class="barcode-section">
                            @if (!empty($barcodeFileName))
                                <img src="{{ public_path('temp/' . $barcodeFileName) }}" alt="Mã vạch"
                                    class="barcode-img">
                            @else
                                <p style="color: red;">Không thể hiển thị mã vạch.</p>
                            @endif
                            <div class="ticket-code">{{ $datVe->ma_dat_ve }}</div>
                            <div class="instruction">Hóa đơn tổng</div>
                        </td>
                    </tr>
                    <tr>
                        <td class="details-table">
                            <p class="ticket-line"><strong>Khách hàng:</strong>
                                {{ $datVe->nguoiDung?->email ?? 'Không có email' }}</p>
                            <div class="divider"></div>
                            <p class="ticket-line">Phim: {{ $datVe->suatChieu?->phim?->ten_phim ?? '---' }}</p>
                            <p class="ticket-line">Thời gian:
                                {{ optional($datVe->suatChieu)->ngay_bat_dau ? \Carbon\Carbon::parse($datVe->suatChieu->ngay_bat_dau)->format('d/m/Y') : '---' }}
                                - {{ $datVe->suatChieu?->bat_dau ?? '---' }}
                            </p>
                            <p class="ticket-line">Phòng: {{ $datVe->suatChieu?->phongChieu?->ten_phong ?? '---' }}
                            </p>
                            <p class="ticket-line">Ghế:
                                {{ $datVe->gheNgois->pluck('ma_ghe')->join(', ') ?? 'Không có ghế' }}</p>
                            <p class="ticket-line">Tiền vé: {{ number_format($tongTienGhe, 0, ',', '.') }} VND</p>

                            @if ($tongTienCombo > 0)
                                <p class="ticket-line font-bold">Combo</p>
                                @foreach ($datVe->combos as $combo)
                                    <p class="ticket-line">{{ $combo->tieu_de }} (x{{ $combo->pivot->so_luong }}) -
                                        {{ number_format($combo->gia * $combo->pivot->so_luong, 0, ',', '.') }} VND</p>
                                @endforeach
                            @endif

                            @if ($tongTienDoAn > 0)
                                <p class="ticket-line font-bold">Đồ ăn & nước uống</p>
                                @foreach ($datVe->doAns as $doAn)
                                    <p class="ticket-line">{{ $doAn->tieu_de }} (x{{ $doAn->pivot->so_luong }}) -
                                        {{ number_format($doAn->gia * $doAn->pivot->so_luong, 0, ',', '.') }} VND</p>
                                @endforeach
                            @endif

                            <div class="divider"></div>
                            <p class="ticket-line font-bold"><strong>Tổng thanh toán:</strong>
                                {{ number_format($tongThanhTien, 0, ',', '.') }} VND</p>
                        </td>
                    </tr>
                    <tr>
                        <td class="footer" style="text-align:center; font-size:13px; line-height:1.6; color:#555;">
                            <div style="margin-bottom:6px;">
                                Cảm ơn bạn đã đặt vé tại <strong>{{ $cauHinh->ten_thuong_hieu ?? 'PolyFlix' }}</strong>
                            </div>
                            <div style="margin-bottom:6px;">
                                Hotline: {{ $cauHinh->so_dien_thoai ?? '---' }} •
                                Email: {{ $cauHinh->email ?? '---' }}
                            </div>
                            <div style="margin-bottom:6px; font-size:12px; color:#d9534f;">
                                * Vé điện tử – vui lòng không chia sẻ mã vé / QR cho người khác.
                            </div>
                            <div style="font-size:12px; margin-top:6px; color:#888;">
                                &copy; {{ now()->year }} {{ $cauHinh->ban_quyen ?? 'PolyFlix' }}
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
