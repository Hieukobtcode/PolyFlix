@extends('layouts.client')

@section('title', 'Thanh toán vé xem phim')

@section('content')
    @vite('resources/css/thanh-toan.css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        window.routeThanhToan = "{{ route('client.thanh-toan.xu-ly') }}";
    </script>
    <div class="thanh-toan-container">
        <div class="container">
            <div class="payment-header">
                <h2><i class="fas fa-credit-card"></i> Thanh toán vé xem phim</h2>
                <p>Vui lòng chọn phương thức thanh toán để hoàn tất đặt vé</p>
            </div>

            <div class="payment-container">
                <div class="payment-methods">
                    <form id="payment-form">
                        @csrf
                        <input type="hidden" name="dat_ve_id" value="{{ $datVe->id }}">

                        <div class="payment-option" data-method="zalopay">
                            <input type="radio" name="phuong_thuc_tt" value="zalopay" id="zalopay">
                            <div class="payment-icon zalopay">
                                <i class="fas fa-mobile-alt"></i>
                            </div>
                            <div class="payment-details">
                                <h4>ZaloPay</h4>
                                <p>Thanh toán nhanh chóng với ví điện tử ZaloPay</p>
                            </div>
                        </div>
                    </form>

                    <button id="btn-pay" class="btn-thanh-toan" disabled>
                        <i class="fas fa-credit-card"></i> Tiến hành thanh toán
                    </button>
                </div>

                <div class="order-summary">
                    <div class="movie-summary">
                        <div class="movie-info">
                            <img src="{{ asset('storage/' . $datVe->suatChieu->phim->poster) }}"
                                alt="{{ $datVe->suatChieu->phim->ten_phim }}" class="movie-poster">
                            <div class="movie-details">
                                <h4>{{ $datVe->suatChieu->phim->ten_phim }}</h4>
                                <div class="movie-meta">
                                    <div><i class="fas fa-map-marker-alt"></i>
                                        {{ $datVe->suatChieu->phongChieu->rapPhim->chiNhanh->ten_chi_nhanh }}</div>
                                    <div><i class="fas fa-door-open"></i> {{ $datVe->suatChieu->phongChieu->ten_phong }}
                                    </div>
                                    <div><i class="fas fa-calendar"></i>
                                        {{ \Carbon\Carbon::parse($datVe->suatChieu->ngay_chieu)->format('d/m/Y') }}</div>
                                    <div><i class="fas fa-clock"></i>
                                        {{ \Carbon\Carbon::parse($datVe->suatChieu->bat_dau)->format('H:i') }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="seat_select">
                            <strong><i class="fas fa-couch"></i> Ghế đã chọn:</strong>
                            @foreach ($datVe->gheNgois as $ghe)
                                {{ $ghe->ma_ghe }}{{ !$loop->last ? ', ' : '' }}
                            @endforeach
                        </div>
                    </div>

                    <div class="price-details">
                        <div class="price-row">
                            <span>Vé ({{ $datVe->gheNgois->count() }} ghế)</span>
                            <span>{{ number_format($tongTienGhe) }}đ</span>
                        </div>

                        @if ($tongTienCombo > 0)
                            <div class="price-row">
                                <span>Combo</span>
                                <span>{{ number_format($tongTienCombo) }}đ</span>
                            </div>
                        @endif

                        @if ($tongTienDoAn > 0)
                            <div class="price-row">
                                <span>Đồ ăn & nước uống</span>
                                <span>{{ number_format($tongTienDoAn) }}đ</span>
                            </div>
                        @endif
                    </div>

                    <form method="POST" action="{{ route('client.thanh-toan.huy', $datVe->id) }}"
                        style="margin-top: 20px;">
                        @csrf
                        <button type="submit" class="payment-button" style="background: #e53e3e;"
                            onclick="return confirm('Bạn có chắc chắn muốn hủy đặt vé?')">
                            <i class="fas fa-times"></i> Hủy đặt vé
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @vite('resources/js/thanh-toan.js')

@endsection

