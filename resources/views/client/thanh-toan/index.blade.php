@extends('layouts.client')

@section('title', 'Thanh toán vé xem phim')

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection

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
                    @csrf
                    <input type="hidden" name="dat_ve_id" value="{{ $datVe->id }}">

                    @if (Auth::check() && Auth::user()->vai_tro_id == 4)
                        {{-- ✅ Nhân viên: chỉ thanh toán tiền mặt --}}
                        <form action="{{ route('thanh-toan.tien-mat') }}" method="POST">
                            @csrf
                            <input type="hidden" name="dat_ve_id" value="{{ $datVe->id }}">
                            <button type="submit" class="btn-thanh-toan" style="font-size:18px; width:100%;">
                                <i class="fas fa-money-bill-wave"></i> Thanh toán
                            </button>
                        </form>
                    @else
                        {{-- ✅ Khách hàng: ZaloPay --}}
                        <form id="payment-form">
                            @csrf
                            <input type="hidden" name="dat_ve_id" value="{{ $datVe->id }}">

                            <div class="payment-option">
                                <div class="payment-icon zalopay">
                                    <i class="fas fa-mobile-alt"></i>
                                </div>
                                <input type="radio" name="phuong_thuc_tt" value="zalopay" id="zalopay">
                                <div class="payment-details">
                                    <h4>ZaloPay</h4>
                                    <p>Thanh toán nhanh chóng với ví điện tử ZaloPay</p>
                                </div>
                            </div>
                        </form>

                        <button id="btn-pay" class="btn-thanh-toan" style="font-size:18px; width:100%;">
                            <i class="fas fa-credit-card"></i> Tiến hành thanh toán
                        </button>
                    @endif
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
                                        {{ \Carbon\Carbon::parse($datVe->suatChieu->ngay_bat_dau)->format('d/m/Y') }}</div>
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
                            <span>{{ number_format($tongThanhTien) }}đ</span>
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

                        <!-- Điểm -->
                        <div class="point-section"
                            style="margin: 20px 0; padding: 15px; background: #f8f9fa; border-radius: 10px;">
                            <h5 style="margin-bottom: 15px; color: #333;">
                                <i class="fa-solid fa-gift"></i> Đổi điểm
                            </h5>

                            <!-- Số điểm hiện có -->
                            <p style="margin-bottom: 10px; color: #555;">
                                <i class="fa-solid fa-coins text-warning"></i>
                                Bạn hiện có: <strong>{{ number_format(Auth::user()->diem) }}</strong> điểm
                            </p>

                            <div class="input-group">
                                <input type="text" id="point" class="form-control"
                                    placeholder="Nhập số điểm cần đổi..." style="border-radius: 8px 0 0 8px;">
                                <button type="button" id="apply-point" class="btn btn-primary"
                                    style="border-radius: 0 8px 8px 0;">
                                    <i class="fas fa-check"></i> Áp dụng
                                </button>
                            </div>

                            <div id="point-message" class="mt-2" style="display: none;"></div>
                        </div>

                        <!-- Khuyến mãi -->
                        <div class="promotion-section"
                            style="margin: 20px 0; padding: 15px; background: #f8f9fa; border-radius: 10px;">
                            <h5 style="margin-bottom: 15px; color: #333;"><i class="fas fa-tags"></i> Mã khuyến mãi</h5>
                            <div class="input-group">
                                <input type="text" id="promotion-code" class="form-control"
                                    placeholder="Nhập mã khuyến mãi..." style="border-radius: 8px 0 0 8px;">
                                <button type="button" id="apply-promotion" class="btn btn-primary"
                                    style="border-radius: 0 8px 8px 0;">
                                    <i class="fas fa-check"></i> Áp dụng
                                </button>
                            </div>
                            <div id="promotion-message" class="mt-2" style="display: none;"></div>
                            <div id="promotion-discount" class="price-row"
                                style="display: none; color: #28a745; font-weight: bold;">
                                <span>Giảm giá</span>
                                <span id="discount-amount">0đ</span>
                            </div>
                        </div>

                        <!-- Tổng tiền -->
                        <div class="total-section"
                            style="border-top: 2px solid #dee2e6; padding-top: 15px; margin-top: 15px;">
                            <div class="price-row total" style="font-size: 1.2rem; font-weight: bold; color: #ff5757;">
                                <span>Tổng cộng</span>
                                <span
                                    id="final-total">{{ number_format($tongThanhTien + $tongTienCombo + $tongTienDoAn) }}đ</span>
                            </div>
                        </div>
                    </div>
                    <!-- Đồng hồ đếm ngược -->
                    @if (isset($expiresAt))
                        <div class="alert alert-warning">
                            Thời gian giữ vé còn: <span id="countdown"></span>
                        </div>

                        <!-- Modal thông báo -->
                        <div class="modal fade" id="expireModal" tabindex="-1" aria-labelledby="expireModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content text-center text-dark"> {{-- 👈 thêm text-dark --}}
                                    <div class="modal-header">
                                        <h5 class="modal-title text-dark" id="expireModalLabel">Thông báo</h5>
                                        {{-- 👈 thêm text-dark --}}
                                    </div>
                                    <div class="modal-body">
                                        Đơn của bạn đã hết thời gian giữ vé.<br>
                                        Bạn sẽ được chuyển về trang chủ sau <span id="autoClose">30</span> giây.
                                    </div>
                                    <div class="modal-footer justify-content-center">
                                        <button type="button" id="agreeBtn" class="btn btn-primary">Đồng ý</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <script>
                            (function() {
                                const expiresAt = new Date("{{ $expiresAt->format('Y-m-d H:i:s') }}").getTime();
                                const countdownEl = document.getElementById('countdown');
                                const autoCloseEl = document.getElementById('autoClose');

                                function pad(n) {
                                    return n < 10 ? '0' + n : n;
                                }

                                const timer = setInterval(() => {
                                    const now = Date.now();
                                    const diff = Math.floor((expiresAt - now) / 1000);

                                    if (diff <= 0) {
                                        clearInterval(timer);
                                        countdownEl.innerText = "Hết thời gian!";

                                        // Gọi API hủy vé
                                        fetch("{{ route('client.thanh-toan.huy', $datVe->id) }}", {
                                            method: 'POST',
                                            headers: {
                                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                                'Accept': 'application/json'
                                            }
                                        }).then(() => {
                                            // Hiện modal Bootstrap
                                            const modal = new bootstrap.Modal(document.getElementById('expireModal'));
                                            modal.show();

                                            // Đếm ngược 30 giây
                                            let autoClose = 30;
                                            const autoTimer = setInterval(() => {
                                                autoClose--;
                                                autoCloseEl.innerText = autoClose;
                                                if (autoClose <= 0) {
                                                    clearInterval(autoTimer);
                                                    window.location.href = "{{ route('home') }}";
                                                }
                                            }, 1000);

                                            // Nút đồng ý → về home ngay
                                            document.getElementById('agreeBtn').addEventListener('click', function() {
                                                clearInterval(autoTimer);
                                                window.location.href = "{{ route('home') }}";
                                            });
                                        });

                                    } else {
                                        const m = Math.floor(diff / 60);
                                        const s = diff % 60;
                                        countdownEl.innerText = pad(m) + ':' + pad(s);
                                    }
                                }, 1000);
                            })();
                        </script>
                    @endif
                    <!-- Hủy đặt vé -->
                    <form method="POST" action="{{ route('client.thanh-toan.huy', $datVe->id) }}"
                        style="margin-top: 20px;">
                        @csrf
                        <button type="submit" class="payment-button"
                            style="background: #e53e3e;width:50%;margin-left:250px;"
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
@section('scripts')
    <script>
        let isPaying = false;

        document.getElementById("btn-pay").addEventListener("click", function() {
            isPaying = true; // bật cờ khi bấm thanh toán
        });

        window.addEventListener("beforeunload", function() {
            if (!isPaying) { 
                const url = "{{ route('client.thanh-toan.huy', $datVe->id) }}";
                const data = new FormData();
                data.append("_token", "{{ csrf_token() }}");

                navigator.sendBeacon(url, data);
            }
        });

        $(document).ready(function() {
            $('#apply-point').click(function() {
                // Lấy số điểm nhập vào và tổng tiền hiện tại
                let points = parseInt($('#point').val()) || 0;
                let pointMessage = $('#point-message');
                let pointDiscount = $('#point-discount');
                let discountAmount = $('#discount-amount');
                let finalTotal = $('#final-total');

                // Lấy tổng tiền hiện tại từ phần tử final-total (loại bỏ ký tự đ và định dạng)
                let currentTotalText = finalTotal.text().replace('đ', '').replace(/,/g, '');
                let currentTotal = parseInt(currentTotalText) || 0;

                // Lấy số điểm hiện có của người dùng
                let availablePointsText = $('strong', '.point-section').text().replace(/,/g, '');
                let availablePoints = parseInt(availablePointsText) || 0;

                // Kiểm tra dữ liệu đầu vào
                if (points <= 0) {
                    pointMessage.text('Vui lòng nhập số điểm hợp lệ!').css('color', 'red').show();
                    return;
                }

                if (points < 1000) {
                    pointMessage.text('Số điểm đổi tối thiểu phải là 1000!').css('color', 'red').show();
                    return;
                }

                if (points > availablePoints) {
                    pointMessage.text('Số điểm nhập vượt quá số điểm hiện có!').css('color', 'red').show();
                    return;
                }

                // Tính toán giảm giá (1000 điểm = 1000 VND)
                let discount = points;
                let newTotal = currentTotal - discount;

                if (newTotal < 0) {
                    pointMessage.text('Số điểm đổi vượt quá tổng tiền!').css('color', 'red').show();
                    return;
                }

                // Cập nhật giao diện
                pointMessage.text('Đổi điểm thành công!').css('color', 'green').show();
                pointDiscount.show();
                discountAmount.text(numberFormat(discount) + 'đ');
                finalTotal.text(numberFormat(newTotal) + 'đ');

                // Gửi yêu cầu AJAX để cập nhật cơ sở dữ liệu
                $.ajax({
                    url: '/update-points', // Điều chỉnh URL theo route của bạn
                    type: 'POST',
                    data: {
                        points_used: points,
                        discount: discount,
                        new_total: newTotal,
                        _token: '{{ csrf_token() }}' // Token CSRF của Laravel
                    },
                    success: function(response) {
                        if (response.success) {
                            pointMessage.text('Đổi điểm thành công').css('color', 'green')
                            .show();
                            // Cập nhật số điểm hiển thị
                            let newPoints = availablePoints - points;
                            $('strong', '.point-section').text(numberFormat(newPoints));
                            $('#point').val(''); // Xóa input
                            // Ẩn chỉ cái input-group đầu tiên
                            $('.input-group:first').hide();
                        } else {
                            pointMessage.text('Lỗi khi cập nhật điểm: ' + response.message).css(
                                'color', 'red').show();
                        }
                    },
                    error: function(xhr) {
                        pointMessage.text('Đã có lỗi xảy ra, vui lòng thử lại!').css('color',
                            'red').show();
                    }
                });
            });

            // Hàm định dạng số với dấu phẩy
            function numberFormat(number) {
                return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            }
        });
    </script>
@endsection
