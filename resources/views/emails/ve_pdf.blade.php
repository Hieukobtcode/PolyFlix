<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Vé xem phim - {{ $datVe->ma_dat_ve }}</title>

</head>

<body>
    <div class="cinema-ticket">
        <div class="ticket-left">
            <h2>🎟️ Vé Xem Phim</h2>
            <div class="info">
                <div><strong>🎬 Phim:</strong> {{ $datVe->suatChieu?->phim?->ten_phim ?? 'Không rõ' }}</div>
                <div><strong>👤 Người đặt:</strong> {{ $datVe->nguoiDung?->name ?? 'Không rõ' }}</div>
                <div><strong>📅 Ngày:</strong> {{ \Carbon\Carbon::parse($datVe->thoi_gian_dat)->format('d/m/Y') }}</div>
                <div><strong>🕒 Giờ:</strong> {{ \Carbon\Carbon::parse($datVe->thoi_gian_dat)->format('H:i') }}</div>
                <div><strong>💳 Thanh toán:</strong> {{ ucfirst(str_replace('_', ' ', $datVe->phuong_thuc_tt)) }}</div>

                @if ($datVe->combos->count())
                    <h5 class="mt-4">🍿 <strong>Combo đi kèm:</strong></h5>
                    <div>
                        @foreach ($datVe->combos as $combo)
                            <div style="margin-bottom: 10px;">
                                <div><strong>{{ $combo->tieu_de }}</strong></div>
                                @if ($combo->doAns->count())
                                    <ul>
                                        @foreach ($combo->doAns as $doAn)
                                            <li>{{ $doAn->tieu_de }} × {{ $doAn->pivot->so_luong }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-muted fst-italic mt-2 ms-1">Không có món ăn trong combo này.</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="no-combo">Không có combo kèm theo.</div>
                @endif
            </div>
        </div>

        <div class="ticket-right">
            <div class="barcode">
                @if (!empty($barcodeCid))
                    <img src="cid:{{ $barcodeCid }}" alt="Mã vạch" style="max-width: 300px;">
                    <div>{{ $datVe->ma_dat_ve }}</div>
                @else
                    <p style="color: red;">Không thể hiển thị mã vạch.</p>
                @endif
            </div>
        </div>
    </div>
</body>

</html>
