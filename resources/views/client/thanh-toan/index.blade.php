@extends('layouts.client')

@section('styles')
    <style>
        .time-order {
            font-size: 28px;
            font-weight: 700;
            color: #e74c3c;
            text-align: center;
            margin: 15px auto;
            padding: 12px;
            background-color: #fff3f3;
            border-radius: 8px;
            border: 2px solid #ffcdd2;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            max-width: 200px;
        }

        .time-order.warning {
            animation: blink 1s infinite;
            background-color: #ffe6e6;
            border-color: #ff8a80;
        }
    </style>
    @vite('resources/css/thanh-toan.css')
@endsection

@section('title', 'Thanh toán vé xem phim')
a
@section('content')
    <div class="thanh-toan-container">
        <div class="container">
            <!-- Payment Header -->
            <div class="payment-header">
                <h2><i class="fas fa-credit-card"></i> Thanh toán vé xem phim</h2>
                <p>Vui lòng chọn phương thức thanh toán để hoàn tất đặt vé</p>
            </div>

            <div class="payment-container">
                <!-- Left side - Payment methods -->
                <div class="payment-methods">
                    <h3><i class="fas fa-wallet"></i> Chọn phương thức thanh toán</h3>

                    <form id="payment-form">
                        @csrf
                        <input type="hidden" name="dat_ve_id" value="{{ $datVe->id }}">

                        <!-- ZaloPay - Priority -->
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

                        <!-- VNPay -->
                        <div class="payment-option" data-method="vnpay">
                            <input type="radio" name="phuong_thuc_tt" value="vnpay" id="vnpay">
                            <div class="payment-icon vnpay">
                                <i class="fas fa-credit-card"></i>
                            </div>
                            <div class="payment-details">
                                <h4>VNPay</h4>
                                <p>Thanh toán qua thẻ ATM nội địa, Visa, MasterCard</p>
                            </div>
                        </div>

                        <!-- MoMo -->
                        <div class="payment-option" data-method="momo">
                            <input type="radio" name="phuong_thuc_tt" value="momo" id="momo">
                            <div class="payment-icon momo">
                                <i class="fab fa-apple-pay"></i>
                            </div>
                            <div class="payment-details">
                                <h4>Ví MoMo</h4>
                                <p>Thanh toán nhanh với ví điện tử MoMo</p>
                            </div>
                        </div>

                        <!-- Banking -->
                        <div class="payment-option" data-method="banking">
                            <input type="radio" name="phuong_thuc_tt" value="banking" id="banking">
                            <div class="payment-icon banking">
                                <i class="fas fa-university"></i>
                            </div>
                            <div class="payment-details">
                                <h4>Chuyển khoản ngân hàng</h4>
                                <p>Chuyển khoản trực tiếp qua ngân hàng</p>
                            </div>
                        </div>

                        <!-- COD -->
                        <div class="payment-option" data-method="cod">
                            <input type="radio" name="phuong_thuc_tt" value="cod" id="cod">
                            <div class="payment-icon cod">
                                <i class="fas fa-hand-holding-usd"></i>
                            </div>
                            <div class="payment-details">
                                <h4>Thanh toán tại quầy</h4>
                                <p>Thanh toán trực tiếp tại rạp trước giờ chiếu 30 phút</p>
                            </div>
                        </div>
                    </form>

                    <!-- Banking info (hidden by default) -->
                    <div class="banking-info" id="banking-info">
                        <h4><i class="fas fa-info-circle"></i> Thông tin chuyển khoản</h4>
                        <div class="bank-detail">
                            <strong>Ngân hàng:</strong>
                            <span>Techcombank</span>
                        </div>
                        <div class="bank-detail">
                            <strong>Số tài khoản:</strong>
                            <span>19036766589018 <button type="button" class="copy-btn"
                                    onclick="copyToClipboard('19036766589018')">Copy</button></span>
                        </div>
                        <div class="bank-detail">
                            <strong>Tên tài khoản:</strong>
                            <span>CONG TY TNHH POLYFLIX</span>
                        </div>
                        <div class="bank-detail">
                            <strong>Nội dung:</strong>
                            <span>POLYFLIX {{ $datVe->ma_dat_ve }} <button type="button" class="copy-btn"
                                    onclick="copyToClipboard('POLYFLIX {{ $datVe->ma_dat_ve }}')">Copy</button></span>
                        </div>
                        <div class="bank-detail">
                            <strong>Số tiền:</strong>
                            <span>{{ number_format($tongThanhTien) }}đ</span>
                        </div>
                    </div>

                    <!-- Payment Button -->
                    <button type="button" id="btn-pay" class="payment-button" disabled>
                        <i class="fas fa-lock"></i> Chọn phương thức thanh toán
                    </button>
                </div>

                <!-- Right side - Order summary -->
                <div class="order-summary">
                    <h3><i class="fas fa-ticket-alt"></i> Thông tin đặt vé</h3>

                    <!-- Movie summary -->
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

                        <div class="seats-info">
                            <strong><i class="fas fa-couch"></i> Ghế đã chọn:</strong>
                            @foreach ($datVe->gheNgois as $ghe)
                                {{ $ghe->ma_ghe }}{{ !$loop->last ? ', ' : '' }}
                            @endforeach
                        </div>
                    </div>

                    <!-- Price breakdown -->
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

                            <p><strong>Mã đặt vé:</strong> {{ $datVe->ma_dat_ve }}</p>
                            <p><strong>Thời gian đặt:</strong> {{ $datVe->created_at->format('d/m/Y H:i') }}</p>
                        </div> --}}

                        <!-- Action buttons -->
                        <div class="action-buttons">
                            {{-- <button type="button" id="btn-pay" class="btn-payment" disabled>
                                <i class="fas fa-lock"></i> Thanh toán ngay
                            </button> --}}

                            <!-- Hủy đặt vé - Form POST -->
                            <form method="POST" action="{{ route('client.thanh-toan.huy', $datVe->id) }}"
                                style="display: flex;">
                                @csrf
                                <button type="submit" class="btn-cancel" onclick="return confirmCancel()">
                                    <i class="fas fa-times"></i> Hủy
                                </button>

                            </form>
                            @php
                                $maVe = $datVe->ma_dat_ve;
                            @endphp
                            <button class="btn-confirm" onclick="kiemTraThanhToan('{{ $maVe }}')">
                                Kiểm tra thanh toán
                            </button>
                            <span><strong>Tổng cộng</strong></span>
                            <span><strong>{{ number_format($tongThanhTien) }}đ</strong></span>
                        </div>
                    </div>

                    <!-- Order info -->
                    <div class="movie-summary">
                        <p><strong>Mã đặt vé:</strong> {{ $datVe->ma_dat_ve }}</p>
                        <p><strong>Thời gian đặt:</strong> {{ $datVe->created_at->format('d/m/Y H:i') }}</p>
                    </div>

                    <!-- Cancel button -->
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

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function kiemTraThanhToan(maDatVe) {

            $.ajax({
                url: "/check-payment-status",
                method: "GET",
                data: {
                    ma_dat_ve: maDatVe
                },
                success: function(res) {
                    console.log("Kết quả từ server:", res);

                    if (res.success) {
                        window.location.href = `/dat-ve/ket-qua/${maDatVe}`;
                    } else {
                        alert("Không tìm thấy giao dịch: " + res.message);
                    }
                        const method = $(this).data('method');
                        // Show/hide banking info
                        if (method === 'banking') {
                            $('#banking-info').addClass('show');
                        } else {
                            $('#banking-info').removeClass('show');
                        }

                        // Update button text
                        updatePaymentButtonText(method);
                    });
                    alert("Đã xảy ra lỗi hệ thống khi kiểm tra thanh toán.");
                }
            });
        }
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

                    // Update payment button text
                    function updatePaymentButtonText(method) {
                        const texts = {
                            'zalopay': '<i class="fas fa-mobile-alt"></i> Thanh toán với ZaloPay',
                            'vnpay': '<i class="fas fa-credit-card"></i> Thanh toán với VNPay',
                            'momo': '<i class="fab fa-apple-pay"></i> Thanh toán với MoMo',
                            'banking': '<i class="fas fa-university"></i> Xác nhận chuyển khoản',
                            'cod': '<i class="fas fa-hand-holding-usd"></i> Đặt vé (thanh toán tại quầy)'
                        };
                        $('#btn-pay').html(texts[method] || '<i class="fas fa-lock"></i> Thanh toán ngay');
                    }
                    $(document).ready(function() {

                            Swal.fire({
                                icon: 'warning',
                                title: 'Chưa chọn phương thức thanh toán',
                                text: 'Vui lòng chọn một phương thức thanh toán!',
                                confirmButtonColor: '#667eea'
                            });
                            return;
                        }

                        // Show loading
                        $(this).prop('disabled', true).html(
                            '<span class="payment-loading"><span class="spinner"></span> Đang xử lý...</span>');

                        // Submit payment
                        $.ajax({
                            url: '{{ route('client.thanh-toan.xu-ly') }}',
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
                                            confirmButtonColor: '#667eea'
                                        }).then(() => {
                                            window.location.href =
                                                '{{ route('client.dat-ve.ket-qua', $datVe->id) }}';
                                        });
                                    } else if (response.redirect_url) {
                                        // Direct redirect (COD)
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Đặt vé thành công!',
                                            text: response.message,
                                            confirmButtonColor: '#667eea'
                                        }).then(() => {
                                            window.location.href = response.redirect_url;
                                        });

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
                                        updatePaymentButtonText($('input[name="phuong_thuc_tt"]:checked')
                                            .val());
                                    }

                            },
                            error: function(xhr) {
                                const response = xhr.responseJSON;
                                showError(response?.message || 'Có lỗi xảy ra khi xử lý thanh toán!');
                            },
                            complete: function() {
                                // Reset loading state
                                const selectedMethod = $('input[name="phuong_thuc_tt"]:checked').val();
                                if (selectedMethod) {
                                    $('#btn-pay').prop('disabled', false);
                                    updatePaymentButtonText(selectedMethod);
                                } else {
                                    $('#btn-pay').prop('disabled', true).html(
                                        '<i class="fas fa-lock"></i> Chọn phương thức thanh toán');
                                }
                            }
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
                // Helper functions
                function showError(message) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi thanh toán',
                        text: message,
                        confirmButtonColor: '#667eea'

                    });
                }

                // Confirm cancel booking
                function confirmCancel() {
                    const confirmed = confirm(
                        'Bạn có chắc chắn muốn hủy đặt vé này không?\n\nLưu ý: Việc hủy vé không thể hoàn tác!');
                    if (confirmed) {
                        // Reset timer khi người dùng xác nhận hủy
                        localStorage.removeItem('order_start_time');
                        const timerDisplay = document.querySelector('.time-order');
                        if (timerDisplay) {
                            timerDisplay.textContent = '05:00';
                            timerDisplay.classList.remove('warning');
                        }
                        const btnPay = document.getElementById('btn-pay');
                        if (btnPay) {
                            btnPay.disabled = false;
                        }
                    }
                    return confirmed;
                }

                document.addEventListener('DOMContentLoaded', function() {
                    const ORDER_KEY = 'order_start_time';
                    const timerDisplay = document.querySelector('.time-order');
                    const btnPay = document.getElementById('btn-pay');
                    const timeLimit = 5 * 60; // 5 phút = 300 giây
                    let timer;

                    // Hàm reset timer
                    function resetTimer() {
                        clearInterval(timer);
                        localStorage.removeItem(ORDER_KEY);
                        if (timerDisplay) {
                            timerDisplay.textContent = '05:00';
                            timerDisplay.classList.remove('warning');
                        }
                        if (btnPay) {
                            btnPay.disabled = false;
                        }
                    }

                    // Kiểm tra và thiết lập thời gian bắt đầu từ localStorage
                    function getStartTime() {
                        let startTime = localStorage.getItem(ORDER_KEY);
                        if (!startTime) {
                            startTime = new Date().getTime();
                            localStorage.setItem(ORDER_KEY, startTime);
                        }
                        return parseInt(startTime);
                    }

                    // Lấy thời điểm bắt đầu
                    const startTime = getStartTime();

                    // Hàm format thời gian
                    function formatTime(seconds) {
                        const minutes = Math.floor(seconds / 60);
                        const remainingSeconds = seconds % 60;
                        return `${String(minutes).padStart(2, '0')}:${String(remainingSeconds).padStart(2, '0')}`;
                    }

                    // Hàm cập nhật style của timer
                    function updateTimerStyle(seconds) {
                        if (seconds <= 60) {
                            timerDisplay.classList.add('warning');
                        } else {
                            timerDisplay.classList.remove('warning');
                        }
                    }

                    // Hàm tự động hủy đặt vé
                    function autoCancel() {
                        const cancelForm = document.querySelector('form');
                        if (cancelForm) {
                            const autoCancel = document.createElement('input');
                            autoCancel.type = 'hidden';
                            autoCancel.name = 'auto_cancel';
                            autoCancel.value = '1';
                            cancelForm.appendChild(autoCancel);
                            resetTimer();
                            cancelForm.submit();
                        }
                    }

                    // Hàm đếm ngược
                    function countdown() {
                        const currentTime = new Date().getTime();
                        const elapsedSeconds = Math.floor((currentTime - startTime) / 1000);
                        const timeLeft = timeLimit - elapsedSeconds;

                        if (timeLeft <= 0) {
                            clearInterval(timer);
                            timerDisplay.textContent = '00:00';
                            if (btnPay) btnPay.disabled = true;
                            autoCancel();
                            return;
                        }

                        timerDisplay.textContent = formatTime(timeLeft);
                        updateTimerStyle(timeLeft);
                        if (btnPay) btnPay.disabled = false;
                    }

                    // Kiểm tra ngay khi load trang xem đã hết thời gian chưa
                    const currentTime = new Date().getTime();
                    const elapsedSeconds = Math.floor((currentTime - startTime) / 1000);

                    if (elapsedSeconds >= timeLimit) {
                        timerDisplay.textContent = '00:00';
                        if (btnPay) btnPay.disabled = true;
                        autoCancel();
                    } else {
                        // Khởi tạo timer và cập nhật mỗi giây
                        countdown();
                        timer = setInterval(countdown, 1000);

                        // Xử lý khi user rời trang
                        window.addEventListener('beforeunload', function() {
                            clearInterval(timer);
                        });

                        // Xử lý khi tab không active
                        document.addEventListener('visibilitychange', function() {
                            if (document.hidden) {
                                clearInterval(timer);
                            } else {
                                countdown();
                                timer = setInterval(countdown, 1000);
                            }
                        });

                        // Xử lý nút thanh toán
                        if (btnPay) {
                            btnPay.addEventListener('click', function() {
                                resetTimer();
                            });
                        }
                    }
                });
            }
    </script>
@endsection
