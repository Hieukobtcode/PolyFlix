@extends('layouts.admin')

@section('title', 'Danh sách Suất chiếu')
@section('page-title', 'Thêm Suất chiếu')
@section('breadcrumb', 'Thêm Suất chiếu')

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
                <h5 class="mb-0 fw-bold">Thêm suất chiếu cho phim: {{ $phim->ten_phim }}</h5>
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

                <div class="row">
                    <!-- Cột trái: Form thêm suất chiếu -->
                    <div class="col-md-6 border-end">
                        <div class="mb-4">
                            <span class="fw-semibold text-muted">Ngày phát hành:</span>
                            <span>{{ $phim->ngay_phat_hanh ? $phim->ngay_phat_hanh->format('d/m/Y') : 'N/A' }}</span>
                            <span class="fw-semibold text-muted">Ngày kết thúc:</span>
                            <span>{{ $phim->ngay_ket_thuc ? $phim->ngay_ket_thuc->format('d/m/Y') : 'N/A' }}</span>
                        </div>

                        <form action="{{ route('admin.suat-chieu.store') }}" method="POST" id="suat-chieu-form">
                            @csrf
                            <input type="hidden" name="phim_id" value="{{ $phim->id }}">

                            <div class="mb-4">
                                <label for="phong_chieu_id" class="form-label fw-semibold">Phòng chiếu <span
                                        class="text-danger">*</span></label>
                                <select class="form-select rounded @error('phong_chieu_id') is-invalid @enderror"
                                    id="phong_chieu_id" name="phong_chieu_id" required>
                                    <option value="">-- Chọn phòng chiếu --</option>
                                    @foreach ($phongChieus as $phong)
                                        <option value="{{ $phong->id }}"
                                            {{ old('phong_chieu_id') == $phong->id ? 'selected' : '' }}>
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
                                <div class="d-flex flex-wrap gap-3">
                                    @foreach ($dinhDangs as $fmt)
                                        @foreach ($phuDes as $sub)
                                            @php
                                                $fSlug = \Str::slug($fmt->ten_dinh_dang, '-');
                                                $sSlug = \Str::slug($sub->ten_phu_de, '-');
                                                $code = strtolower($fSlug . '-' . $sSlug);
                                            @endphp
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="phien_ban_phim"
                                                    id="{{ $code }}" value="{{ $code }}" required>
                                                <label class="form-check-label" for="{{ $code }}">
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
                                    id="ngay_chieu" name="ngay_chieu" value="{{ old('ngay_chieu') }}" required>
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
                                        <label class="form-label">Giờ bắt đầu</label>
                                        <input type="time"
                                            class="form-control rounded @error('thucong_bat_dau.*') is-invalid @enderror"
                                            name="thucong_bat_dau[]">
                                        @error('thucong_bat_dau.*')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-5">
                                        <label class="form-label">Giờ kết thúc</label>
                                        <input type="time"
                                            class="form-control rounded @error('thucong_ket_thuc.*') is-invalid @enderror"
                                            name="thucong_ket_thuc[]">
                                        @error('thucong_ket_thuc.*')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <button type="button" class="btn btn-outline-danger btn-sm remove-gio"
                                            style="display: none;" onclick="xoaGioChieu(this)" title="Xóa khung giờ">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4" id="them-gio-chieu-wrapper">
                                <button type="button" class="btn btn-outline-success" onclick="themGioChieu()"
                                    title="Thêm khung giờ">
                                    <i class="fas fa-plus me-1"></i> Thêm giờ chiếu
                                </button>
                            </div>

                            <!-- Tự động -->
                            <div id="gio-chieu-tu-dong" style="display: none;">
                                <div class="row g-3 mb-3">
                                    <div class="col-md-5">
                                        <label for="tudong_bat_dau" class="form-label">Giờ bắt đầu chung</label>
                                        <input type="time" id="tudong_bat_dau" name="tudong_bat_dau"
                                            class="form-control rounded @error('tudong_bat_dau') is-invalid @enderror"
                                            value="{{ old('tudong_bat_dau') }}">
                                        @error('tudong_bat_dau')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-5">
                                        <label for="tudong_ket_thuc" class="form-label">Giờ kết thúc chung</label>
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
                                <button type="submit" class="btn btn-primary" title="Lưu suất chiếu">
                                    <i class="fas fa-save me-1"></i> Lưu suất chiếu
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Cột phải: Danh sách suất chiếu -->
                    <div class="col-md-6 ps-md-4 mt-4 mt-md-0">
                        <table class="table table-bordered" id="tbl-suat-chieu">
                            <thead>
                                <tr>
                                    <th class="text-center">Ngày chiếu</th>
                                    <th class="text-center">Giờ bắt đầu – Kết thúc</th>
                                    <th class="text-center">Phòng</th>
                                    <th class="text-center">Phiên bản</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="4" class="text-center">Vui lòng chọn phòng & ngày để xem suất chiếu.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script>
    const apiUrl = @json(route('admin.suat-chieu.theo-phong-ngay'));
