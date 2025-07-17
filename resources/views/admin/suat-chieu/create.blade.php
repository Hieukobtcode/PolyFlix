@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Thêm suất chiếu cho phim: {{ $phim->ten_phim }}</h5>
                <a href="{{ route('admin.suat-chieu.index') }}" class="btn btn-light btn-sm" title="Quay lại">
                    <i class="ti ti-arrow-left me-1"></i> Quay lại
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
                            <br>
                            <span class="fw-semibold text-muted">Ngày kết thúc:</span>
                            <span>{{ $phim->ngay_ket_thuc ? $phim->ngay_ket_thuc->format('d/m/Y') : 'N/A' }}</span>
                            <br>
                            <span class="fw-semibold text-muted">Thời lượng:</span>
                            <span>{{ $phim->thoi_luong }} phút</span>
                        </div>

                        <form id="suat-chieu-form">
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

                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label for="ngay_bat_dau" class="form-label fw-semibold">Ngày bắt đầu <span
                                            class="text-danger">*</span></label>
                                    <input type="date"
                                        class="form-control rounded @error('ngay_bat_dau') is-invalid @enderror"
                                        id="ngay_bat_dau" name="ngay_bat_dau" value="{{ old('ngay_bat_dau') }}" required>
                                    @error('ngay_bat_dau')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="ngay_ket_thuc" class="form-label fw-semibold">Ngày kết thúc <span
                                            class="text-danger">*</span></label>
                                    <input type="date"
                                        class="form-control rounded @error('ngay_ket_thuc') is-invalid @enderror"
                                        id="ngay_ket_thuc" name="ngay_ket_thuc" value="{{ old('ngay_ket_thuc') }}"
                                        required>
                                    @error('ngay_ket_thuc')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
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
                                        <input type="time" class="form-control rounded" name="thucong_bat_dau[]">
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <button type="button" class="btn btn-outline-danger btn-sm remove-gio"
                                            style="display: none;" onclick="xoaGioChieu(this)" title="Xóa giờ">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4" id="them-gio-chieu-wrapper">
                                <button type="button" class="btn btn-outline-success" onclick="themGioChieu()"
                                    title="Thêm giờ">
                                    <i class="ti ti-plus me-1"></i> Thêm giờ chiếu
                                </button>
                            </div>

                            <!-- Tự động -->
                            <div id="gio-chieu-tu-dong" style="display: none;">
                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <label for="tudong_bat_dau" class="form-label">Giờ bắt đầu</label>
                                        <input type="time" id="tudong_bat_dau" name="tudong_bat_dau"
                                            class="form-control rounded @error('tudong_bat_dau') is-invalid @enderror"
                                            value="{{ old('tudong_bat_dau') }}">
                                        @error('tudong_bat_dau')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="tudong_ket_thuc" class="form-label">Giờ kết thúc</label>
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
                                <button type="button" class="btn btn-primary" onclick="taoSuatChieu()"
                                    title="Tạo suất chiếu">
                                    Tạo suất chiếu
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Cột phải: Danh sách suất chiếu -->
                    <div class="col-md-6 ps-md-4 mt-4 mt-md-0">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold mb-0">Suất chiếu dự kiến</h6>
                            <button type="button" class="btn btn-success btn-sm" onclick="luuSuatChieu()"
                                style="display: none;" id="btn-luu-suat-chieu">
                                <i class="ti ti-device-floppy me-1"></i> Lưu suất chiếu
                            </button>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered" id="tbl-suat-chieu">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 40px;">
                                            <input type="checkbox" id="check-all" onchange="toggleCheckAll(this)">
                                        </th>
                                        <th class="text-center">Ngày chiếu</th>
                                        <th class="text-center">Giờ chiếu</th>
                                        <th class="text-center">Phòng</th>
                                        <th class="text-center">Phiên bản</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="5" class="text-center">Chưa có suất chiếu dự kiến.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Cột phải: Danh sách suất chiếu -->
            <div class="col-md-6 ps-md-4 mt-4 mt-md-0">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0">Suất chiếu đã có</h6>
                </div>
                <table class="table table-bordered" id="tbl-suat-chieu-da-co">
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

    <!-- Template cho hàng trong bảng -->
    <template id="row-template">
        <tr>
            <td class="text-center">
                <input type="checkbox" class="suat-checkbox" checked>
            </td>
            <td class="text-center">{ngay_chieu}</td>
            <td class="text-center">{gio_chieu}</td>
            <td>{phong}</td>
            <td>{phien_ban_display}</td>
        </tr>
    </template>
@endsection

