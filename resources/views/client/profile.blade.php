@extends('layouts.client')

@section('styles')
    <style>
        body {
            background-color: #e0f2f1;
            font-family: "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        }

        .profile-wrapper {
            max-width: 1200px;
            margin: 40px auto;
            display: flex;
            gap: 30px;
        }

        .left-panel,
        .right-panel {
            background: #ffffff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }

        .left-panel {
            width: 30%;
            height: 50%;
        }

        .right-panel {
            flex: 1;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-avatar {
            width: 64px;
            height: 64px;
            background: #cfd8dc;
            border-radius: 50%;
        }

        .user-name1 {
            font-weight: 600;
            color: #004d40;
        }

        .stars {
            font-size: 14px;
            color: #ff9800;
        }

        .spending-progress {
            margin-top: 30px;
        }

        .progress-bar {
            height: 8px;
            background: #b2dfdb;
            border-radius: 20px;
            margin: 15px 0;
            position: relative;
        }

        .progress-fill {
            width: 0%;
            height: 100%;
            background: #009688;
            border-radius: 20px;
        }

        .milestone-labels {
            display: flex;
            justify-content: space-between;
            font-size: 14px;
            color: #455a64;
            margin-top: 10px;
        }

        .support-info {
            font-size: 14px;
            margin-top: 20px;
            color: #004d40;
        }

        .support-info a {
            color: #00796b;
            text-decoration: none;
        }

        .tab-nav {
            display: flex;
            gap: 30px;
            border-bottom: 2px solid #cfd8dc;
            margin-bottom: 20px;
        }

        .tab-nav a {
            padding: 10px 0;
            font-weight: 500;
            text-decoration: none;
            color: #607d8b;
            position: relative;
        }

        .tab-nav a.active {
            color: #004d40;
            border-bottom: 3px solid #009688;
        }

        .form-row {
            display: flex;
            gap: 20px;
            margin-bottom: 10px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 5px;
            display: block;
            color: #37474f;
        }

        .form-group input {
            width: 100%;
            height: 40px;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #b2dfdb;
            background: #f1f8f7;
            color: #263238;
        }

        .form-group input[disabled] {
            background: #e0e0e0;
            color: #607d8b;
        }

        .form-group .change-link {
            color: #00695c;
            font-size: 13px;
            margin-left: 10px;
            cursor: pointer;
        }

        .form-actions {
            text-align: right;
        }

        .form-actions button {
            background: #009688;
            color: #fff;
            padding: 10px 24px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.2s ease;
        }

        .form-actions button:hover {
            background: #00796b;
        }

        .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 16px;
            color: #607d8b;
        }

        .password-wrapper {
            position: relative;
        }

        .swal2-icon.swal2-error {
            background-color: transparent !important;
            color: red;
        }

        .custom-toast {
            padding: 16px 24px;
            font-size: 14px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        .custom-toast {
            padding: 16px 24px;
            font-size: 14px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        .swal2-icon.swal2-success {
            color: #22c55e;
            border-color: #22c55e;
        }

        hr {
            margin-bottom: 20px;
        }

        .input-icon-wrapper {
            position: relative;
        }

        .input-icon-wrapper i {
            position: absolute;
            top: 50%;
            left: 12px;
            transform: translateY(-50%);
            color: #607d8b;
            font-size: 16px;
            pointer-events: none;
        }

        .input-icon-wrapper input {
            padding-left: 36px !important;
            /* make room for icon */
        }

        .user-avatar {
            width: 95px;
            height: 95px;
            border-radius: 50%;
            overflow: hidden;
            border: 3px solid #4CAF50;
            /* Xanh lá viền */
            cursor: pointer;
            position: relative;
            transition: box-shadow 0.3s ease;
        }

        .user-avatar:hover {
            box-shadow: 0 0 10px rgba(76, 175, 80, 0.7);
        }

        .user-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        #avatarUpload {
            display: none;
        }

        .avatar-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4);
            /* lớp tối nhẹ */
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: 0.3s ease;
        }

        .user-avatar:hover .avatar-overlay {
            opacity: 1;
        }

        .avatar-overlay i {
            font-size: 28px;
            color: #74C0FC;
            animation-duration: 2s;
            /* điều chỉnh bounce chậm hơn nếu cần */
        }
    </style>
@endsection

@section('content')
    <div class="profile-wrapper">
        {{-- LEFT PANEL --}}
        <div class="left-panel">
            <div class="user-info">
                <form id="avatarForm" enctype="multipart/form-data">
                    <label for="avatarUpload">
                        <div class="user-avatar">
                            <img id="avatarPreview"
                                src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('images/default.png') }}"
                                alt="Avatar">
                            <div class="avatar-overlay"><i class="fa-solid fa-camera fa-bounce" style="color: #74C0FC;"></i>
                            </div>
                        </div>
                    </label>
                    <input type="file" id="avatarUpload" name="avatar" accept="image/*">
                </form>
                <div>
                    <div class="user-name1">🏅 {{ Auth::user()->name }}</div>
                    <div class="stars">{{ $tenCapBac }}</div>
                </div>
            </div>

            <div class="spending-progress">
                <p>
                    <strong style="color: #004d40;">Tổng tiền chi tiêu:</strong>
                    <span style="color: #f97316;">
                        {{ number_format($tongChiTieu, 0, ',', '.') }}₫
                    </span>
                </p>

                {{-- Thanh tiến độ --}}
                <div class="progress-bar"
                    style="height: 10px; background-color: #b2dfdb; border-radius: 10px; overflow: hidden;">
                    <div class="progress-fill"
                        style="height: 100%; width: {{ $phanTramChiTieu }}%; background-color: #ef4444; transition: width 0.5s;">
                    </div>
                </div>

                {{-- Các mốc --}}
                <div class="milestone-labels" style="display: flex; justify-content: space-between; margin-top: 5px;">
                    @foreach ($milestones as $moc)
                        <span>{{ number_format($moc, 0, ',', '.') }} đ</span>
                    @endforeach
                </div>
            </div>

            <div class="support-info">
                <p>HOTLINE hỗ trợ: <a href="tel:19002224">19002224</a> (9:00 - 22:00)</p>
                <p>Email: <a href="mailto:polyflixteam@gmail.com">polyflixteam@gmail.com</a></p>
            </div>
        </div>

        {{-- RIGHT PANEL --}}
        <div class="right-panel">
            <div class="tab-nav">
                <a href="#" class="tab-link active" data-tab="profile-tab">Thông Tin Cá Nhân</a>
                <a href="#" class="tab-link" data-tab="history-tab">Lịch Sử Đặt Vé</a>
                <a href="#" class="tab-link" data-tab="history-point-tab">Lịch Sử Điểm</a>
            </div>

            {{-- Tab Thông Tin Cá Nhân --}}
            <div id="profile-tab" class="tab-content active">
                <form method="POST" action="{{ route('updatePassword') }}">
                    @csrf
                    <div class="form-row">
                        <div class="form-group" style="flex: 1;">
                            <label>Họ và Tên</label>
                            <div class="input-icon-wrapper">
                                <i class="fa-solid fa-user"></i>
                                <input type="text" value="{{ Auth::user()->name }}" disabled>
                            </div>
                        </div>

                        <div class="form-group" style="flex: 1;">
                            <label>Ngày sinh</label>
                            <div class="input-icon-wrapper">
                                <i class="fa-solid fa-calendar-days"></i>
                                <input type="date" value="{{ Auth::user()->ngay_sinh ?? '' }}" disabled>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group" style="flex: 1;">
                            <label>Email</label>
                            <div class="input-icon-wrapper">
                                <i class="fa-solid fa-envelope"></i>
                                <input type="email" value="{{ Auth::user()->email }}" disabled>
                            </div>
                        </div>

                        <div class="form-group" style="flex: 1;">
                            <label>Số điện thoại</label>
                            <div class="input-icon-wrapper">
                                <i class="fa-solid fa-phone"></i>
                                <input type="text" value="{{ Auth::user()->so_dien_thoai ?? '' }}" disabled>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="form-group">
                        <label>Mật khẩu cũ</label>
                        <div class="password-wrapper">
                            <input type="password" name="current_password">
                            <span class="toggle-password" onclick="togglePassword(this)">
                                <i class="fa-solid fa-eye" style="color: #B197FC;"></i>
                            </span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Mật khẩu mới</label>
                        <div class="password-wrapper">
                            <input type="password" name="new_password" value="{{ old('new_password') }}">
                            <span class="toggle-password" onclick="togglePassword(this)">
                                <i class="fa-solid fa-eye" style="color: #B197FC;"></i>
                            </span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Xác nhận mật khẩu</label>
                        <div class="password-wrapper">
                            <input type="password" name="confirm_password" value="{{ old('confirm_password') }}">
                            <span class="toggle-password" onclick="togglePassword(this)">
                                <i class="fa-solid fa-eye" style="color: #B197FC;"></i>
                            </span>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit">Cập nhật</button>
                    </div>
                </form>
            </div>

            {{-- Tab Lịch Sử Giao Dịch --}}
            <div id="history-tab" class="tab-content" style="display: none;">
                <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
                    <thead>
                        <tr style="background: #009688; color: #fff;">
                            <th style="padding: 10px; text-align: left;">Ngày</th>
                            <th style="padding: 10px; text-align: left;">Mã vé</th>
                            <th style="padding: 10px; text-align: left;">Phim</th>
                            <th style="padding: 10px; text-align: left;">Trạng thái</th>
                            <th style="padding: 10px; text-align: left;">Số tiền</th>
                            <th style=" text-align: left;">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($donDatVeDaThanhToan as $ve)
                            <tr style="background: #f1f8f7; color: #004d40;">
                                <td style="padding: 10px;">{{ \Carbon\Carbon::parse($ve->thanh_toan)->format('d/m/Y') }}
                                </td>
                                <td style="padding: 10px;">{{ $ve->ma_dat_ve ?? '---' }}</td>
                                <td style="padding: 10px;">
                                    Đặt vé phim "{{ $ve->suatChieu->phim->ten_phim ?? 'N/A' }}"
                                </td>
                                <td style="padding: 10px;">Chưa xuất vé</td>
                                <td style="padding: 10px; color: #f97316;">
                                    {{ number_format($ve->tong_tien, 0, ',', '.') }}
                                    đ
                                </td>
                                <td>
                                    <a href="{{ route('dat-ve.chi-tiet', $ve->id) }}"
                                        style="background: #009688; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none;">
                                        <i class="fas fa-eye me-1"></i>Xem chi tiết
                                    </a>

                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="padding: 10px; color:black; text-align: center;">Không có giao
                                    dịch nào</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Tab Lịch Sử Điểm --}}
            <div id="history-point-tab" class="tab-content" style="display: none;">
                <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
                    <thead>
                        <tr style="background: #009688; color: #fff;">
                            <th style="padding: 10px; text-align: left;">#</th>
                            <th style="padding: 10px; text-align: left;">Thay đổi</th>
                            <th style="padding: 10px; text-align: left;">Lý do</th>
                            <th style="padding: 10px; text-align: left;">Thời gian</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($lichSuDiem as $index => $item)
                            <tr style="background: #f1f8f7; color: #004d40;">
                                <td style="padding: 10px;">
                                    {{ ($lichSuDiem->currentPage() - 1) * $lichSuDiem->perPage() + $loop->iteration }}
                                </td>
                                <td style="padding: 10px;">
                                    @php
                                        $isTru = Str::contains($item->ly_do, 'Trừ');
                                    @endphp

                                    @if ($isTru)
                                        <span style="color: red;">-{{ abs($item->thay_doi) }}</span>
                                    @else
                                        <span style="color: green;">+{{ $item->thay_doi }}</span>
                                    @endif

                                </td>
                                <td style="padding: 10px;">{{ $item->ly_do ?? 'Không rõ' }}</td>
                                <td style="padding: 10px;">{{ $item->thoi_gian }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="padding: 10px; text-align: center; color: gray;">
                                    Bạn chưa có lịch sử điểm nào.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- Phân trang --}}
                @if ($lichSuDiem->hasPages())
                    <div style="margin-top: 20px;">
                        {{ $lichSuDiem->withQueryString()->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const avatarInput = document.getElementById('avatarUpload');
            const avatarForm = document.getElementById('avatarForm');
            const avatarPreview = document.getElementById('avatarPreview');

            if (avatarInput && avatarForm) {
                avatarInput.addEventListener('change', function() {
                    // ✅ Tạo formData trước khi sử dụng
                    const formData = new FormData(avatarForm);

                    fetch('/update-avatar', {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content')
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.avatar_url) {
                                avatarPreview.src = data.avatar_url;
                            } else {
                                console.warn('Không có avatar_url trong phản hồi');
                            }

                            if (data.message) {
                                Swal.fire({
                                    toast: true,
                                    position: 'top-end',
                                    icon: 'success',
                                    title: data.message,
                                    background: '#10b981',
                                    color: '#fff',
                                    showCloseButton: true,
                                    timer: 7000,
                                    timerProgressBar: true,
                                    showConfirmButton: false,
                                    customClass: {
                                        popup: 'custom-toast'
                                    }
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Lỗi khi upload:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Lỗi khi upload ảnh!',
                                text: error.message || 'Có lỗi xảy ra khi kết nối máy chủ.',
                            });
                        });
                });
            } else {
                console.error('Không tìm thấy #avatarUpload hoặc #avatarForm');
            }

            // chuyển tab
            const tabLinks = document.querySelectorAll('.tab-link');
            const tabContents = document.querySelectorAll('.tab-content');

            tabLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();

                    tabLinks.forEach(l => l.classList.remove('active'));
                    tabContents.forEach(tc => {
                        tc.classList.remove('active');
                        tc.style.display = 'none';
                    });

                    this.classList.add('active');
                    const targetId = this.getAttribute('data-tab');
                    document.getElementById(targetId).classList.add('active');
                    document.getElementById(targetId).style.display = 'block';
                });
            });

        });


        function togglePassword(el) {
            const input = el.previousElementSibling;
            const icon = el.querySelector('i');
            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                input.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        }
    </script>
@endsection
