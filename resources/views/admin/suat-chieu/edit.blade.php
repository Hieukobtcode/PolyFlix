@extends('layouts.admin')

@section('title', 'Danh sách Suất chiếu')
@section('page-title', 'Chỉnh sửa Suất chiếu')
@section('breadcrumb', 'Chỉnh sửa Suất chiếu')

@section('styles')
    <style>
        .card {
            border-radius: 10px;
        }

        .form-control,
        .form-select {
            border-radius: 8px;
            border: 1px solid #ced4da;
        }

        .form-label {
            margin-bottom: 0.5rem;
            font-weight: 500;
        }

        .btn {
            border-radius: 8px;
        }

        .invalid-feedback {
            font-size: 0.9em;
        }

        .alert {
            border-radius: 8px;
        }

        .gio-chieu-group {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Chỉnh sửa suất chiếu cho phim: {{ $suatChieu->phim->ten_phim }}</h5>
                <a href="{{ route('admin.suat-chieu.index') }}" class="btn btn-light btn-sm" title="Quay lại">
                    <i class="fas fa-arrow-left me-1"></i> Quay lại
                </a>
            </div>
            <div class="card-body p-4">
                @if ($errors->any())
                    <div class="alert alert-danger rounded">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <strong>Lỗi!</strong> Vui lòng kiểm tra lại thông tin.
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.suat-chieu.update', $suatChieu->id) }}" method="POST" id="suat-chieu-form">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="phim_id" value="{{ $suatChieu->phim_id }}">

                    <div class="row g-4">
                        <div class="col-md-12">
                            <div class="mb-4">
                                <label for="phong_chieu_id" class="form-label fw-semibold">Phòng chiếu <span
                                        class="text-danger">*</span></label>
                                <select class="form-select rounded @error('phong_chieu_id') is-invalid @enderror"
                                    id="phong_chieu_id" name="phong_chieu_id" required>
                                    <option value="">-- Chọn phòng chiếu --</option>
                                    @foreach ($phongChieus as $phong)
                                        <option value="{{ $phong->id }}"
                                            {{ old('phong_chieu_id', $suatChieu->phong_chieu_id) == $phong->id ? 'selected' : '' }}>
                                            {{ $phong->ten_phong }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('phong_chieu_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold">Phiên bản phim <span
                                        class="text-danger">*</span></label>
                                <div class="d-flex gap-3">
                                    @foreach ($dinhDangs as $fmt)
                                        @foreach ($phuDes as $sub)
                                            @php
                                                $fSlug = \Str::slug($fmt->ten_dinh_dang, '-');
                                                $sSlug = \Str::slug($sub->ten_phu_de, '-');
                                                $code = strtolower($fSlug . '-' . $sSlug);
                                                $checked =
                                                    old('phien_ban_phim', $suatChieu->phien_ban_phim) === $code
                                                        ? 'checked'
                                                        : '';
                                            @endphp

                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="phien_ban_phim"
                                                    id="version_{{ $code }}" value="{{ $code }}"
                                                    {{ $checked }} required>
                                                <label class="form-check-label" for="version_{{ $code }}">
                                                    {{ $fmt->ten_dinh_dang }} – {{ $sub->ten_phu_de }}
                                                </label>
                                            </div>
                                        @endforeach
                                    @endforeach
                                </div>
                                @error('phien_ban_phim')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="ngay_chieu" class="form-label fw-semibold">Ngày chiếu <span
                                        class="text-danger">*</span></label>
                                <input type="date" class="form-control rounded @error('ngay_chieu') is-invalid @enderror"
                                    id="ngay_chieu" name="ngay_chieu"
                                    value="{{ old('ngay_chieu', $suatChieu->ngay_chieu) }}" required>
                                @error('ngay_chieu')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold">Chế độ tạo suất chiếu <span
                                        class="text-danger">*</span></label>
                                <div class="d-flex gap-3">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="che_do" id="thu_cong"
                                            value="thu_cong" {{ old('che_do', 'thu_cong') == 'thu_cong' ? 'checked' : '' }}
                                            onchange="toggleCheDo()">
                                        <label class="form-check-label" for="thu_cong">Thủ công</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="che_do" id="tu_dong"
                                            value="tu_dong" {{ old('che_do') == 'tu_dong' ? 'checked' : '' }}
                                            onchange="toggleCheDo()">
                                        <label class="form-check-label" for="tu_dong">Tự động</label>
                                    </div>
                                </div>
                                @error('che_do')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Thủ công -->
                            <div id="gio-chieu-thu-cong">
                                <div class="gio-chieu-group row g-3 mb-3">
                                    <div class="col-md-5">
                                        <label class="form-label">Giờ bắt đầu <span class="text-danger">*</span></label>
                                        <input type="time"
                                            class="form-control rounded @error('thucong_bat_dau') is-invalid @enderror"
                                            name="thucong_bat_dau"
                                            value="{{ old('thucong_bat_dau', \Carbon\Carbon::parse($suatChieu->bat_dau)->format('H:i')) }}"
                                            required>
                                        @error('thucong_bat_dau')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-5">
                                        <label class="form-label">Giờ kết thúc <span class="text-danger">*</span></label>
                                        <input type="time"
                                            class="form-control rounded @error('thucong_ket_thuc') is-invalid @enderror"
                                            name="thucong_ket_thuc"
                                            value="{{ old('thucong_ket_thuc', \Carbon\Carbon::parse($suatChieu->ket_thuc)->format('H:i')) }}"
                                            required>
                                        @error('thucong_ket_thuc')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Tự động -->
                            <div id="gio-chieu-tu-dong" style="display: none;">
                                <div class="row g-3 mb-3">
                                    <div class="col-md-5">
                                        <label for="tudong_bat_dau" class="form-label">Giờ bắt đầu chung <span
                                                class="text-danger">*</span></label>
                                        <input type="time" id="tudong_bat_dau" name="tudong_bat_dau"
                                            class="form-control rounded @error('tudong_bat_dau') is-invalid @enderror"
                                            value="{{ old('tudong_bat_dau') }}">
                                        @error('tudong_bat_dau')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-5">
                                        <label for="tudong_ket_thuc" class="form-label">Giờ kết thúc chung <span
                                                class="text-danger">*</span></label>
                                        <input type="time" id="tudong_ket_thuc" name="tudong_ket_thuc"
                                            class="form-control rounded @error('tudong_ket_thuc') is-invalid @enderror"
                                            value="{{ old('tudong_ket_thuc') }}">
                                        @error('tudong_ket_thuc')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2 mt-4">
                                <a href="{{ route('admin.suat-chieu.index') }}" class="btn btn-outline-secondary"
                                    title="Hủy">Hủy</a>
                                <button type="submit" class="btn btn-primary" title="Cập nhật suất chiếu">
                                    <i class="fas fa-save me-1"></i> Cập nhật
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Tự động focus vào trường "Phòng chiếu"
            document.getElementById('phong_chieu_id').focus();

            // Xác nhận trước khi hủy
            document.querySelector('.btn-outline-secondary').addEventListener('click', function(e) {
                if (!confirm('Bạn có muốn hủy và quay lại danh sách?')) {
                    e.preventDefault();
                }
            });

            // Gọi toggleCheDo để khởi tạo trạng thái ban đầu
            toggleCheDo();
        });

        function toggleCheDo() {
            const cheDo = document.querySelector('input[name="che_do"]:checked').value;
            const thuCongBox = document.getElementById('gio-chieu-thu-cong');
            const tuDongBox = document.getElementById('gio-chieu-tu-dong');

            const thuCongBatDau = document.querySelector('input[name="bat_dau"]');
            const thuCongKetThuc = document.querySelector('input[name="ket_thuc"]');
            const tuDongBatDau = document.querySelector('input[name="tudong_bat_dau"]');
            const tuDongKetThuc = document.querySelector('input[name="tudong_ket_thuc"]');

            if (cheDo === 'tu_dong') {
                thuCongBox.style.display = 'none';
                tuDongBox.style.display = 'block';
                thuCongBatDau.required = false;
                thuCongKetThuc.required = false;
                tuDongBatDau.required = true;
                tuDongKetThuc.required = true;
            } else {
                thuCongBox.style.display = 'block';
                tuDongBox.style.display = 'none';
                thuCongBatDau.required = true;
                thuCongKetThuc.required = true;
                tuDongBatDau.required = false;
                tuDongKetThuc.required = false;
            }
        }
    </script>
@endsection
