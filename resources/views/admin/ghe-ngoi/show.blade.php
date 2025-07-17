@extends('layouts.admin')
@php
    $tenPhong = $phongChieu->ten_phong;
    $loaiGheIdsDangSuDung = [];

    foreach ($gheGroupedArray as $seatsInRow) {
        foreach ($seatsInRow as $oneSeat) {
            if ($oneSeat['loai_ghe'] !== 'empty') {
                $loaiGheIdsDangSuDung[] = $oneSeat['loai_ghe'] ?? null;
            }
        }
    }

    $loaiGheIdsDangSuDung = array_unique(array_filter($loaiGheIdsDangSuDung));
@endphp

@section('content')
    <div class="container mx-auto p-6">
        @vite('resources/js/app.js')

        <form id="updateSeatForm" action="{{ route('admin.ghe-ngoi.updateSeat') }}" method="POST">
            @csrf

            <div class="two-column">
                <input type="hidden" name="allSeat" id="allSeat">
                <div class="col-left">
                    @if (Auth::user()->vai_tro_id !== 2)
                        <div class="seat-toolbar">
                            <button class="btn btn-addSeat" id="btn-addSeat" type="button">
                                <i class="ti ti-settings fs-5"></i>
                            </button>

                            {{-- ============================================================================== --}}

                            <select name="" id="select-addSeat" style="display:none" class="select-hang">
                                <option value="">--Thêm ghế--</option>
                                <option value="1">Thêm hàng</option>
                                <option value="2">Thêm cột</option>
                            </select>

                            {{-- Chọn loại ghế --}}
                            <select style="display: none" name="add-hang-ghe" class="add-hang-ghe" id="add-hang-ghe">
                                <option value="">Chọn loại ghế</option>
                                @foreach ($loaiGhes as $item)
                                    <option value="{{ $item->id }}">{{ $item->ten_loai_ghe }}</option>
                                @endforeach
                            </select>

                            {{-- Nhập số lượng hàng ghế theo loại --}}
                            <input placeholder="Số hàng" style="display: none" max="10"
                                id="so_luong_hang_ghe_theo_loai" class="add-hang-ghe-theo-loai" type="number"
                                min="1">

                            {{-- Nhập số ghế khi thêm hàng --}}
                            <input type="number" placeholder="Số ghế" style="display: none" id="so-ghe-khi-them-hang"
                                class="so-ghe-khi-them-hang" min="1">

                            {{-- Nút thêm hàng --}}
                            <button style="display: none" type="button" class="btn-them-hang" id="btn-them-hang">Thêm
                                hàng</button>

                            {{-- ===================================================================================== --}}

                            {{-- Chọn cột ghế --}}
                            <div id="chon-cot-ghe" style="display: none" class="btn-group list-cot w-100" role="group">
                                <button type="button" class="btn btn-primary dropdown-toggle w-100"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    Chọn cột ghế
                                </button>
                                <div class="dropdown-menu w-100 p-3">
                                    <div class="grid-checkboxes">
                                        @foreach ($gheGroupedArray as $hangIndex => $item)
                                            <label class="form-check">
                                                <input class="form-check-input" type="checkbox" name="checkbox[]"
                                                    value="{{ $hangIndex }}">
                                                {{ $hangIndex }}
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            {{-- Nhập số lượng ghế  --}}
                            <input placeholder="Số ghế" class="so-luong-cot-ghe" type="number" id="so-luong-cot-ghe"
                                style="display: none">

                            {{-- Chọn cột right or left --}}
                            <select style="display: none" id="right-or-left" class="right-or-left" name="right-or-left">
                                <option value="">Chọn bên ghế</option>
                                <option value="1">Hàng phải</option>
                                <option value="2">Hàng trái</option>
                            </select>

                            {{-- Btn thêm cột ghế --}}
                            <button style="display: none" type="button" class="btn-cot-ghe" id="btn-them-ghe">Thêm
                                ghế</button>

                            {{-- =================================================================================================== --}}

                        </div>
                    @endif

                    <div class="seat-map">
                        <div class="screen">Màn Hình Chiếu</div>
                        <input type="hidden" name="phongChieuId" id="phongChieuId" value="{{ $phongChieuId }}">
                        <input type="hidden" name="seats_json" id="hiddenSeatsJson">
                        @foreach ($gheGroupedArray as $hangIndex => $seatsInRow)
                            <div class="seat-row" data-row="{{ $hangIndex }}">
                                @foreach ($seatsInRow as $oneSeat)
                                    @php
                                        $mau = $mauGhes[$oneSeat['loai_ghe']];
                                        $maGhe = $oneSeat['ma_ghe'];
                                        $loaiGhe = $oneSeat['loai_ghe'];
                                        $isDouble = $oneSeat['loai_ghe'] == 12 ? 'doi' : '';
                                    @endphp

                                    @if ($oneSeat['loai_ghe'] !== 'empty')
                                        <div style="background-color: {{ $mau }}"
                                            data-loai="{{ $oneSeat['loai_ghe'] }}" data-id="{{ $oneSeat['id'] }}"
                                            class="{{ $isDouble }} seat-wrapper {{ $oneSeat['trang_thai'] === 'bao_tri' ? 'selected' : '' }}"
                                            data-seat="{{ $maGhe }}" data-row='{{ $oneSeat['hang'] }}'
                                            data-col='{{ $oneSeat['cot'] }}'>
                                            <i class="ti ti-armchair fs-5"></i>
                                            <span data-hang="{{ $oneSeat['hang'] }}"
                                                class="seat-code">{{ $maGhe }}</span>
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
                        @if (Auth::user()->vai_tro_id !== 2)
                            <div class="panel-box">
                                <h4>Cập nhật</h4>
                                <p><strong>Trạng thái:</strong>
                                    {{-- {{ $soDoGhe->trang_thai == 1 ? 'Chưa hoạt động' : 'Hoạt động' }} --}}
                                </p>
                                <div class="btn-group">
                                    <button type="submit" id="btn_update" class="btn-publish">Cập nhật</button>
                                </div>
                            </div>
                        @endif

                        <div class="panel-box">
                            <h4>Chú thích</h4>
                            @foreach ($loaiGhes as $item)
                                @if (in_array($item->id, $loaiGheIdsDangSuDung))
                                    <div class="legend-item">
                                        <span>{{ $item->ten_loai_ghe }}</span>
                                        <div style="background-color: {{ $item->chu_thich_mau_ghe }}"
                                            class="legend-color">
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                            <div class="legend-item">
                                <span>Ghế hỏng</span>
                                <div class="legend-color selected" style="position: relative; background-color: #f87171;">
                                    <i class="ti ti-armchair fs-5"
                                        style="color: #34495e; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);"></i>
                                </div>
                            </div>

                            <div class="legend-item">
                                <span>Tổng số ghế</span>
                                <span>{{ $totalSeats }} / {{ $gheBaoTri }}</span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </form>
        <script>
            window.tenLoaiGheMap = {!! json_encode($loaiGhes->pluck('ten', 'id')) !!};
        </script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    </div>
@endsection
