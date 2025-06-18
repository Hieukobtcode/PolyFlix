<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Vé xem phim - {{ $datVe->ma_dat_ve }}</title>
</head>

<body style=" margin:0; padding:0; background:#f4f4f4; font-family:Arial,sans-serif;">
    <table role="presentation"  border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td align="center" style="padding:40px 0;">
                <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="550"
                    style="background: linear-gradient(to bottom right, #fff0f0, #ffffff); border-radius:24px; overflow:hidden; box-shadow:0 6px 24px rgba(0,0,0,0.13); border:2px dashed #e0e0e0; position:relative;">
                    <!-- Decorative circles -->
                    <tr>
                        <td style="height:0; position:relative; padding:0;">
                            <div
                                style="position:absolute; left:-14px; top:50%; transform:translateY(-50%); width:28px; height:28px; background:#f4f4f4; border-radius:50%; border:2px solid #e0e0e0; z-index:2;">
                            </div>
                            <div
                                style="position:absolute; right:-14px; top:50%; transform:translateY(-50%); width:28px; height:28px; background:#f4f4f4; border-radius:50%; border:2px solid #e0e0e0; z-index:2;">
                            </div>
                        </td>
                    </tr>

                    <!-- Barcode -->
                    <tr>
                        <td style="padding: 0 32px 24px 32px; text-align: center;">
                            @if (!empty($barcodeCid))
                                <img src="cid:{{ $barcodeCid }}" alt="Mã vạch"
                                    style="max-width: 180px; display: block; margin: 0 auto 8px;">
                            @else
                                <p style="color: red;">Không thể hiển thị mã vạch.</p>
                            @endif

                            <div style="margin: 12px 0 6px; font-size: 13px; font-weight: bold; color: #888;">
                                {{ $datVe->ma_dat_ve }}
                            </div>

                            <div style="font-size: 13px; color: #888;">
                                Đưa mã này đến quầy vé để nhận vé của bạn
                            </div>
                        </td>
                    </tr>

                    <!-- Nội dung vé -->
                    <tr>
                        <td style="padding: 0 40px 20px; font-size: 15px; color: #333;">
                            <table width="100%" cellpadding="6" style="font-size:15px; color:#333;">
                                <tr>
                                    <td align="left"><strong>Phim</strong></td>
                                    <td align="right">{{ $datVe->suatChieu?->phim?->ten_phim ?? 'Không rõ' }}</td>
                                </tr>
                                <tr>
                                    <td align="left"><strong>Rạp</strong></td>
                                    <td align="right">Rạp chiếu</td>
                                </tr>
                                <tr>
                                    <td align="left"><strong>Phòng</strong></td>
                                    <td align="right">Phòng chiếu</td>
                                </tr>
                                <tr>
                                    <td align="left"><strong>Suất chiếu</strong></td>
                                    <td align="right">Ngày chiếu giờ bắt đầu - kết thúc</td>
                                </tr>
                                <tr>
                                    <td align="left"><strong>Ghế</strong></td>
                                    <td align="right">Ghế ở đây</td>
                                </tr>
                            </table>

                            <hr style="border: none; border-top: 2px dashed #ccc; margin: 30px 0;">

                            @if ($datVe->combos->count())
                                <h4 style="margin-top: 30px;">Combo đi kèm:</h4>
                                @foreach ($datVe->combos as $combo)
                                    <div style="margin-bottom: 10px;">
                                        <strong>{{ $combo->tieu_de }}</strong>
                                        @if ($combo->doAns->count())
                                            <ul style="margin: 5px 0 10px 20px; padding: 0;">
                                                @foreach ($combo->doAns as $doAn)
                                                    <li>{{ $doAn->tieu_de }} × {{ $doAn->pivot->so_luong }}</li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <p style="font-style: italic; color: #777;">Không có món ăn trong combo này.
                                            </p>
                                        @endif
                                    </div>
                                @endforeach
                            @else
                                <p><strong>Combo:</strong> Không có combo kèm theo.</p>
                            @endif

                        </td>
                    </tr>

                    <tr>
                        <td
                            style="padding:20px 32px 28px 32px; text-align:center; font-size:14px; color:#888; border-top:1px dashed #e0e0e0;">
                            <div style="margin-bottom:4px;">Cảm ơn bạn đã đặt vé tại <strong
                                    style="color:#2b6cb0;">PolyFlix</strong></div>
                            <div>Chúc bạn có trải nghiệm xem phim tuyệt vời!</div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
