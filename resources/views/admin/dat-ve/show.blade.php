@extends('layouts.admin')

@section('title', 'Chi tiết Đặt vé')
@section('page-title', 'Chi tiết Đặt vé')
@section('breadcrumb', 'Chi tiết Đặt vé')

@section('content')
    <style>
        .cinema-ticket {
            width: 900px;
            /* tăng từ 650px lên 900px */
            margin: 40px auto;
            display: flex;
            border-radius: 16px;
            font-family: 'Segoe UI', sans-serif;
            background: #fff;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            overflow: hidden;
        }

        .ticket-left {
            flex: 1;
            padding: 24px;
            background: linear-gradient(to bottom right, #ffe6e6, #ffffff);
            border-right: 2px dashed #ff6666;
        }

        .ticket-left h2 {
            font-size: 22px;
            color: #d60000;
            margin-bottom: 20px;
        }

        .ticket-left .info div {
            font-size: 15px;
            color: #333;
            margin-bottom: 6px;
        }

        .ticket-left .info h5 {
            margin-top: 20px;
            font-size: 16px;
            color: #aa0000;
        }

        .ticket-left ul {
            margin: 0;
            padding-left: 18px;
        }

        .ticket-left li {
            font-size: 14px;
            margin-bottom: 4px;
            color: #444;
        }

        .ticket-left .code {
            margin-top: 24px;
            font-weight: bold;
            font-size: 16px;
            color: #d60000;
            border-top: 1px dashed #ccc;
            padding-top: 12px;
        }

        .ticket-right {
            width: 180px;
            background-color: #fefefe;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 24px;
        }

        .barcode {
            text-align: center;
        }

        .barcode div {
            margin-top: 10px;
            font-size: 13px;
            letter-spacing: 1.5px;
            color: #666;
        }

        .no-combo {
            font-style: italic;
            font-size: 14px;
            color: #888;
            margin-top: 6px;
        }
    </style>

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

                    <div class="row">
                        @foreach ($datVe->combos as $combo)
                            <div class="col-md-12 mb-3">
                                <div class="card shadow-sm border-0">
                                    <div class="card-body bg-light">
                                        <h6 class="card-title text-danger fw-bold">
                                            <i class="fas fa-utensils me-1"></i> {{ $combo->tieu_de }}
                                        </h6>

                                        @if ($combo->doAns->count())
                                            <ul class="list-group list-group-flush mt-2">
                                                @foreach ($combo->doAns as $doAn)
                                                    <li
                                                        class="list-group-item d-flex justify-content-between align-items-center">
                                                        <span>
                                                            <i class="fas fa-hamburger text-warning me-2"></i>
                                                            {{ $doAn->tieu_de }}
                                                        </span>
                                                        <span class="badge bg-primary rounded-pill">
                                                            × {{ $doAn->pivot->so_luong }}
                                                        </span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <p class="text-muted fst-italic mt-2 ms-1">Không có món ăn trong combo này.</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-secondary mt-3">
                        <i class="fas fa-info-circle me-2"></i> Không có combo kèm theo.
                    </div>
                @endif

            </div>

            <div class="code">🧾 Mã vé: {{ $datVe->ma_dat_ve }}</div>
        </div>

        <div class="ticket-right">
            <div class="barcode">
                {!! DNS1D::getBarcodeHTML($datVe->ma_dat_ve, 'C128', 1.2, 60) !!}
                <div>{{ $datVe->ma_dat_ve }}</div>
            </div>
        </div>
    </div>
@endsection