</script>

@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
        
            document.querySelector('.btn-outline-secondary').addEventListener('click', function(e) {
                if (!confirm('Bạn có muốn hủy và quay lại danh sách?')) {
                    e.preventDefault();
                }
            });

            toggleCheDo();

            const selPhong = document.getElementById('phong_chieu_id');
            const inpNgay = document.getElementById('ngay_chieu');

            selPhong.addEventListener('change', fetchSuatChieu);
            inpNgay.addEventListener('change', fetchSuatChieu);
        });

        function toggleCheDo() {
            const cheDo = document.querySelector('input[name="che_do"]:checked').value;
            const thuCongBox = document.getElementById('gio-chieu-thu-cong');
            const tuDongBox = document.getElementById('gio-chieu-tu-dong');
            const themGioWrapper = document.getElementById('them-gio-chieu-wrapper');

            const thuCongBatDaus = document.querySelectorAll('input[name="thucong_bat_dau[]"]');
            const thuCongKetThucs = document.querySelectorAll('input[name="thucong_ket_thuc[]"]');
            const tuDongBatDau = document.querySelector('input[name="tudong_bat_dau"]');
            const tuDongKetThuc = document.querySelector('input[name="tudong_ket_thuc"]');

            if (cheDo === 'tu_dong') {
                thuCongBox.style.display = 'none';
                themGioWrapper.style.display = 'none';
                tuDongBox.style.display = 'block';

                thuCongBatDaus.forEach(i => i.required = false);
                thuCongKetThucs.forEach(i => i.required = false);
                tuDongBatDau.required = true;
                tuDongKetThuc.required = true;
            } else {
                thuCongBox.style.display = 'block';
                themGioWrapper.style.display = 'block';
                tuDongBox.style.display = 'none';

                thuCongBatDaus.forEach(i => i.required = true);
                thuCongKetThucs.forEach(i => i.required = true);
                tuDongBatDau.required = false;
                tuDongKetThuc.required = false;
            }
        }

        function themGioChieu() {
            const container = document.getElementById('gio-chieu-thu-cong');
            const group = document.createElement('div');
            group.classList.add('gio-chieu-group', 'row', 'g-3', 'mb-3');

            group.innerHTML = `
                <div class="col-md-5">
                    <label class="form-label">Giờ bắt đầu</label>
                    <input type="time" name="thucong_bat_dau[]" class="form-control rounded" required>
                </div>
                <div class="col-md-5">
                    <label class="form-label">Giờ kết thúc</label>
                    <input type="time" name="thucong_ket_thuc[]" class="form-control rounded" required>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="xoaGioChieu(this)" title="Xóa khung giờ">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            `;
            container.appendChild(group);
            toggleCheDo();
        }

        function xoaGioChieu(btn) {
            btn.closest('.gio-chieu-group').remove();
            toggleCheDo();
        }

        function fetchSuatChieu() {
            const phongId = document.getElementById('phong_chieu_id').value;
            const ngay = document.getElementById('ngay_chieu').value;
            const tbody = document.querySelector('#tbl-suat-chieu tbody');
            tbody.innerHTML = `<tr><td colspan="4" class="text-center">Đang tải...</td></tr>`;

            if (!phongId || !ngay) {
                tbody.innerHTML =
                    `<tr><td colspan="4" class="text-center">Vui lòng chọn phòng & ngày để xem suất chiếu.</td></tr>`;
                return;
            }
            
            fetch(`${apiUrl}?phong_chieu_id=${phongId}&ngay_chieu=${ngay}`)
            .then(res => {
                    if (!res.ok) throw new Error('Lỗi kết nối');
                    return res.json();
                })
                .then(data => {
                    if (data.length === 0) {
                        tbody.innerHTML =
                            `<tr><td colspan="4" class="text-center">Chưa có suất chiếu cho lựa chọn này.</td></tr>`;
                    } else {
                        tbody.innerHTML = data.map(s => `
                    <tr>
                        <td>${s.ngay_chieu}</td>
                        <td>${s.gio_bat_dau} – ${s.gio_ket_thuc}</td>
                        <td>${s.phong}</td>
                        <td>${s.phien_ban}</td>
                    </tr>
                `).join('');
                    }
                })
                .catch(err => {
                    console.error(err);
                    tbody.innerHTML =
                        `<tr><td colspan="4" class="text-center text-danger">Lỗi khi tải dữ liệu.</td></tr>`;
                });
        }
    </script>
@endsection