@section('scripts')
    <script>
        let cacSuatChieuDeXuat = [];

        document.addEventListener('DOMContentLoaded', function() {
            toggleCheDo();

            // Set min date cho input ngày
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('ngay_bat_dau').min = today;
            document.getElementById('ngay_ket_thuc').min = today;

            // Khi thay đổi ngày bắt đầu, cập nhật min của ngày kết thúc
            document.getElementById('ngay_bat_dau').addEventListener('change', function() {
                document.getElementById('ngay_ket_thuc').min = this.value;
            });
        });

        function toggleCheDo() {
            const cheDo = document.querySelector('input[name="che_do"]:checked').value;
            const thuCongBox = document.getElementById('gio-chieu-thu-cong');
            const tuDongBox = document.getElementById('gio-chieu-tu-dong');
            const themGioWrapper = document.getElementById('them-gio-chieu-wrapper');

            if (cheDo === 'tu_dong') {
                thuCongBox.style.display = 'none';
                themGioWrapper.style.display = 'none';
                tuDongBox.style.display = 'block';
            } else {
                thuCongBox.style.display = 'block';
                themGioWrapper.style.display = 'block';
                tuDongBox.style.display = 'none';
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
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="xoaGioChieu(this)" title="Xóa giờ">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            `;
            container.appendChild(group);
        }

        function xoaGioChieu(btn) {
            btn.closest('.gio-chieu-group').remove();
        }

        function taoSuatChieu() {
            const form = document.getElementById('suat-chieu-form');
            const formData = new FormData(form);

            fetch('{{ route('admin.suat-chieu.store') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        cacSuatChieuDeXuat = data.du_kien;
                        hienThiSuatChieuDeXuat();
                        document.getElementById('btn-luu-suat-chieu').style.display = 'block';
                    } else {
                        alert(data.message || 'Có lỗi xảy ra khi tạo suất chiếu.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra khi tạo suất chiếu.');
                });
        }

        function hienThiSuatChieuDeXuat() {
            const tbody = document.querySelector('#tbl-suat-chieu tbody');
            const template = document.getElementById('row-template').innerHTML;

            if (cacSuatChieuDeXuat.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" class="text-center">Chưa có suất chiếu dự kiến.</td></tr>';
                return;
            }

            tbody.innerHTML = cacSuatChieuDeXuat.map(suat => {
                return template
                    .replace('{ngay_chieu}', suat.ngay_bat_dau_display)
                    .replace('{gio_chieu}', `${suat.bat_dau} - ${suat.ket_thuc}`)
                    .replace('{phong}', document.getElementById('phong_chieu_id').options[
                        document.getElementById('phong_chieu_id').selectedIndex
                    ].text)
                    .replace('{phien_ban_display}', suat.phien_ban_display || suat.phien_ban.split('-').map(p => p
                        .charAt(0).toUpperCase() + p
                        .slice(
                            1)).join(' - '));
            }).join('');
        }

        function toggleCheckAll(checkbox) {
            document.querySelectorAll('.suat-checkbox').forEach(cb => {
                cb.checked = checkbox.checked;
            });
        }

        function luuSuatChieu() {
            const checkboxes = document.querySelectorAll('.suat-checkbox:checked');
            const suatChieuDaChon = Array.from(checkboxes).map((cb, index) => cacSuatChieuDeXuat[index]);

            if (suatChieuDaChon.length === 0) {
                alert('Vui lòng chọn ít nhất một suất chiếu để lưu.');
                return;
            }

            fetch('{{ route('admin.suat-chieu.luu-suat-chieu') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        suat_chieus: suatChieuDaChon
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Lưu suất chiếu thành công!');
                        window.location.href = '{{ route('admin.suat-chieu.index') }}';
                    } else {
                        alert(data.message || 'Có lỗi xảy ra khi lưu suất chiếu.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra khi lưu suất chiếu.');
                });
        }

        function loadSuatChieu() {
            const phongId = document.getElementById('phong_chieu_id').value;
            const ngay = document.getElementById('ngay_bat_dau').value;

            if (!phongId || !ngay) return;

            fetch(`/admin/suat-chieu/theo-phong-va-ngay?phong_chieu_id=${phongId}&ngay_bat_dau=${ngay}`)
                .then(response => response.json())
                .then(data => {
                    document.querySelector('#tbl-suat-chieu-da-co tbody').innerHTML = data.html;
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.querySelector('#tbl-suat-chieu-da-co tbody').innerHTML =
                        '<tr><td colspan="4" class="text-center text-danger">Có lỗi xảy ra khi tải dữ liệu.</td></tr>';
                });
        }

        // Thêm event listeners
        document.getElementById('phong_chieu_id').addEventListener('change', loadSuatChieu);
        document.getElementById('ngay_bat_dau').addEventListener('change', loadSuatChieu);
    </script>
@endsection
