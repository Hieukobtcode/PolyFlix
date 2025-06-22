@extends('layouts.client')

@section('title', 'Đặt vé xem phim')

@section('styles')
@vite('resources/css/dat-ve.css')
@endsection

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
                    <p><i class="fas fa-calendar"></i> {{ \Carbon\Carbon::parse($suatChieu->ngay_chieu)->format('d/m/Y') }}</p>
                    <p><i class="fas fa-clock"></i> {{ \Carbon\Carbon::parse($suatChieu->bat_dau)->format('H:i') }} - {{ \Carbon\Carbon::parse($suatChieu->ket_thuc)->format('H:i') }}</p>
                    <p><i class="fas fa-film"></i> {{ $suatChieu->phien_ban_phim }}</p>
                </div>
                <div class="cinema-info">
                    <p><i class="fas fa-map-marker-alt"></i> {{ $suatChieu->phongChieu->rapPhim->chiNhanh->ten_chi_nhanh }}</p>
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

                @foreach($seatsByRow as $hangGhe => $ghes)
                    <div class="seat-row">
                        <div class="row-label">{{ $hangGhe }}</div>
                        <div class="seats">
                            @foreach($ghes as $ghe)
                                <div class="seat {{ $ghe->da_dat ? 'occupied' : 'available' }} {{ $ghe->loaiGhe->ten_loai_ghe ?? 'thuong' }}"
                                     data-seat-id="{{ $ghe->id }}"
                                     data-seat-name="{{ $ghe->ma_ghe }}"
                                     data-seat-price="{{ $ghe->loaiGhe->phu_thu ?? 0 }}"
                                     @if($ghe->da_dat) disabled @endif>
                                    {{ $ghe->cot }}
                                </div>
                            @endforeach
                        </div>
                        <div class="row-label">{{ $hangGhe }}</div>
                    </div>
                @endforeach
            </div>

            <!-- Chú thích ghế -->
            <div class="seat-legend">
                <div class="legend-item">
                    <div class="seat available"></div>
                    <span>Ghế trống</span>
                </div>
                <div class="legend-item">
                    <div class="seat selected"></div>
                    <span>Ghế đã chọn</span>
                </div>
                <div class="legend-item">
                    <div class="seat occupied"></div>
                    <span>Ghế đã đặt</span>
                </div>
                <div class="legend-item">
                    <div class="seat vip"></div>
                    <span>Ghế VIP</span>
                </div>
            </div>
        </div>

        <!-- Chọn đồ ăn -->
        <div class="food-selection">
            <h3>Chọn đồ ăn & nước uống</h3>
            <div class="food-tabs">
                <button class="tab-btn active" data-tab="do-an">Đồ ăn</button>
                <button class="tab-btn" data-tab="combo">Combo</button>
            </div>

            <!-- Tab đồ ăn -->
            <div class="tab-content active" id="do-an">
                <div class="food-grid">
                    @foreach($doAns as $doAn)
                        <div class="food-item">
                            <img src="{{ asset('storage/' . $doAn->hinh_anh) }}" alt="{{ $doAn->tieu_de }}">
                            <div class="food-info">
                                <h4>{{ $doAn->tieu_de }}</h4>
                                <p class="price">{{ number_format($doAn->gia) }}đ</p>
                                <div class="quantity-control">
                                    <button type="button" class="qty-btn minus" data-target="do-an-{{ $doAn->id }}">-</button>
                                    <input type="number" name="do_an[{{ $doAn->id }}]" id="do-an-{{ $doAn->id }}" value="0" min="0" max="10" readonly>
                                    <button type="button" class="qty-btn plus" data-target="do-an-{{ $doAn->id }}">+</button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Tab combo -->
            <div class="tab-content" id="combo">
                <div class="food-grid">
                    @foreach($combos as $combo)
                        <div class="food-item">
                            <img src="{{ asset('storage/' . $combo->hinh_anh) }}" alt="{{ $combo->ten_combo }}">
                            <div class="food-info">
                                <h4>{{ $combo->ten_combo }}</h4>
                                <p class="description">{{ $combo->mo_ta }}</p>
                                <p class="price">{{ number_format($combo->gia) }}đ</p>
                                <div class="quantity-control">
                                    <button type="button" class="qty-btn minus" data-target="combo-{{ $combo->id }}">-</button>
                                    <input type="number" name="combo[{{ $combo->id }}]" id="combo-{{ $combo->id }}" value="0" min="0" max="10" readonly>
                                    <button type="button" class="qty-btn plus" data-target="combo-{{ $combo->id }}">+</button>
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
                <div class="total-price">
                    <h4>Tổng tiền: <span id="total-amount">0đ</span></h4>
                </div>
                <button type="button" id="btn-dat-ve" class="btn-primary" disabled>Đặt vé</button>
            </div>
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
@endsection

@section('scripts')
@vite('resources/js/dat-ve-client.js')
@endsection
