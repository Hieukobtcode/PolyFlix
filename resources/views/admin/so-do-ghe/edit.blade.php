@extends('layouts.admin')
@section('content')

    @php
        $cauTrucGhes = $soDoGhe->cau_truc_ghe ?? [];
        $rows = [];
        $gheId = [];
        
        // Kiểm tra nếu cấu trúc ghế không rỗng và là mảng
        if (is_array($cauTrucGhes) && !empty($cauTrucGhes)) {
            foreach ($cauTrucGhes as $seat => $type) {
                $row = substr($seat, 0, 1);
                $col = substr($seat, 1);
                $rows[$row][$col] = $type;
                $gheId[] = $type;
            }
            ksort($rows);
            foreach ($rows as &$cols) {
                ksort($cols);
            }
            unset($cols);
        }
    @endphp

    <style>
        .layout {
            width: 100%;
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
            grid-template-columns: repeat({{ !empty($rows) && reset($rows) !== false ? count(reset($rows)) + 2 : 2 }}, 36px);
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
                    @if(!empty($rows))
                        <div></div>
                        @if(reset($rows) !== false)
                            @foreach (array_keys(reset($rows)) as $col)
                                <div class="seat-label">{{ $col }}</div>
                            @endforeach
                        @endif
                        <div></div>
                        
                        @foreach ($rows as $rowKey => $cols)
                            <div class="seat-row-label">{{ $rowKey }}</div>
                            @foreach ($cols as $colKey => $loaiGheId)
                                @php

                                    $mau = $mauGhes[$loaiGheId] ?? '#ccc';
                                    $typeClass = $loaiGheId;
                                    $statusClass = 'empty';
                                    $isDouble = $loaiGheId == 12 ? 'doi' : '';

                                @endphp

                            <button type="button" style="background-color: {{ $mau }}"
                                class="seat {{ $typeClass }} {{ $statusClass }} {{ $isDouble }}" empty
                                data-row="{{ $rowKey }}" data-col="{{ $colKey }}"
                                data-loai="{{ $typeClass }}"
                                onclick="toggleSeat('{{ $rowKey }}','{{ $colKey }}')">
                                @if ($statusClass === 'empty')
                                    <i class="ti ti-plus fs-5"></i>
                                @else
                                    <i class="ti ti-armchair fs-5"></i>
                                @endif
                            </button>

                        @endforeach

                        <div class="seat-action-btns" data-row="{{ $rowKey }}">
                            <button type="button" title="Thêm hàng {{ $rowKey }}" class="seat-action-btn add"
                                data-type="add-button" data-row="{{ $rowKey }}"
                                onclick="toggleRow('{{ $rowKey }}')">
                                <i class="ti ti-plus fs-5"></i>
                            </button>
                            <button type="button" title="Xóa hàng {{ $rowKey }}" class="seat-action-btn delete"
                                data-type="delete-button" data-row="{{ $rowKey }}"
                                onclick="deleteRow('{{ $rowKey }}')" style="display: none;">
                                <i class="ti ti-trash fs-5"></i>
                            </button>
                        </div>
                    @endforeach
                    @else
                        <div class="col-12 text-center p-4">
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i>
                                <strong>Không có dữ liệu sơ đồ ghế!</strong><br>
                                Vui lòng kiểm tra lại cấu trúc dữ liệu hoặc tạo lại sơ đồ ghế.
                            </div>
                        </div>
                    @endif
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
                    @foreach ($loaiGhes as $item)
                        @if (in_array($item->id, $gheId))
                            <div class="legend-item">
                                <span>{{ $item->ten_loai_ghe }}</span>
                                <div style="background-color: {{ $item->chu_thich_mau_ghe }}" class="legend-color"></div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </form>
    <script>
        function toggleSeat(row, col) {
            const seatSelector = `[data-row="${row}"][data-col="${col}"]`;
            const seat = $(seatSelector);
            if (seat.length === 0) return;

            if (seat.hasClass('doi')) {
                const colNum = Number(col);
                const partnerCol = (colNum % 2 === 1) ? colNum + 1 : colNum - 1;
                const partnerSelector = `[data-row="${row}"][data-col="${partnerCol}"]`;
                const partnerSeat = $(partnerSelector);

                const toggleOne = s => {
                    if (s.hasClass('empty')) {
                        s.removeClass('empty').addClass('filled').html('<i class="ti ti-armchair fs-5"></i>');
                    } else {
                        s.removeClass('filled').addClass('empty').html(
                            '<i class="ti ti-plus fs-5"></i>');
                    }
                };

                toggleOne(seat);
                if (partnerSeat.length) toggleOne(partnerSeat);
            } else {
                if (seat.hasClass('empty')) {
                    seat.removeClass('empty').addClass('filled').html('<i class="ti ti-armchair fs-5"></i>');
                } else {
                    seat.removeClass('filled').addClass('empty').html('<i class="ti ti-plus fs-5"></i>');
                }
            }

            updateRowButtons(row);
        }

        function toggleRow(row) {
            const rowSeats = $(`.seat[data-row="${row}"]`);
            rowSeats.each(function() {
                const seat = $(this);
                if (seat.hasClass('empty')) {
                    seat.removeClass('empty').addClass('filled').html('<i class="ti ti-armchair fs-5"></i>');
                }
            });
            updateRowButtons(row);
        }

        function deleteRow(row) {
            const rowSeats = $(`.seat[data-row="${row}"]`);
            rowSeats.each(function() {
                const seat = $(this);
                if (seat.hasClass('filled')) {
                    seat.removeClass('filled').addClass('empty').html(
                        '<i class="ti ti-plus"></i>');
                }
            });
            updateRowButtons(row);
        }

        function updateRowButtons(row) {
            const rowSeats = $(`.seat[data-row="${row}"]`);
            let hasFilled = false;
            rowSeats.each(function() {
                if ($(this).hasClass('filled')) hasFilled = true;
            });

            const btnGroup = $(`.seat-action-btns[data-row="${row}"]`);
            const addBtn = btnGroup.find('.seat-action-btn.add');
            const deleteBtn = btnGroup.find('.seat-action-btn.delete');

            if (hasFilled) {
                addBtn.hide();
                deleteBtn.show();
            } else {
                addBtn.show();
                deleteBtn.hide();
            }
        }

        $('form').on('submit', function() {
            const seats = [];

            $('.seat').each(function() {
                const seat = $(this);
                seats.push({
                    row: seat.data('row'),
                    col: seat.data('col'),
                    loai: seat.hasClass('empty') ? 'empty' : seat.data('loai')
                });
            });

            $('#seat_data').val(JSON.stringify(seats));
        });
    </script>
@endsection
