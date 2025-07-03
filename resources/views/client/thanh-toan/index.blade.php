@extends('layouts.client')

@section('styles')
@vite('resources/css/thanh-toan.css')
@endsection

@section('title', 'Thanh toán vé xem phim')

@section('content')
<div class="thanh-toan-container">
    <div class="payment-wrapper">
        <div class="payment-card">
            <!-- Header -->
            <div class="payment-header">
                <h1>Thanh toán</h1>
                <p>Vui lòng chọn phương thức thanh toán để hoàn tất đặt vé</p>
            </div>

            <div class="payment-content">
                <!-- Left side - Payment methods -->
                <div class="payment-methods">
                    <h3>Chọn phương thức thanh toán</h3>

                    <form id="payment-form">
                        @csrf
                        <input type="hidden" name="dat_ve_id" value="{{ $datVe->id }}">

                        <!-- MoMo -->
                        <div class="payment-option momo" data-method="momo">
                            <input type="radio" name="phuong_thuc_tt" value="momo" id="momo">
                            <label for="momo" class="payment-option-content">
                                <div class="payment-icon">
                                    <i class="fab fa-apple-pay"></i>
                                </div>
                                <div class="payment-details">
                                    <h4>Ví MoMo</h4>
                                    <p>Thanh toán nhanh chóng và bảo mật với ví điện tử MoMo</p>
                                </div>
                            </label>
                        </div>

                        <!-- VNPay -->
                        <div class="payment-option vnpay" data-method="vnpay">
                            <input type="radio" name="phuong_thuc_tt" value="vnpay" id="vnpay">
                            <label for="vnpay" class="payment-option-content">
                                <div class="payment-icon">
                                    <i class="fas fa-credit-card"></i>
                                </div>
                                <div class="payment-details">
                                    <h4>VNPay</h4>
                                    <p>Thanh toán qua thẻ ATM, Internet Banking, Visa, MasterCard</p>
                                </div>
                            </label>
                        </div>

                        <!-- ZaloPay -->
                        <div class="payment-option zalopay" data-method="zalopay">
                            <input type="radio" name="phuong_thuc_tt" value="zalopay" id="zalopay">
                            <label for="zalopay" class="payment-option-content">
                                <div class="payment-icon">
                                    <i class="fas fa-mobile-alt"></i>
                                </div>
                                <div class="payment-details">
                                    <h4>ZaloPay</h4>
                                    <p>Thanh toán liền tay với ví điện tử ZaloPay</p>
                                </div>
                            </label>
                        </div>

                        <!-- Banking -->
                        <div class="payment-option banking" data-method="banking">
                            <input type="radio" name="phuong_thuc_tt" value="banking" id="banking">
                            <label for="banking" class="payment-option-content">
                                <div class="payment-icon">
                                    <i class="fas fa-university"></i>
                                </div>
                                <div class="payment-details">
                                    <h4>Chuyển khoản ngân hàng</h4>
                                    <p>Chuyển khoản trực tiếp qua ngân hàng</p>
                                </div>
                            </label>
                        </div>

                        <!-- COD -->
                        <div class="payment-option cod" data-method="cod">
                            <input type="radio" name="phuong_thuc_tt" value="cod" id="cod">
                            <label for="cod" class="payment-option-content">
                                <div class="payment-icon">
                                    <i class="fas fa-hand-holding-usd"></i>
                                </div>
                                <div class="payment-details">
                                    <h4>Thanh toán tại quầy</h4>
                                    <p>Thanh toán trực tiếp tại rạp trước giờ chiếu 30 phút</p>
                                </div>
                            </label>
                        </div>
                    </form>

                    <!-- Banking info (hidden by default) -->
                    <div class="banking-info" id="banking-info">
                        <h4>🏦 Thông tin chuyển khoản</h4>
                        <div class="bank-detail">
                            <span class="bank-label">Ngân hàng:</span>
                            <span class="bank-value">Techcombank</span>
                        </div>
                        <div class="bank-detail">
                            <span class="bank-label">Số tài khoản:</span>
                            <span class="bank-value">
                                19036766589018
                                <button type="button" class="copy-btn" onclick="copyToClipboard('19036766589018')">Copy</button>
                            </span>
                        </div>
                        <div class="bank-detail">
                            <span class="bank-label">Tên tài khoản:</span>
                            <span class="bank-value">CONG TY TNHH POLYFLIX</span>
                        </div>
                        <div class="bank-detail">
                            <span class="bank-label">Nội dung:</span>
                            <span class="bank-value">
                                POLYFLIX {{ $datVe->ma_dat_ve }}
                                <button type="button" class="copy-btn" onclick="copyToClipboard('POLYFLIX {{ $datVe->ma_dat_ve }}')">Copy</button>
                            </span>
                        </div>
                        <div class="bank-detail">
                            <span class="bank-label">Số tiền:</span>
                            <span class="bank-value">{{ number_format($tongThanhTien) }}đ</span>
                        </div>
                    </div>
                </div>

                <!-- Right side - Order summary -->
                <div class="order-summary">
                    <h3>Thông tin đặt vé</h3>

                    <!-- Movie summary -->
                    <div class="movie-summary">
                        <div class="movie-info">
                            <div class="movie-poster">
                                <img src="{{ asset('storage/' . $datVe->suatChieu->phim->poster) }}"
                                    alt="{{ $datVe->suatChieu->phim->ten_phim }}">
                            </div>
                            <div class="movie-details">
                                <h4>{{ $datVe->suatChieu->phim->ten_phim }}</h4>
                                <div class="movie-meta">
                                    <div><i class="fas fa-map-marker-alt"></i> {{ $datVe->suatChieu->phongChieu->rapPhim->chiNhanh->ten_chi_nhanh }}</div>
                                    <div><i class="fas fa-door-open"></i> {{ $datVe->suatChieu->phongChieu->ten_phong }}</div>
                                    <div><i class="fas fa-calendar"></i> {{ \Carbon\Carbon::parse($datVe->suatChieu->ngay_chieu)->format('d/m/Y') }}</div>
                                    <div><i class="fas fa-clock"></i> {{ \Carbon\Carbon::parse($datVe->suatChieu->bat_dau)->format('H:i') }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Seats -->
                        <div class="seats-info">
                            <strong>Ghế:</strong>
                            @foreach($datVe->gheNgois as $ghe)
                            {{ $ghe->ma_ghe }}{{ !$loop->last ? ', ' : '' }}
                            @endforeach
                        </div>
                    </div>

                    <!-- Price breakdown -->
                    <div class="price-breakdown">
                        <div class="price-item">
                            <span class="price-label">Vé ({{ $datVe->gheNgois->count() }} ghế)</span>
                            <span class="price-value">{{ number_format($tongTienGhe) }}đ</span>
                        </div>

                        @if($tongTienCombo > 0)
                        <div class="price-item">
                            <span class="price-label">Combo</span>
                            <span class="price-value">{{ number_format($tongTienCombo) }}đ</span>
                        </div>
                        @endif

                        @if($tongTienDoAn > 0)
                        <div class="price-item">
                            <span class="price-label">Đồ ăn & nước uống</span>
                            <span class="price-value">{{ number_format($tongTienDoAn) }}đ</span>
                        </div>
                        @endif

                        <div class="price-item total">
                            <span class="price-label">Tổng cộng</span>
                            <span class="price-value">{{ number_format($tongThanhTien) }}đ</span>
                        </div>
                    </div>

                    <!-- Order info -->
                    <div class="order-info">
                        <p><strong>Mã đặt vé:</strong> {{ $datVe->ma_dat_ve }}</p>
                        <p><strong>Thời gian đặt:</strong> {{ $datVe->created_at->format('d/m/Y H:i') }}</p>
                    </div>

                    <!-- Action buttons -->
                    <div class="action-buttons">
                        <button type="button" id="btn-pay" class="btn-payment" disabled>
                            <i class="fas fa-lock"></i> Thanh toán ngay
                        </button>
                        <a href="{{ route('client.thanh-toan.huy', $datVe->id) }}" class="btn-cancel">
                            <i class="fas fa-times"></i> Hủy đặt vé
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        // Handle payment method selection
        $('.payment-option').on('click', function() {
            $('.payment-option').removeClass('selected');
            $(this).addClass('selected');
            $(this).find('input[type="radio"]').prop('checked', true);

            // Enable payment button
            $('#btn-pay').prop('disabled', false);

            // Show/hide banking info
            const method = $(this).data('method');
            if (method === 'banking') {
                $('#banking-info').addClass('show');
            } else {
                $('#banking-info').removeClass('show');
            }

            // Update button text based on method
            updatePaymentButtonText(method);
        });

        // Update payment button text
        function updatePaymentButtonText(method) {
            const texts = {
                'momo': '<i class="fab fa-apple-pay"></i> Thanh toán với MoMo',
                'vnpay': '<i class="fas fa-credit-card"></i> Thanh toán với VNPay',
                'zalopay': '<i class="fas fa-mobile-alt"></i> Thanh toán với ZaloPay',
                'banking': '<i class="fas fa-university"></i> Xác nhận chuyển khoản',
                'cod': '<i class="fas fa-hand-holding-usd"></i> Đặt vé (thanh toán tại quầy)'
            };
            $('#btn-pay').html(texts[method] || '<i class="fas fa-lock"></i> Thanh toán ngay');
        }

        // Handle payment submission
        $('#btn-pay').on('click', function() {
            const selectedMethod = $('input[name="phuong_thuc_tt"]:checked').val();

            if (!selectedMethod) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Chưa chọn phương thức thanh toán',
                    text: 'Vui lòng chọn một phương thức thanh toán!',
                    confirmButtonColor: '#3f2b96'
                });
                return;
            }

            // Show loading
            $(this).prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Đang xử lý...');
            $('.payment-content').addClass('loading');

            // Submit payment
            $.ajax({
                url: '{{ route("client.thanh-toan.xu-ly") }}',
                method: 'POST',
                data: $('#payment-form').serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        if (response.payment_url) {
                            // Redirect to payment gateway
                            Swal.fire({
                                icon: 'success',
                                title: 'Chuyển hướng thanh toán',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.href = response.payment_url;
                            });
                        } else if (response.payment_method === 'banking') {
                            // Show banking info
                            Swal.fire({
                                icon: 'info',
                                title: 'Thông tin chuyển khoản',
                                html: `
                                <div style="text-align: left; padding: 20px;">
                                    <p><strong>Ngân hàng:</strong> ${response.banking_info.bank_name}</p>
                                    <p><strong>Số tài khoản:</strong> ${response.banking_info.account_number}</p>
                                    <p><strong>Tên tài khoản:</strong> ${response.banking_info.account_name}</p>
                                    <p><strong>Số tiền:</strong> ${formatCurrency(response.banking_info.amount)}đ</p>
                                    <p><strong>Nội dung:</strong> ${response.banking_info.content}</p>
                                </div>
                            `,
                                confirmButtonText: 'Đã chuyển khoản',
                                confirmButtonColor: '#3f2b96'
                            }).then(() => {
                                window.location.href = '{{ route("client.dat-ve.ket-qua", $datVe->id) }}';
                            });
                        } else if (response.redirect_url) {
                            // Direct redirect (COD)
                            Swal.fire({
                                icon: 'success',
                                title: 'Đặt vé thành công!',
                                text: response.message,
                                confirmButtonColor: '#3f2b96'
                            }).then(() => {
                                window.location.href = response.redirect_url;
                            });
                        }
                    } else {
                        showError(response.message);
                    }
                },
                error: function(xhr) {
                    const response = xhr.responseJSON;
                    showError(response?.message || 'Có lỗi xảy ra khi xử lý thanh toán!');
                },
                complete: function() {
                    // Reset loading state
                    $('.payment-content').removeClass('loading');
                    $('#btn-pay').prop('disabled', false);
                    updatePaymentButtonText($('input[name="phuong_thuc_tt"]:checked').val());
                }
            });
        });

        // Helper functions
        function showError(message) {
            Swal.fire({
                icon: 'error',
                title: 'Lỗi thanh toán',
                text: message,
                confirmButtonColor: '#3f2b96'
            });
        }

        function formatCurrency(amount) {
            return new Intl.NumberFormat('vi-VN').format(amount);
        }
    });

    // Copy to clipboard function
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(function() {
            Swal.fire({
                icon: 'success',
                title: 'Đã sao chép!',
                text: 'Thông tin đã được sao chép vào clipboard',
                timer: 1500,
                showConfirmButton: false
            });
        });
    }
</script>
@endsection