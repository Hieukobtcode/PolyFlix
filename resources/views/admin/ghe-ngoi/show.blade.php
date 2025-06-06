@extends('layouts.admin')
@section('title', 'Phòng chiếu')
@section('page-title', 'Chỉnh sửa sơ đồ ghế')
@php
    $tenPhong = $phongChieu->ten_phong;
    // Tính tổng số ghế
    $totalSeats = 0;
    foreach ($gheGroupedArray as $seatsInRow) {
        foreach ($seatsInRow as $oneSeat) {
            if ($oneSeat['loai_ghe'] !== 'empty') {
                $totalSeats++;
            }
        }
    }
@endphp
@php
    $breadcrumb = 'Chi tiết sơ đồ ghế - Phòng chiếu ' . $tenPhong;
@endphp

@section('breadcrumb', $breadcrumb)
@section('content')
    <div class="container mx-auto p-6">
        <style>
            .two-column {
                display: flex;
                justify-content: center;
                gap: 40px;
                margin-top: 10px;
                flex-wrap: wrap;
            }

            .col-left {
                width: 70%;
                max-width: 100%;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                padding: 30px;
                box-shadow:
                    rgba(14, 63, 126, 0.06) 0px 0px 0px 1px,
                    rgba(42, 51, 70, 0.03) 0px 1px 1px -0.5px,
                    rgba(42, 51, 70, 0.04) 0px 2px 2px -1px,
                    rgba(42, 51, 70, 0.04) 0px 3px 3px -1.5px,
                    rgba(42, 51, 70, 0.03) 0px 5px 5px -2.5px,
                    rgba(42, 51, 70, 0.03) 0px 10px 10px -5px,
                    rgba(42, 51, 70, 0.03) 0px 24px 24px -8px;
                margin-left: 40px;
                border-radius: 8px;
            }

            .col-right {
                width: 20%;
                flex-shrink: 0;
                display: flex;
                flex-direction: column;
                gap: 24px;
            }

            .seat-map {
                display: flex;
                flex-direction: column;
                gap: 2px;
                max-width: 100%;
            }

            .seat-row {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 2px;
            }

            .row-label {
                width: 24px;
                font-weight: 600;
                font-size: 14px;
                text-align: center;
            }

            .seat-wrapper {
                position: relative;
                width: 40px;
                height: 40px;
                background-color: #f3f4f6;
                border-radius: 6px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                transition: background-color 0.2s;
            }

            .seat-wrapper i.fa-couch {
                position: absolute;
                font-size: 20px;
                color: #34495e;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
            }

            .seat-wrapper .seat-code {
                position: absolute;
                bottom: 2px;
                right: 2px;
                font-size: 10px;
                font-weight: 600;
                color: #34495e;
                background-color: rgba(255, 255, 255, 0.7);
                padding: 2px 3px;
                border-radius: 3px;
                pointer-events: none;
            }

            .seat-wrapper.thuong {
                background-color: #fef3c7;
            }

            .seat-wrapper.vip {
                background-color: #f3f4f6;
            }

            .seat-wrapper.doi {
                background-color: #fce7f3;
            }

            .seat-wrapper.empty {
                background-color: #ffffff;
                border: 1px solid #d1d5db;
                cursor: default;
                /* không cho click nếu muốn */
            }

            /* Khi ghế được đánh dấu (selected) */
            .seat-wrapper.selected {
                background-color: #d1d5db;
                /* đổi màu nền hoặc giữ nguyên tuỳ ý */
            }

            /* Dấu gạch chéo (strike-through) hoặc dấu tích: */
            .seat-wrapper.selected::after {
                content: '';
                position: absolute;
                width: 100%;
                height: 2px;
                background-color: #e53e3e;
                /* màu gạch chéo, bạn có thể thay */
                top: 50%;
                left: 0;
                transform: rotate(-45deg);
                pointer-events: none;
            }

            .right-panel {
                display: flex;
                flex-direction: column;
                gap: 24px;
            }

            .panel-box {
                background-color: #ffffff;
                border-radius: 8px;
                padding: 20px;
                box-shadow:
                    rgba(14, 63, 126, 0.06) 0px 0px 0px 1px,
                    rgba(42, 51, 70, 0.03) 0px 1px 1px -0.5px,
                    rgba(42, 51, 70, 0.04) 0px 2px 2px -1px,
                    rgba(42, 51, 70, 0.04) 0px 3px 3px -1.5px,
                    rgba(42, 51, 70, 0.03) 0px 5px 5px -2.5px,
                    rgba(42, 51, 70, 0.03) 0px 10px 10px -5px,
                    rgba(42, 51, 70, 0.03) 0px 24px 24px -8px;
            }

            .panel-box h4 {
                font-size: 18px;
                font-weight: 600;
                margin-bottom: 12px;
                color: #1f2937;
            }

            .panel-box p {
                font-size: 14px;
                margin: 4px 0;
                color: #4b5563;
            }

            .legend-item {
                display: flex;
                justify-content: space-between;
                margin-top: 10px;
                font-size: 14px;
                color: #374151;
            }

            .legend-color {
                width: 24px;
                height: 24px;
                border-radius: 4px;
                border: 1px solid #ccc;
            }

            .legend-thuong {
                background-color: #fef3c7;
            }

            .legend-vip {
                background-color: #f3f4f6;
            }

            .legend-doi {
                background-color: #fce7f3;
            }

            .btn-group {
                display: flex;
                gap: 12px;
                margin-top: 12px;
            }

            .btn-publish {
                padding: 8px 16px;
                border-radius: 8px;
                font-size: 14px;
                font-weight: 600;
                background-color: #1e3a8a;
                color: #fff;
                border: none;
                cursor: pointer;
            }

            .screen {
                width: 400px;
                max-width: 600px;
                height: 32px;
                background-color: #d1d5db;
                border-radius: 8px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-weight: 600;
                color: #4b5563;
                margin-bottom: 50px;
                margin-left: auto;
                margin-right: auto;
            }
        </style>
        <form id="updateSeatForm" action="{{ route('admin.ghe-ngoi.updateSeat') }}" method="POST">
            @csrf
            <div class="two-column">
                <div class="col-left">
                    <div class="seat-map">
                        <div class="screen">Màn Hình Chiếu</div>
                        <input type="hidden" name="phongChieuId" id="phongChieuId" value="{{ $phongChieuId }}">
                        <input type="hidden" name="seats_json" id="hiddenSeatsJson">
                        @foreach ($gheGroupedArray as $hangIndex => $seatsInRow)
                            <div class="seat-row">
                                <span class="row-label">{{ $hangIndex }}</span>
                                @foreach ($seatsInRow as $oneSeat)
                                    @php
                                        $maGhe = $oneSeat['ma_ghe'];
                                        $loaiGhe = $oneSeat['loai_ghe'];
                                        $classLoai = match ($loaiGhe) {
                                            'thuong' => 'thuong',
                                            'vip' => 'vip',
                                            'doi' => 'doi',
                                            default => 'empty',
                                        };
                                    @endphp

                                    @if ($classLoai !== 'empty')
                                        <div data-id="{{ $oneSeat['id'] }}"
                                            class="seat-wrapper {{ $classLoai }} {{ $oneSeat['trang_thai'] === 'bao_tri' ? 'selected' : '' }}"
                                            data-seat="{{ $maGhe }}">
                                            <i class="fa-solid fa-couch"></i>
                                            <span class="seat-code">{{ $maGhe }}</span>
                                        </div>  
                                    @else
                                        <div class="seat-wrapper empty"></div>
                                    @endif
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="col-right">
                    <div class="right-panel">
                        <div class="panel-box">
                            <h4>Cập nhật</h4>
                            <p><strong>Hoạt động:</strong> {{ $soDoGhe->trang_thai == 1 ? 'Chưa hoạt động' : 'Hoạt động' }}
                            </p>
                            <div class="btn-group">
                                <button type="submit" id="btn_update" class="btn-publish">Cập nhật</button>
                            </div>
                        </div>

                        <div class="panel-box">
                            <h4>Chú thích</h4>
                            <div class="legend-item">
                                <span>Ghế thường</span>
                                <div class="legend-color legend-thuong"></div>
                            </div>
                            <div class="legend-item">
                                <span>Ghế VIP</span>
                                <div class="legend-color legend-vip"></div>
                            </div>
                            <div class="legend-item">
                                <span>Ghế đôi</span>
                                <div class="legend-color legend-doi"></div>
                            </div>
                            <div class="legend-item">
                                <span>Tổng số ghế</span>
                                <span>{{ $totalSeats }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const seats = document.querySelectorAll('.seat-wrapper:not(.empty)');
                seats.forEach(seat => {
                    seat.addEventListener('click', function() {
                        this.classList.toggle('selected');
                    });
                });
            });

            document.getElementById('updateSeatForm')
                .addEventListener('submit', function(e) {
                    console.log('Bắt được sự kiện submit, chuẩn bị thu thập ghế…');

                    const selectedSeats = document.querySelectorAll('.seat-wrapper.selected');
                    console.log('selectedSeats NodeList:', selectedSeats);

                    const seats = Array.from(selectedSeats).map(el => el.getAttribute('data-id'));

                    document.getElementById('hiddenSeatsJson').value = JSON.stringify(seats);
                    console.log('Giá trị hiddenSeatsJson được gán:', document.getElementById('hiddenSeatsJson').value);

                });
        </script>
    </div>
@endsection
