@extends('layouts.client')

@section('styles')
    <style>
        {!! collect($loaiGhes)->map(function ($loai) {
                $class = \Illuminate\Support\Str::slug($loai->ten_loai_ghe);
                return ".ghe-chieu.{$class} { background-color: {$loai->chu_thich_mau_ghe}; }";
            })->implode("\n") !!} .time-seat {
            font-size: 20px;
            font-weight: bold;
            color: #dc3545;
            margin: 10px 0;
        }
    </style>

    @vite('resources/css/trang-chu.css')
    @vite('resources/css/dat-ve.css')
@endsection

@section('title', 'Đặt vé xem phim')

@section('content')

    @if (session('success'))
        <div class="alert alert-success">
            {{ e(session('success')) }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ e(session('error')) }}
        </div>
    @endif

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
                                    <div class="ghe-chieu
                                    {{-- Nếu ghế đang bảo trì --}}
                                    {{ $ghe->trang_thai == 'bao_tri' ? 'maintenance' : '' }}

                                    {{-- Nếu ghế đang được chính người dùng này chọn --}}
                                    {{ $ghe->trang_thai == 'dang_chon' ? 'selected' : '' }}

                                    {{-- Nếu ghế đang được người khác chọn (giữ tạm thời trong Redis) --}}
                                    {{ $ghe->dang_duoc_chon && $ghe->trang_thai != 'dang_chon' ? 'selected-by-other' : '' }}

                                    {{-- Nếu không rơi vào các trạng thái đặc biệt → available --}}
                                    {{ !$ghe->da_dat && !$ghe->dang_duoc_chon && $ghe->trang_thai !== 'bao_tri' && $ghe->trang_thai !== 'dang_chon' ? 'available' : '' }}

                                    {{-- Loại ghế (VIP, thường...) --}}
                                    {{ \Illuminate\Support\Str::slug($ghe->loaiGhe->ten_loai_ghe ?? 'thuong') }}

                                    {{-- Ghế đôi --}}
                                    {{ $ghe->loaiGhe->id == 12 ? 'ghe-doi' : '' }}"
                                        data-seat-id="{{ $ghe->id }}" data-seat-name="{{ $ghe->ma_ghe }}"
                                        data-ten-loai-ghe="{{ $ghe->loaiGhe->ten_loai_ghe ?? 'Thường' }}"
                                        data-phu-thu-loai-phong="{{ $ghe->phu_thu_loai_phong }}"
                                        data-phu-thu-loai-ghe="{{ $ghe->phu_thu_loai_ghe }}"
                                        data-phu-thu-rap-phim="{{ $ghe->phu_thu_rap_phim }}"
                                        data-seat-type-id="{{ $ghe->loaiGhe->id }}"
                                        @if ($ghe->trang_thai == 'bao_tri' || $ghe->da_dat) disabled @endif>
                                        {{-- Hiển thị x nếu ghế đang bảo trì --}}
                                        {{ $ghe->trang_thai == 'bao_tri' ? 'x' : $ghe->ma_ghe }}
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
                            <div class="seat-demo bg-available"></div><span>Ghế trống</span>
                        </div>
                        <div class="legend-item">
                            <div class="seat-demo bg-selected"></div><span>Đã chọn</span>
                        </div>
                        <div class="legend-item">
                            <div class="seat-demo seat-disabled"><i class="fas fa-times text-danger"></i></div><span>Không
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
                <h3>Chọn đồ ăn & nước uống</h3>
                <div class="food-tabs">
                    <button class="tab-btn active" data-tab="do-an">Đồ ăn</button>
                    <button class="tab-btn" data-tab="combo">Combo</button>
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
                    <div class="total-price">
                        <h4>Tổng tiền: <span id="total-amount">0đ</span></h4>
                    </div>
                    <div class="time-seat">05:00</div>
                    <button type="button" id="btn-dat-ve" class="btn-primary" disabled>Đặt vé</button>
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
    <script src="http://localhost:6001/socket.io/socket.io.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.11.3/echo.iife.js"></script>

    <script>
        // Khởi tạo Laravel Echo với socket.io
        window.Echo = new Echo({
            broadcaster: 'socket.io',
            host: window.location.hostname + ':6001',
        });

        // Khi có người chọn ghế -> tất cả client khác sẽ nhận được sự kiện này
        window.Echo.channel('ghe-duoc-chon')
            .listen('.ghe-duoc-chon', function(e) {
                console.log('Đã nhận được sự kiện ghe-duoc-chon:', e);
                const ghe = document.querySelector(`.ghe-chieu[data-seat-id="${e.gheId}"]`);
                const currentUserId = parseInt(document.querySelector('meta[name="user-id"]').content);

                if (ghe && e.userId !== currentUserId) {
                    // Kiểm tra xem ghế có phải là ghế đôi
                    const isCoupleSeat = ghe.classList.contains("ghe-doi");
                    if (isCoupleSeat) {
                        const seatName = ghe.getAttribute("data-seat-name");
                        const seatNumber = parseInt(seatName.match(/\d+/)[0]);
                        const row = seatName.match(/[A-Za-z]+/)[0];
                        const partnerSeatNumber = seatNumber % 2 === 1 ? seatNumber + 1 : seatNumber - 1;
                        const partnerSeatName = row + partnerSeatNumber;
                        const partnerSeat = document.querySelector(`.ghe-chieu[data-seat-name="${partnerSeatName}"]`);

                        // Cập nhật cả hai ghế
                        [ghe, partnerSeat].forEach((seat) => {
                            if (seat) {
                                seat.classList.add("selected-by-other");
                                seat.disabled = true;
                            }
                        });
                    } else {
                        ghe.classList.add("selected-by-other");
                        ghe.disabled = true;
                    }

                    const thongBao = document.getElementById('thong-bao-ghe');
                    if (thongBao) {
                        thongBao.innerText = `⚠️ Ghế số ${e.gheId} vừa được người khác chọn. Vui lòng chọn ghế khác.`;
                        thongBao.style.display = 'block';

                        setTimeout(() => {
                            thongBao.style.display = 'none';
                        }, 5000);
                    }
                }
            });

        // Khi người dùng hủy chọn ghế
        window.Echo.channel('ghe-bi-huy')
            .listen('.ghe-bi-huy', function(e) {
                const ghe = document.querySelector(`.ghe-chieu[data-seat-id="${e.gheId}"]`);
                const currentUserId = parseInt(document.querySelector('meta[name="user-id"]').content);

                if (ghe && e.userId !== currentUserId) {
                    const isCoupleSeat = ghe.classList.contains("ghe-doi");
                    if (isCoupleSeat) {
                        const seatName = ghe.getAttribute("data-seat-name");
                        const seatNumber = parseInt(seatName.match(/\d+/)[0]);
                        const row = seatName.match(/[A-Za-z]+/)[0];
                        const partnerSeatNumber = seatNumber % 2 === 1 ? seatNumber + 1 : seatNumber - 1;
                        const partnerSeatName = row + partnerSeatNumber;
                        const partnerSeat = document.querySelector(`.ghe-chieu[data-seat-name="${partnerSeatName}"]`);

                        // Cập nhật cả hai ghế
                        [ghe, partnerSeat].forEach((seat) => {
                            if (seat) {
                                seat.classList.remove("selected-by-other");
                                seat.disabled = false;
                            }
                        });
                    } else {
                        ghe.classList.remove("selected-by-other");
                        ghe.disabled = false;
                    }
                }
            });

        // Khi tải lại trang, vô hiệu hóa các ghế đã bị chọn bởi người khác
        document.querySelectorAll('.ghe-chieu.selected-by-other').forEach(ghe => {
            ghe.disabled = true;
        });

        // Gắn sự kiện click vào từng ghế
        document.querySelectorAll('.ghe-chieu').forEach(ghe => {
            ghe.addEventListener('click', function() {
                const gheId = this.getAttribute('data-seat-id');
                const gheElement = this;

                if (gheElement.classList.contains('selected-by-other')) {
                    alert('Ghế này đã được người khác chọn!');
                    gheElement.classList.add('selected-by-other');
                    gheElement.disabled = true;
                    return;
                }

                fetch('/chon-ghe', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        },
                        body: JSON.stringify({
                            ghe_id: gheId
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            if (response.status === 409) {
                                return response.json().then(data => {
                                    alert(data.message);
                                    // Xử lý ghế đôi
                                    if (gheElement.classList.contains('ghe-doi')) {
                                        const seatName = gheElement.getAttribute(
                                            "data-seat-name");
                                        const seatNumber = parseInt(seatName.match(/\d+/)[0]);
                                        const row = seatName.match(/[A-Za-z]+/)[0];
                                        const partnerSeatNumber = seatNumber % 2 === 1 ?
                                            seatNumber + 1 : seatNumber - 1;
                                        const partnerSeatName = row + partnerSeatNumber;
                                        const partnerSeat = document.querySelector(
                                            `.ghe-chieu[data-seat-name="${partnerSeatName}"]`
                                        );

                                        // Cập nhật cả hai ghế
                                        [gheElement, partnerSeat].forEach((seat) => {
                                            if (seat) {
                                                seat.classList.remove('selected',
                                                    'selected-by-me');
                                                seat.classList.add('selected-by-other');
                                                seat.disabled = true;
                                            }
                                        });
                                    } else {
                                        gheElement.classList.remove('selected',
                                            'selected-by-me');
                                        gheElement.classList.add('selected-by-other');
                                        gheElement.disabled = true;
                                    }
                                });
                            } else {
                                throw new Error("Đã xảy ra lỗi không xác định");
                            }
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data && data.success) {
                            console.log(' Đã chọn ghế thành công!');
                            gheElement.classList.add('selected-by-me');
                            gheElement.disabled = false;
                        }
                    })
                    .catch(error => {
                        console.error('Lỗi khi chọn ghế:', error);
                    });
            });
        });

        let countdownTimer;

        function startTimer(duration, display) {
            if (countdownTimer) {
                clearInterval(countdownTimer);
            }

            let timer = duration;
            let startTime = Date.now();
            let endTime = startTime + (timer * 1000);

            // Lưu thời gian kết thúc vào localStorage
            localStorage.setItem('timerEndTime', endTime);

            function updateTimer() {
                let currentTime = Date.now();
                let remainingTime = Math.ceil((endTime - currentTime) / 1000);

                if (remainingTime <= 0) {
                    clearInterval(countdownTimer);
                    handleTimeout();
                    return;
                }

                let minutes = parseInt(remainingTime / 60, 10);
                let seconds = parseInt(remainingTime % 60, 10);

                minutes = minutes < 10 ? "0" + minutes : minutes;
                seconds = seconds < 10 ? "0" + seconds : seconds;

                display.textContent = minutes + ":" + seconds;
            }

            updateTimer();
            countdownTimer = setInterval(updateTimer, 1000);
        }

        function handleTimeout() {
            // Clear localStorage
            localStorage.removeItem('timerEndTime');

            // Bỏ chọn hết ghế
            const selectedSeatsList = document.getElementById('selected-seats-list');
            if (selectedSeatsList) {
                selectedSeatsList.textContent = "Chưa chọn ghế";
            }

            // Disable nút đặt vé
            const btnDatVe = document.getElementById('btn-dat-ve');
            if (btnDatVe) {
                btnDatVe.disabled = true;
            }

            // Hiển thị thông báo
            alert('Đã hết thời gian giữ ghế!');

            // Chuyển về trang home
            window.location.href = '/';
        }

        function clearTimer() {
            if (countdownTimer) {
                clearInterval(countdownTimer);
            }
            localStorage.removeItem('timerEndTime');

            const timeDisplay = document.querySelector('.time-seat');
            if (timeDisplay) {
                timeDisplay.textContent = "05:00";
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const selectedSeatsList = document.getElementById('selected-seats-list');
            const timeDisplay = document.querySelector('.time-seat');
            const btnDatVe = document.getElementById('btn-dat-ve');

            // Thêm event listener cho nút đặt vé
            if (btnDatVe) {
                btnDatVe.addEventListener('click', function() {
                    clearTimer(); // Xóa timer khi bấm nút đặt vé
                });
            }

            // Kiểm tra xem có timer đang chạy từ trước không
            const savedEndTime = localStorage.getItem('timerEndTime');
            if (savedEndTime) {
                const currentTime = Date.now();
                const remainingTime = Math.ceil((savedEndTime - currentTime) / 1000);

                if (remainingTime > 0) {
                    startTimer(remainingTime, timeDisplay);
                } else {
                    handleTimeout();
                }
            }

            // Observer để theo dõi thay đổi trong selected-seats-list
            const selectedSeatsObserver = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (selectedSeatsList && timeDisplay &&
                        selectedSeatsList.textContent !== "Chưa chọn ghế" &&
                        !localStorage.getItem('timerEndTime')) {
                        startTimer(5 * 60, timeDisplay);
                    }
                });
            });

            if (selectedSeatsList) {
                selectedSeatsObserver.observe(selectedSeatsList, {
                    childList: true,
                    characterData: true,
                    subtree: true
                });
            }
        });

        // Xử lý khi rời trang
        window.addEventListener('beforeunload', function() {
            const timeDisplay = document.querySelector('.time-seat');
            if (timeDisplay &&
                timeDisplay.textContent !== "05:00" &&
                timeDisplay.textContent !== "Hết giờ!") {
                // Timer đang chạy, đã được lưu trong localStorage
            }
        });
    </script>

@endsection
