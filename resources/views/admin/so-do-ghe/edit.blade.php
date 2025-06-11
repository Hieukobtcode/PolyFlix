@extends('layouts.admin')
@section('title', 'Phòng chiếu')
@section('page-title', 'Chỉnh sửa sơ đồ ghế')
@section('breadcrumb', 'Chỉnh sửa sơ đồ ghế')

@section('content')
    @php
        $cauTrucGhes = $soDoGhe->cau_truc_ghe;
        $rows = [];
        foreach ($cauTrucGhes as $seat => $type) {
            $row = substr($seat, 0, 1);
            $col = substr($seat, 1);
            $rows[$row][$col] = $type;
        }
        ksort($rows);
        foreach ($rows as &$cols) {
            ksort($cols);
        }
        unset($cols);
    @endphp

    <style>
        .layout {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-top: 10px;
            flex-wrap: wrap;
        }

        .khung {
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

        .seat-map-grid {
            padding: 10px;
            display: grid;
            grid-template-columns: repeat({{ count(reset($rows)) + 2 }}, 36px);
            gap: 2px;
            justify-content: center;
            align-items: center;
        }

        .seat-label,
        .seat-row-label {
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: #34495e;
            font-size: 12px;
            letter-spacing: 1px;
        }

        .seat {
            width: 35px;
            height: 35px;
            border-radius: 6px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: 500;
            font-size: 12px;
            cursor: pointer;
            border: 2px solid #fff;
            transition: transform 0.15s;
        }

        .seat:hover {
            transform: scale(1.08);
            border-color: #6366f1;
        }

        .seat.empty i.fa-plus {
            color: #B197FC;
        }

        .seat.filled i.fa-couch {
            color: #34495e;
        }

        .seat.vip {
            background: #f3f4f6;
            color: #fff;
        }

        .seat.thuong {
            background: #fef3c7;
            color: #fff;
        }

        .seat.doi {
            background: #fce7f3;
            color: #fff;
        }

        .seat-action-btns {
            display: flex;
            flex-direction: column;
            gap: 4px;
            align-items: center;
            justify-content: center;
            margin-top: 2px;
        }

        .seat-action-btn {
            width: 32px;
            height: 32px;
            border: none;
            border-radius: 6px;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.15s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .seat-action-btn.add {
            background: #22c55e;
        }

        .seat-action-btn.add:hover {
            background: #16a34a;
        }

        .seat-action-btn.delete {
            background-color: #ef4444;
        }

        .seat-action-btn.delete:hover {
            background-color: #b91c1c;
        }

        .right-panel {
            width: 20%;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .panel-box {
            box-shadow:
                rgba(14, 63, 126, 0.06) 0px 0px 0px 1px,
                rgba(42, 51, 70, 0.03) 0px 1px 1px -0.5px,
                rgba(42, 51, 70, 0.04) 0px 2px 2px -1px,
                rgba(42, 51, 70, 0.04) 0px 3px 3px -1.5px,
                rgba(42, 51, 70, 0.03) 0px 5px 5px -2.5px,
                rgba(42, 51, 70, 0.03) 0px 10px 10px -5px,
                rgba(42, 51, 70, 0.03) 0px 24px 24px -8px;
            border-radius: 12px;
            padding: 20px;
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

        .btn {
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            border: none;
            cursor: pointer;
        }

        .btn-save {
            background-color: #e5e7eb;
            color: #111827;
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
    </style>
    <form action="{{ route('admin.ghe-ngoi.store') }}" method="POST">
        @csrf
        <input type="hidden" name="soDoGheId" value="{{ $soDoGhe->id }}">
        <div class="layout">
            <div class="khung">
                <input type="hidden" name="seat_data" id="seat_data">
                <input type="hidden" value="{{ $phongChieu->id }}" name="phong_chieu_id">
                <div class="seat-map-grid">
                    <div></div>
                    @foreach (array_keys(reset($rows)) as $col)
                        <div class="seat-label">{{ $col }}</div>
                    @endforeach
                    <div></div>

                    @foreach ($rows as $rowKey => $cols)
                        <div class="seat-row-label">{{ $rowKey }}</div>

                        @foreach ($cols as $colKey => $type)
                            @php
                                $statusClass = $type !== 'empty' ? 'empty' : 'filled';
                                $typeClass = match ($type) {
                                    'vip' => 'vip',
                                    'thuong' => 'thuong',
                                    'doi' => 'doi',
                                    default => '',
                                };
                            @endphp

                            <button type="button" class="seat {{ $typeClass }} {{ $statusClass }}" empty
                                data-row="{{ $rowKey }}" data-col="{{ $colKey }}"
                                data-loai="{{ $typeClass }}"
                                onclick="toggleSeat('{{ $rowKey }}','{{ $colKey }}')">
                                @if ($statusClass === 'empty')
                                    <i class="fa-solid fa-plus fa-spin-pulse"></i>
                                @else
                                    <i class="fa-solid fa-couch"></i>
                                @endif
                            </button>
                        @endforeach

                        <div class="seat-action-btns" data-row="{{ $rowKey }}">
                            <button type="button" title="Thêm hàng {{ $rowKey }}" class="seat-action-btn add"
                                data-type="add-button" data-row="{{ $rowKey }}"
                                onclick="toggleRow('{{ $rowKey }}')">
                                <i class="fa-solid fa-plus fa-bounce"></i>
                            </button>
                            <button type="button" title="Xóa hàng {{ $rowKey }}" class="seat-action-btn delete"
                                data-type="delete-button" data-row="{{ $rowKey }}"
                                onclick="deleteRow('{{ $rowKey }}')" style="display: none;">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="right-panel">
                <div class="panel-box">
                    <h4>Cập nhật</h4>
                    <p><strong>Trạng thái:</strong>
                        {{ $soDoGhe->trang_thai == 1 ? 'Chưa hoạt động' : 'Hoạt động' }}
                    </p>
                    <div class="btn-group">
                        <button type="submit" class="btn-publish">Cập nhật</button>
                    </div>
                </div>

                <div class="panel-box">
                    <h4>Chú thích</h4>
                    <div class="legend-item">
                        <span>Hàng ghế thường</span>
                        <div class="legend-color legend-thuong"></div>
                    </div>
                    <div class="legend-item">
                        <span>Hàng ghế vip</span>
                        <div class="legend-color legend-vip"></div>
                    </div>
                    <div class="legend-item">
                        <span>Hàng ghế đôi</span>
                        <div class="legend-color legend-doi"></div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <script>
        function toggleSeat(row, col) {
            const seatSelector = `[data-row="${row}"][data-col="${col}"]`;
            const seat = document.querySelector(seatSelector);
            if (!seat) return;

            if (seat.classList.contains('doi')) {
                const colNum = Number(col);
                const partnerCol = (colNum % 2 === 1) ?
                    String(colNum + 1) :
                    String(colNum - 1);
                const partnerSelector = `[data-row="${row}"][data-col="${partnerCol}"]`;
                const partnerSeat = document.querySelector(partnerSelector);

                const toggleOne = s => {
                    if (s.classList.contains('empty')) {
                        s.classList.replace('empty', 'filled');
                        s.innerHTML = '<i class="fa-solid fa-couch"></i>';
                    } else {
                        s.classList.replace('filled', 'empty');
                        s.innerHTML = '<i class="fa-solid fa-plus fa-spin-pulse"></i>';
                    }
                };

                toggleOne(seat);
                if (partnerSeat) {
                    toggleOne(partnerSeat);
                }
            } else {
                if (seat.classList.contains('empty')) {
                    seat.classList.replace('empty', 'filled');
                    seat.innerHTML = '<i class="fa-solid fa-couch"></i>';
                } else {
                    seat.classList.replace('filled', 'empty');
                    seat.innerHTML = '<i class="fa-solid fa-plus fa-spin-pulse"></i>';
                }
            }

            updateRowButtons(row);
        }

        function toggleRow(row) {
            const rowSeats = document.querySelectorAll(`.seat[data-row="${row}"]`);
            rowSeats.forEach(seat => {
                if (seat.classList.contains('empty')) {
                    if (seat.classList.contains('doi')) {
                        const colNum = Number(seat.dataset.col);
                        const partnerCol = (colNum % 2 === 1) ?
                            String(colNum + 1) :
                            String(colNum - 1);
                        const partnerSelector = `[data-row="${row}"][data-col="${partnerCol}"]`;
                        const partnerSeat = document.querySelector(partnerSelector);

                        seat.classList.replace('empty', 'filled');
                        seat.innerHTML = '<i class="fa-solid fa-couch"></i>';
                        if (partnerSeat && partnerSeat.classList.contains('empty')) {
                            partnerSeat.classList.replace('empty', 'filled');
                            partnerSeat.innerHTML = '<i class="fa-solid fa-couch"></i>';
                        }
                    } else {
                        seat.classList.replace('empty', 'filled');
                        seat.innerHTML = '<i class="fa-solid fa-couch"></i>';
                    }
                }
            });

            const btnGroup = document.querySelector(`.seat-action-btns[data-row="${row}"]`);
            if (!btnGroup) return;
            const addBtn = btnGroup.querySelector('.seat-action-btn.add');
            const deleteBtn = btnGroup.querySelector('.seat-action-btn.delete');
            if (addBtn && deleteBtn) {
                addBtn.style.display = 'none';
                deleteBtn.style.display = 'flex';
            }

        }

        function deleteRow(row) {
            const rowSeats = document.querySelectorAll(`.seat[data-row="${row}"]`);
            rowSeats.forEach(seat => {
                if (seat.classList.contains('filled')) {
                    if (seat.classList.contains('doi')) {
                        const colNum = Number(seat.dataset.col);
                        const partnerCol = (colNum % 2 === 1) ?
                            String(colNum + 1) :
                            String(colNum - 1);
                        const partnerSelector = `[data-row="${row}"][data-col="${partnerCol}"]`;
                        const partnerSeat = document.querySelector(partnerSelector);

                        seat.classList.replace('filled', 'empty');
                        seat.innerHTML = '<i class="fa-solid fa-plus fa-spin-pulse"></i>';
                        if (partnerSeat && partnerSeat.classList.contains('filled')) {
                            partnerSeat.classList.replace('filled', 'empty');
                            partnerSeat.innerHTML = '<i class="fa-solid fa-plus fa-spin-pulse"></i>';
                        }
                    } else {
                        seat.classList.replace('filled', 'empty');
                        seat.innerHTML = '<i class="fa-solid fa-plus fa-spin-pulse"></i>';
                    }
                }
            });

            const btnGroup = document.querySelector(`.seat-action-btns[data-row="${row}"]`);
            if (!btnGroup) return;
            const addBtn = btnGroup.querySelector('.seat-action-btn.add');
            const deleteBtn = btnGroup.querySelector('.seat-action-btn.delete');
            if (addBtn && deleteBtn) {
                addBtn.style.display = 'flex';
                deleteBtn.style.display = 'none';
            }

        }

        function updateRowButtons(row) {
            const rowSeats = document.querySelectorAll(`.seat[data-row="${row}"]`);
            let hasFilled = false;
            rowSeats.forEach(seat => {
                if (seat.classList.contains('filled')) {
                    hasFilled = true;
                }
            });

            const btnGroup = document.querySelector(`.seat-action-btns[data-row="${row}"]`);
            if (!btnGroup) return;
            const addBtn = btnGroup.querySelector('.seat-action-btn.add');
            const deleteBtn = btnGroup.querySelector('.seat-action-btn.delete');
            if (hasFilled) {
                if (addBtn) addBtn.style.display = 'none';
                if (deleteBtn) deleteBtn.style.display = 'flex';
            } else {
                if (addBtn) addBtn.style.display = 'flex';
                if (deleteBtn) deleteBtn.style.display = 'none';
            }
        }

        document.querySelector('form').addEventListener('submit', function(e) {
            const seats = [];
            document.querySelectorAll('.seat').forEach(seat => {
                const row = seat.dataset.row;
                const col = seat.dataset.col;
                let loai = 'empty';
                if (seat.classList.contains('filled')) {
                    if (seat.classList.contains('thuong')) loai = 'thuong';
                    else if (seat.classList.contains('vip')) loai = 'vip';
                    else if (seat.classList.contains('doi')) loai = 'doi';
                }
                seats.push({
                    row,
                    col,
                    loai
                });
            });
            document.getElementById('seat_data').value = JSON.stringify(seats);
        });
    </script>
@endsection
