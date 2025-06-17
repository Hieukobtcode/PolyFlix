@extends('layouts.admin')

@section('title', 'Chi tiết Đặt vé')
@section('page-title', 'Chi tiết Đặt vé')
@section('breadcrumb', 'Chi tiết Đặt vé')

@section('content')
    <style>
        .ticket-left table {
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
            font-size: 14px;
            margin-top: 10px;
        }

        .ticket-left table th,
        .ticket-left table td {
            padding: 12px;
            vertical-align: top;
            border-bottom: 2px dashed #ffb7b7;
            text-align: center;
            word-wrap: break-word;
        }

        .ticket-left table th:nth-child(1),
        .ticket-left table td:nth-child(1) {
            width: 25%;
        }

        .ticket-left table th:nth-child(2),
        .ticket-left table td:nth-child(2) {
            width: 30%;
        }

        .ticket-left table th:nth-child(3),
        .ticket-left table td:nth-child(3) {
            width: 25%;
        }

        .ticket-left table th:nth-child(4),
        .ticket-left table td:nth-child(4) {
            width: 20%;
        }

        .ticket-left table th {
            background-color: #ffe6e6;
            color: #cc0000;
            text-align: center;
            font-weight: 600;
        }

        .ticket-left table td img {
            width: 100px;
            height: 140px;
            border-radius: 8px;
            object-fit: cover;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        .ticket-left .combo-title {
            color: #d60000;
            font-weight: 600;
            margin-top: 5px;
        }

        .ticket-left .list-group-item {
            font-size: 13px;
            border: none;
            padding: 4px 0;
        }

        .ticket-left .badge {
            background-color: #cc0000 !important;
        }

        .ticket-left .summary td {
            border: none;
            padding: 6px 8px;
            font-size: 13px;
            color: #444;
        }

        .ticket-left .summary p {
            margin: 8px 0;
            text-align: left !important;
            padding: 0;
        }

        .ticket-left .summary .float-end {
            float: right;
        }

        .ticket-left .summary-total {
            font-weight: bold;
            color: #d60000;
        }

        .cinema-ticket {
            width: 95%;
            margin: 40px;
            display: flex;
            border-radius: 16px;
            font-family: 'Segoe UI', sans-serif;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            border: 2px dashed #e0e0e0;
        }

        .ticket-left {
            flex: 0.9;
            padding: 24px 32px;
            background: linear-gradient(to bottom right, #fff0f0, #ffffff);
            border-right: 2px dashed #ff9999;
            border-top-left-radius: 20px;
            border-bottom-left-radius: 20px;
            position: relative;
        }

        .ticket-left::before,
        .ticket-left::after {
            content: "";
            position: absolute;
            right: -12px;
            width: 24px;
            height: 24px;
            background: #f4f4f4;
            border-radius: 50%;
            z-index: 1;
        }

        .ticket-left::before {
            top: 0;
            transform: translateY(-50%);
        }

        .ticket-left::after {
            bottom: 0;
            transform: translateY(50%);
        }

        .ticket-left h2 {
            font-size: 24px;
            color: #cc0000;
            margin-bottom: 20px;
        }

        .ticket-left .info div {
            font-size: 15px;
            color: #333;
            margin-bottom: 8px;
        }

        .ticket-left h5 {
            font-size: 16px;
            color: #aa0000;
            margin-top: 24px;
        }

        .ticket-right {
            flex: 0.3;
            background-color: #fefefe;
            display: flex;
            flex-direction: column;
            /* align-items: center; */
            padding: 32px 24px;
            gap: 20px;
            border-top-right-radius: 20px;
            border-bottom-right-radius: 20px;
        }

        .info-user,
        .info-payment {
            text-align: center;
            border-top: 1px solid #ddd;
            padding-top: 16px;
            width: 100%;
        }

        .info-user img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            margin: 10px 0;
        }

        .info-user p,
        .info-payment p {
            font-size: 14px;
            margin: 4px 0;
            color: #555;
        }

        .info-user i,
        .info-payment i {
            margin-right: 6px;
            color: #666;
        }

        .no-combo {
            font-style: italic;
            font-size: 14px;
            color: #888;
            margin-top: 6px;
        }

        .combo-title {
            font-weight: bold;
            color: #d60000;
            margin-bottom: 10px;
        }

        .list-group-item i {
            color: #f39c12;
        }

        .barcode {}

        .barcode div {
            margin-top: 10px;
            font-size: 14px;
            letter-spacing: 1.5px;
            color: #666;
        }
    </style>
    <a href="{{ route('admin.dat_ve.gui_email', $datVe->id) }}" style="width:150px;" class="btn btn-sm btn-outline-primary">
        <i class="fas fa-envelope me-1"></i> Gửi vé về email
    </a>
    <div class="cinema-ticket">
        <div class="ticket-left">
            <table>
                <thead>
                    <tr>
                        <th>Phim</th>
                        <th>Suất chiếu</th>
                        <th>Ghế</th>
                        <th>Giá tiền</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <img src="{{ asset('storage/' . $datVe->suatChieu?->phim?->poster) }}" alt="img">
                            <p style="margin-top: 15px">{{ $datVe->suatChieu?->phim?->ten_phim }}</p>
                        </td>
                        <td>
                            <p><strong>Phòng:</strong> {{ $datVe->suatChieu?->phongChieu->ten_phong }}</p>
                            <p><strong>Ngày:</strong> {{ $datVe->suatChieu?->ngay_chieu }}</p>
                            <p><strong>Giờ:</strong> {{ $datVe->suatChieu?->bat_dau }} - {{ $datVe->suatChieu?->ket_thuc }}
                            </p>
                        </td>

                        <td>
                            <p>C11 (Ghế thường)</p>
                            <p>C12 (Ghế thường)</p>
                            <p>C13 (Ghế thường)</p>
                        </td>
                        <td>
                            <p>20.000 đ</p>
                            <p>20.000 đ</p>
                            <p>20.000 đ</p>
                        </td>
                    </tr>

                    <tr class="summary">
                        <td>
                            <p>Combo</p>
                            @if ($datVe->combos && $datVe->combos->count())
                                @foreach ($datVe->combos as $combo)
                                    <div class="combo-title">{{ $combo->tieu_de }}</div>
                                    @if ($combo->doAns && $combo->doAns->count())
                                        <ul class="list-group list-group-flush">
                                            @foreach ($combo->doAns as $doAn)
                                                <li
                                                    class="list-group-item d-flex justify-content-between align-items-center">
                                                    <span><i class="fas fa-utensils me-2"></i>{{ $doAn->tieu_de }}</span>
                                                    <span class="badge rounded-pill">× {{ $doAn->pivot->so_luong }}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p class="no-combo">Không có món ăn trong combo này.</p>
                                    @endif
                                @endforeach
                            @else
                                <div class="mt-2 py-1 px-2">
                                    <i class="fas fa-info-circle me-1"></i> Không có combo kèm theo.
                                </div>
                            @endif
                        </td>
                        <td colspan="3">
                            <table style="width: 100%;">
                                <tr>
                                    <td style="text-align: right; margin-left:90px;">Tiền vé:</td>
                                    <td style="text-align: right;">60.000 đ</td>
                                </tr>
                                <tr>
                                    <td style="text-align: right;">Tiền combo:</td>
                                    <td style="text-align: right;">0 đ</td>
                                </tr>
                                <tr>
                                    <td style="text-align: right;">Giảm giá:</td>
                                    <td style="text-align: right;">0 đ</td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <hr style="border-top: 3px dashed #000000; width: 70%; margin-left: auto;">
                                    </td>
                                </tr>
                                <tr style="font-weight: bold; color: #d60000;">
                                    <td style="text-align: right;">Thành tiền:</td>
                                    <td style="text-align: right;">150.000 đ</td>
                                </tr>
                            </table>
                        </td>
                    </tr>


                </tbody>
            </table>
        </div>

        <div class="ticket-right">
            <div class="barcode">
                <div class="trang-thai" style="display: flex">
                    <p style="font-size:18px">Trạng thái vé</p>
                    <button type="button" class="btn btn-primary">Chưa xuất</button>
                </div>
                <div class="code" style="margin-left:45px; text-align: center;">
                    {!! DNS1D::getBarcodeHTML($datVe->ma_dat_ve, 'C128', 2, 60) !!}
                </div>
                <div style="text-align: center;">{{ $datVe->ma_dat_ve }}</div>
            </div>

            <div class="info-user">
                <img src="https://upload.wikimedia.org/wikipedia/commons/9/99/Sample_User_Icon.png" alt="User Avatar">
                <p><i class="fa-solid fa-envelope"></i>{{ $datVe->nguoiDung?->email ?? 'Không có email' }}</p>
                <p><i class="fa-solid fa-phone"></i>{{ $datVe->nguoiDung?->so_dien_thoai ?? 'Không có SĐT' }}</p>
            </div>

            <div class="info-payment">
                <p>Phương thức: {{ ucfirst(str_replace('_', ' ', $datVe->phuong_thuc_tt)) }}</p>
                <p><i class="fa-solid fa-money-bill-wave"></i> {{ number_format($datVe->tong_tien, 0, ',', '.') }} đ</p>
            </div>
        </div>

    </div>
@endsection
