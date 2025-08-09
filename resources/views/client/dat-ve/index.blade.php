@extends('layouts.client')

@section('styles')
    <style>
        {!! collect($loaiGhes)->map(function ($loai) {
                $class = \Illuminate\Support\Str::slug($loai->ten_loai_ghe);
                return ".ghe-chieu.{$class} { background-color: {$loai->chu_thich_mau_ghe}; }";
            })->implode("\n") !!}
    </style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://localhost:6001/socket.io/socket.io.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.11.3/echo.iife.js"></script>
    @vite('resources/css/dat-ve.css')
    @vite('resources/js/dat-ve-client.js')

@endsection

@section('title', 'Đặt vé xem phim')

@section('content')

    <div class="dat-ve-container">
        <div class="container">
            <div class="movie-info">
                <div class="movie-poster">
                    <img src="{{ asset('storage/' . $suatChieu->phim->poster) }}" alt="{{ $suatChieu->phim->ten_phim }}">
                </div>
                <div class="movie-details">
                    <h2>{{ $suatChieu->phim->ten_phim }}</h2>
                    <div class="movie-meta">
                        <p><i class="fas fa-clock"></i> {{ $suatChieu->phim->thoi_luong }} phút</p>
                        <p><i class="fas fa-calendar"></i>
                            {{ \Carbon\Carbon::parse($suatChieu->ngay_bat_dau)->format('d/m/Y') }}</p>
                        <p><i class="fas fa-clock"></i> {{ \Carbon\Carbon::parse($suatChieu->bat_dau)->format('H:i') }} -
                            {{ \Carbon\Carbon::parse($suatChieu->ket_thuc)->format('H:i') }}</p>
                        <p><i class="fas fa-film"></i> {{ $suatChieu->formatted_version }}</p>
                    </div>
                    <div class="cinema-info">
                        <p><i class="fas fa-map-marker-alt"></i>
                            {{ $suatChieu->phongChieu->rapPhim->chiNhanh->ten_chi_nhanh }}</p>
                        <p><i class="fas fa-door-open"></i> {{ $suatChieu->phongChieu->ten_phong }}</p>
                    </div>
                </div>
            </div>

            <!-- Chọn ghế -->
            <div class="seat-selection">
                <h3>Chọn ghế ngồi</h3>
                <div class="screen">
                    <div class="screen-text">MÀN HÌNH</div>
                </div>
                <div class="seat-map" id="seat-map">
                    @php
                        $currentRow = '';
                        $seatsByRow = $gheNgois->groupBy('hang');
                    @endphp
                    @foreach ($seatsByRow as $hangGhe => $ghes)
                        <div class="seat-row">
                            <div class="row-label">{{ $hangGhe }}</div>
                            <div class="seats">
                                @foreach ($ghes as $ghe)
                                    @php
                                        // trạng thái helper
                                        $isMaintenance = ($ghe->trang_thai_mac_dinh ?? $ghe->trang_thai) === 'bao_tri';
                                        $suatStatus = $ghe->trang_thai_theo_suat ?? 'trong'; // 'trong'|'da_chon'|'da_dat'
                                        $isSuatBooked = in_array($suatStatus, ['da_chon', 'da_dat']);
                                        $isAvailable =
                                            !$ghe->da_dat && !$ghe->dang_duoc_chon && !$isMaintenance && !$isSuatBooked;
                                        $loaiClass = \Illuminate\Support\Str::slug(
                                            $ghe->loaiGhe->ten_loai_ghe ?? 'thuong',
                                        );

                                    @endphp
                                    {{-- Hiển thị ghế --}}
                                    <div class="ghe-chieu
                                    {{-- Nếu ghế đang bảo trì --}}
                                    {{ $isMaintenance ? 'maintenance' : '' }}
                                    {{ $suatStatus === 'da_chon' ? 'booked' : '' }}
                                    {{ $suatStatus === 'da_dat' ? 'reserved' : '' }}
                                    {{ $isAvailable ? 'available' : '' }}
                                    {{ $loaiClass }}

                                    {{-- Ghế đôi --}}
                                    {{ $ghe->loaiGhe->id == 12 ? 'ghe-doi' : '' }}"
                                        data-seat-id="{{ $ghe->id }}" data-seat-name="{{ $ghe->ma_ghe }}"
                                        data-ten-loai-ghe="{{ $ghe->loaiGhe->ten_loai_ghe ?? 'Thường' }}"
                                        data-phu-thu-loai-phong="{{ $ghe->phu_thu_loai_phong }}"
                                        data-phu-thu-loai-ghe="{{ $ghe->phu_thu_loai_ghe }}"
                                        data-phu-thu-rap-phim="{{ $ghe->phu_thu_rap_phim }}"
                                        data-seat-type-id="{{ $ghe->loaiGhe->id }}"
                                        @if ($isMaintenance || $isSuatBooked) disabled @endif>
                                        {{-- Hiển thị "x" nếu ghế bị bảo trì (mặc định) hoặc bị giữ/đặt theo suất --}}
                                        {{ $isMaintenance || $isSuatBooked ? 'x' : $ghe->ma_ghe }}
                                    </div>
                                @endforeach
                            </div>
                            <div class="row-label">{{ $hangGhe }}</div>
                        </div>
                    @endforeach
                </div>

                <!-- Chú thích ghế -->
                <div class="seat-legend-wrapper">
                    <div class="legend-column">
                        <div class="legend-item">
                            <div class="seat-demo bg-available"></div><span>Checked</span>
                        </div>
                        <div class="legend-item">
                            <div class="seat-demo bg-selected"></div><span>Đã chọn</span>
                        </div>
                        <div class="legend-item">
                            <div class="seat-demo seat-disabled">
                                {{-- <i class="fas fa-times text-danger"></i> --}}
                            </div><span>Không
                                thể chọn</span>
                        </div>
                    </div>
                    <div class="legend-column">
                        @foreach ($loaiGhes as $loai)
                            <div class="legend-item">
                                <div class="seat-demo" style="background-color: {{ $loai->chu_thich_mau_ghe }}"></div>
                                <span>{{ $loai->ten_loai_ghe }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Chọn đồ ăn -->
            <div class="food-selection">
                <div class="food-tabs">
                    <button style="color: white" class="tab-btn active" data-tab="do-an">Đồ ăn</button>
                    <button style="color: white" class="tab-btn" data-tab="combo">Combo</button>
                </div>

                <div class="tab-content active" id="do-an">
                    <div class="food-grid">
                        @foreach ($doAns as $doAn)
                            <div class="food-item">
                                <img src="{{ asset('storage/' . $doAn->hinh_anh) }}" alt="{{ $doAn->tieu_de }}">
                                <div class="food-info">
                                    <h4>{{ $doAn->tieu_de }}</h4>
                                    <p class="price">{{ number_format($doAn->gia) }}đ</p>
                                    <div class="quantity-control">
                                        <button type="button" class="qty-btn minus"
                                            data-target="do-an-{{ $doAn->id }}">-</button>
                                        <input type="number" name="do_an[{{ $doAn->id }}]"
                                            id="do-an-{{ $doAn->id }}" value="0" min="0" max="10"
                                            readonly>
                                        <button type="button" class="qty-btn plus"
                                            data-target="do-an-{{ $doAn->id }}">+</button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="tab-content" id="combo">
                    <div class="food-grid">
                        @foreach ($combos as $combo)
                            <div class="food-item">
                                <img src="{{ asset('storage/' . $combo->hinh_anh) }}" alt="{{ $combo->ten_combo }}">
                                <div class="food-info">
                                    <h4>{{ $combo->ten_combo }}</h4>
                                    <p class="description">{{ $combo->mo_ta }}</p>
                                    <p class="price">{{ number_format($combo->gia) }}đ</p>
                                    <div class="quantity-control">
                                        <button type="button" class="qty-btn minus"
                                            data-target="combo-{{ $combo->id }}">-</button>
                                        <input type="number" name="combo[{{ $combo->id }}]"
                                            id="combo-{{ $combo->id }}" value="0" min="0" max="10"
                                            readonly>
                                        <button type="button" class="qty-btn plus"
                                            data-target="combo-{{ $combo->id }}">+</button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Tóm tắt đơn hàng -->
            <div class="order-summary">
                <h3>Tóm tắt đơn hàng</h3>
                <div class="summary-content">
                    <div class="selected-seats">
                        <h4>Ghế đã chọn:</h4>
                        <div id="selected-seats-list">Chưa chọn ghế</div>
                    </div>
                    <div class="selected-food">
                        <h4>Đồ ăn & nước uống:</h4>
                        <div id="selected-food-list">Chưa chọn</div>
                    </div>
                    <div id="pointView" style="display: none" class="point">
                        <input id="point" style="height: 35px; border-radius: 5px;" type="number">
                        <button id="btnPoint" class="btn btn-warning">Đổi điểm</button>
                        <p style="color: yellow;" class="text-muted">
                            Số điểm hiện có: <span style="color: yellow;" id="diemHienCo"
                                data-diem="{{ Auth::user()->diem }}">{{ number_format(Auth::user()->diem) }}</span>
                        </p>
                    </div>
                    <div class="total-price">
                        <h4>Tổng tiền: <span id="total-amount">0đ</span></h4>
                    </div>

                    <button type="button" id="btn-dat-ve" class="btn btn-warning" disabled>Đặt vé</button>
                </div>
            </div>
        </div>

        <!-- Form ẩn để submit -->
        <form id="booking-form" style="display: none;">
            @csrf
            <input type="hidden" name="suat_chieu_id" value="{{ $suatChieu->id }}">
            <div id="selected-seats-input"></div>
            <div id="selected-food-input"></div>
        </form>
    </div>
@endsection
