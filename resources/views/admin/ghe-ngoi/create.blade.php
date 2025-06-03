@extends('layouts.admin')

@section('title', 'Cập nhật sơ đồ ghế')
@section('page-title', 'Cập nhật sơ đồ ghế')
@section('breadcrumb', 'Cập nhật sơ đồ ghế')

@section('styles')
    <style>
        .seat-matrix {
            display: grid;
            grid-template-columns: repeat({{ $soCot }}, 40px);
            gap: 5px;
        }

        .seat-btn {
            width: 40px;
            height: 40px;
            border-radius: 6px;
            border: 1px solid #ccc;
            text-align: center;
            vertical-align: middle;
            cursor: pointer;
        }

        .seat-normal {
            background-color: #fef7e0;
        }

        .seat-vip {
            background-color: #f0f0f0;
        }

        .seat-couple {
            background-color: #fbd3e0;
        }

        .seat-empty {
            background-color: #ffffff;
            border: 1px dashed #aaa;
        }

        .legend {
            margin-top: 20px;
        }

        .legend span {
            display: inline-block;
            padding: 8px 12px;
            margin-right: 10px;
            border-radius: 5px;
            font-weight: bold;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="fw-bold mb-0">Thiết kế sơ đồ ghế</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.ghe-ngoi.store') }}" method="POST" id="seatForm">
                    @csrf
                    <input type="hidden" name="so_do_ghe_id" value="{{ $soDoGhe->id }}">
                    <div class="table-responsive">
                        <div class="seat-matrix">
                            @foreach(range(1, $soHang) as $i)
                                @foreach(range(1, $soCot) as $j)
                                    @php
                                        $maGhe = chr(64 + $i) . $j; // A1, B2, ...
                                    @endphp
                                    <button type="button" class="seat-btn seat-empty"
                                            data-hang="{{ chr(64 + $i) }}"
                                            data-cot="{{ $j }}"
                                            data-maghe="{{ $maGhe }}"
                                            onclick="toggleSeat(this)">
                                        +
                                    </button>
                                @endforeach
                            @endforeach
                        </div>
                    </div>

                    <input type="hidden" name="ghe_data" id="gheData">

                    <div class="mt-4 text-end">
                        <button type="submit" class="btn btn-success">Lưu sơ đồ</button>
                    </div>
                </form>

                <div class="legend">
                    <h6>Chú thích:</h6>
                    <span style="background-color:#fef7e0">Ghế thường</span>
                    <span style="background-color:#f0f0f0">Ghế VIP</span>
                    <span style="background-color:#fbd3e0">Ghế đôi</span>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    const selectedSeats = [];

    function toggleSeat(btn) {
        const hang = btn.dataset.hang;
        const cot = btn.dataset.cot;
        const maGhe = btn.dataset.maghe;

        // Xử lý toggle
        if (btn.classList.contains('seat-empty')) {
            btn.classList.remove('seat-empty');
            btn.classList.add('seat-normal');
            btn.innerText = '✓';

            selectedSeats.push({
                so_hang: hang,
                so_cot: cot,
                ma_ghe: maGhe,
                loai_ghe_id: 1 // default là thường, bạn có thể thêm lựa chọn loại ghế riêng
            });
        } else {
            btn.classList.remove('seat-normal', 'seat-vip', 'seat-couple');
            btn.classList.add('seat-empty');
            btn.innerText = '+';

            const index = selectedSeats.findIndex(s => s.ma_ghe === maGhe);
            if (index !== -1) selectedSeats.splice(index, 1);
        }

        document.getElementById('gheData').value = JSON.stringify(selectedSeats);
    }
</script>
@endsection
