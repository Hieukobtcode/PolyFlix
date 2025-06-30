@extends('layouts.client')

@section('styles')
    <style>
        {!! collect($loaiGhes)->map(function ($loai) {
                $class = \Illuminate\Support\Str::slug($loai->ten_loai_ghe);
                return ".ghe-chieu.{$class} { background-color: {$loai->chu_thich_mau_ghe}; }";
            })->implode("\n") !!}
    </style>

    @vite('resources/css/trang-chu.css')
    @vite('resources/css/dat-ve.css')

@endsection
@section('title', 'Đặt vé xem phim')


@section('content')
    <div class="dat-ve-container">
        <div class="container">
            <!-- Thông tin phim -->
            <div class="movie-info">
                <div class="movie-poster">
                    <img src="{{ asset('storage/' . $suatChieu->phim->poster) }}" alt="{{ $suatChieu->phim->ten_phim }}">
                </div>
                <div class="movie-details">
                    <h2>{{ $suatChieu->phim->ten_phim }}</h2>
                    <div class="movie-meta">
                        <p><i class="fas fa-clock"></i> {{ $suatChieu->phim->thoi_luong }} phút</p>
                        <p><i class="fas fa-calendar"></i>
                            {{ \Carbon\Carbon::parse($suatChieu->ngay_chieu)->format('d/m/Y') }}</p>
                        <p><i class="fas fa-clock"></i> {{ \Carbon\Carbon::parse($suatChieu->bat_dau)->format('H:i') }} -
                            {{ \Carbon\Carbon::parse($suatChieu->ket_thuc)->format('H:i') }}</p>
                        <p><i class="fas fa-film"></i> {{ $suatChieu->phien_ban_phim }}</p>
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

                <div class="screen"></div>

                <div class="seat-map" id="seat-map">
                    @php
                        $currentRow = '';
                        $seatsByRow = $gheNgois->groupBy('hang');
                    @endphp

                    @foreach ($seatsByRow as $hangGhe => $ghes)
                        <div class="seat-row">
                            <div class="seats">
                                @foreach ($ghes as $ghe)
                                    <div class="ghe-chieu 
                                {{ $ghe->trang_thai == 'bao_tri' ? 'maintenance' : ($ghe->trang_thai == 'dang_chon' ? 'selected' : 'available') }} 
                                {{ \Illuminate\Support\Str::slug($ghe->loaiGhe->ten_loai_ghe ?? 'thuong') }} 
                                {{ $ghe->loaiGhe->id == 12 ? 'ghe-doi' : '' }}"
                                        data-seat-id="{{ $ghe->id }}" data-seat-name="{{ $ghe->ma_ghe }}"
                                        data-ten-loai-ghe="{{ $ghe->loaiGhe->ten_loai_ghe ?? 'Thường' }}"
                                        data-phu-thu-loai-phong="{{ $ghe->phu_thu_loai_phong }}"
                                        data-phu-thu-loai-ghe="{{ $ghe->phu_thu_loai_ghe }}"
                                        data-phu-thu-rap-phim="{{ $ghe->phu_thu_rap_phim }}"
                                        data-seat-type-id="{{ $ghe->loaiGhe->id }}"
                                        @if ($ghe->trang_thai == 'bao_tri' || $ghe->da_dat) disabled @endif>
                                        {{ $ghe->trang_thai == 'bao_tri' ? 'x' : $ghe->ma_ghe }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="seat-legend-wrapper">
                    <!-- Hàng 1: Trạng thái chọn -->
                    <div class="legend-column">
                        <div class="legend-item">
                            <div class="seat-demo bg-available"></div>
                            <span>Checked</span>
                        </div>
                        <div class="legend-item">
                            <div class="seat-demo bg-selected"></div>
                            <span>Đã chọn</span>
                        </div>
                        <div class="legend-item">
                            <div class="seat-demo seat-disabled">
                                <i class="fas fa-times text-danger"></i>
                            </div>
                            <span>Không thể chọn</span>
                        </div>
                    </div>

                    <!-- Hàng 2: Loại ghế -->
                    <div class="legend-column">
                        @foreach ($loaiGhes as $loai)
                            <div class="legend-item">
                                <div class="seat-demo" style="background-color: {{ $loai->chu_thich_mau_ghe }}"></div>
                                <span>{{ $loai->ten_loai_ghe }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>



                <!-- Tóm tắt đơn hàng -->
                <div class="order-summary">
                    <div class="summary-content">
                        <div class="selected-seats">
                            <h4>Ghế đã chọn:</h4>
                            <div id="selected-seats-list">Chưa chọn ghế</div>
                        </div>
                        <div class="total-price">
                            <h4>Tổng: <span id="total-amount">0đ</span></h4>
                        </div>
                    </div>
                    <button type="button" id="btn-dat-ve" class="btn-see" disabled>Next</button>
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

@section('scripts')
    @vite('resources/js/dat-ve-client.js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@endsection
