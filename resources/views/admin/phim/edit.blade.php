@extends('layouts.admin')

@section('title', 'Chỉnh sửa phim')
@section('page-title', 'Chỉnh sửa phim')
@section('breadcrumb', 'Chỉnh sửa phim')
@section('styles')
    <style>
        .card {
            border-radius: 10px;
        }

        .form-control,
        .form-select,
        .select2-container--default .select2-selection--multiple {
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

        .img-thumbnail {
            border-radius: 8px;
            max-height: 200px;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Chỉnh sửa phim: {{ $phim->ten_phim }}</h5>
                <a href="{{ route('admin.phim.index') }}" class="btn btn-light btn-sm" title="Quay lại">
                    <i class="fas fa-arrow-left me-1"></i> Quay lại
                </a>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.phim.update', $phim->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row g-4">
                        <div class="col-md-8">
                            <div class="mb-4">
                                <label for="ten_phim" class="form-label fw-semibold">Tên phim <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control rounded @error('ten_phim') is-invalid @enderror"
                                    id="ten_phim" name="ten_phim" value="{{ old('ten_phim', $phim->ten_phim) }}"
                                    placeholder="Nhập tên phim">
                                @error('ten_phim')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="mo_ta" class="form-label fw-semibold">Mô tả</label>
                                <textarea class="form-control rounded @error('mo_ta') is-invalid @enderror" id="mo_ta"
                                    name="mo_ta" rows="4"
                                    placeholder="Nhập mô tả phim">{{ old('mo_ta', $phim->mo_ta) }}</textarea>
                                @error('mo_ta')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label for="dao_dien" class="form-label fw-semibold">Đạo diễn</label>
                                    <input type="text" class="form-control rounded @error('dao_dien') is-invalid @enderror"
                                        id="dao_dien" name="dao_dien" value="{{ old('dao_dien', $phim->dao_dien) }}"
                                        placeholder="Nhập tên đạo diễn">
                                    @error('dao_dien')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-4">
                                    <label for="thoi_luong" class="form-label fw-semibold">Thời lượng (phút)</label>
                                    <input type="number"
                                        class="form-control rounded @error('thoi_luong') is-invalid @enderror"
                                        id="thoi_luong" name="thoi_luong" value="{{ old('thoi_luong', $phim->thoi_luong) }}"
                                        min="1" placeholder="Nhập thời lượng">
                                    @error('thoi_luong')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="dien_vien" class="form-label fw-semibold">Diễn viên</label>
                                <textarea class="form-control rounded @error('dien_vien') is-invalid @enderror"
                                    id="dien_vien" name="dien_vien" rows="2"
                                    placeholder="Nhập danh sách diễn viên">{{ old('dien_vien', $phim->dien_vien) }}</textarea>
                                @error('dien_vien')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label for="ngay_phat_hanh" class="form-label fw-semibold">Ngày phát hành</label>
                                    <input type="text"
                                        class="form-control rounded datepicker @error('ngay_phat_hanh') is-invalid @enderror"
                                        id="ngay_phat_hanh" name="ngay_phat_hanh"
                                        value="{{ old('ngay_phat_hanh', $phim->ngay_phat_hanh ? $phim->ngay_phat_hanh->format('Y-m-d') : '') }}"
                                        placeholder="YYYY-MM-DD">
                                    @error('ngay_phat_hanh')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-4">
                                    <label for="do_tuoi" class="form-label fw-semibold">Độ tuổi</label>
                                    <input type="text" class="form-control rounded @error('do_tuoi') is-invalid @enderror"
                                        id="do_tuoi" name="do_tuoi" value="{{ old('do_tuoi', $phim->do_tuoi) }}"
                                        placeholder="VD: 16+, 18+, P">
                                    @error('do_tuoi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label for="ngon_ngu" class="form-label fw-semibold">Ngôn ngữ</label>
                                    <select class="form-select rounded @error('ngon_ngu') is-invalid @enderror"
                                        id="ngon_ngu" name="ngon_ngu">
                                        <option value="">-- Chọn ngôn ngữ --</option>
                                    </select>
                                    @error('ngon_ngu')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-4">
                                    <label for="quoc_gia" class="form-label fw-semibold">Quốc gia</label>
                                    <select class="form-select rounded @error('quoc_gia') is-invalid @enderror"
                                        id="quoc_gia" name="quoc_gia">
                                        <option value="">-- Chọn quốc gia --</option>
                                    </select>
                                    @error('quoc_gia')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="trailer" class="form-label fw-semibold">Trailer URL (YouTube)</label>
                                <input type="text" class="form-control rounded @error('trailer') is-invalid @enderror"
                                    id="trailer" name="trailer" value="{{ old('trailer', $phim->trailer) }}"
                                    placeholder="https://www.youtube.com/watch?v=...">
                                @error('trailer')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-4">
                                <label for="poster" class="form-label fw-semibold">Poster</label>
                                <input type="file" class="form-control rounded @error('poster') is-invalid @enderror"
                                    id="poster" name="poster" accept="image/*">
                                <small class="form-text text-muted">Chấp nhận: jpeg, png, jpg, gif. Tối đa: 2MB</small>
                                @error('poster')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="mt-2" id="poster-preview">
                                    @if($phim->poster)
                                        <img src="{{ asset('storage/' . $phim->poster) }}" alt="{{ $phim->ten_phim }}"
                                            class="img-fluid img-thumbnail rounded" style="max-height: 200px;">
                                        <p class="text-muted small mt-1">Poster hiện tại. Tải lên mới để thay đổi.</p>
                                    @endif
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="the_loai_ids" class="form-label fw-semibold">Thể loại <span
                                        class="text-danger">*</span></label>
                                <select class="form-control select2 rounded @error('the_loai_ids') is-invalid @enderror"
                                    id="the_loai_ids" name="the_loai_ids[]" multiple>
                                    @foreach($theLoaiPhims as $theLoai)
                                        <option value="{{ $theLoai->id }}" {{ in_array($theLoai->id, old('the_loai_ids', $selectedTheLoais)) ? 'selected' : '' }}>
                                            {{ $theLoai->ten_the_loai }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('the_loai_ids')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="trang_thai" class="form-label fw-semibold">Trạng thái <span
                                        class="text-danger">*</span></label>
                                <select class="form-select rounded @error('trang_thai') is-invalid @enderror"
                                    id="trang_thai" name="trang_thai">
                                    <option value="đang chiếu" {{ old('trang_thai', $phim->trang_thai) === 'đang chiếu' ? 'selected' : '' }}>
                                        Đang chiếu</option>
                                    <option value="sắp chiếu" {{ old('trang_thai', $phim->trang_thai) === 'sắp chiếu' ? 'selected' : '' }}>
                                        Sắp chiếu</option>
                                    <option value="đã kết thúc" {{ old('trang_thai', $phim->trang_thai) === 'đã kết thúc' ? 'selected' : '' }}>
                                        Đã kết thúc</option>
                                    <option value="bị hủy" {{ old('trang_thai', $phim->trang_thai) === 'bị hủy' ? 'selected' : '' }}>
                                        Bị hủy</option>
                                </select>
                                @error('trang_thai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('admin.phim.index') }}" class="btn btn-outline-secondary" title="Hủy">Hủy</a>
                        <button type="submit" class="btn btn-primary" title="Cập nhật">
                            <i class="fas fa-save me-1"></i> Cập nhật
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/vn.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Flatpickr cho ngày phát hành
            flatpickr(".datepicker", {
                dateFormat: "Y-m-d",
                locale: "vn",
                allowInput: true,
            });

            // Select2 cho thể loại
            $('.select2').select2({
                placeholder: "Chọn thể loại phim",
                allowClear: true,
            });

            // Dữ liệu ngôn ngữ tĩnh
            const ngonNgu = ['Vietnamese', 'English', 'Chinese', 'Korean', 'Japanese'];
            const ngonNguSelect = document.getElementById('ngon_ngu');
            const oldNgonNgu = "{{ old('ngon_ngu', $phim->ngon_ngu ?? '') }}";
            ngonNgu.forEach(lang => {
                const option = document.createElement('option');
                option.value = lang;
                option.textContent = lang;
                if (oldNgonNgu === lang) option.selected = true;
                ngonNguSelect.appendChild(option);
            });

            // Gọi API để lấy danh sách quốc gia
            fetch('https://restcountries.com/v3.1/all')
                .then(res => res.json())
                .then(data => {
                    const quocGiaSelect = document.getElementById('quoc_gia');
                    const oldQuocGia = "{{ old('quoc_gia', $phim->quoc_gia ?? '') }}";
                    data.sort((a, b) => a.name.common.localeCompare(b.name.common));
                    data.forEach(country => {
                        const option = document.createElement('option');
                        option.value = country.name.common;
                        option.textContent = country.name.common;
                        if (oldQuocGia === country.name.common) option.selected = true;
                        quocGiaSelect.appendChild(option);
                    });
                });

            // Preview ảnh poster
            document.getElementById('poster').addEventListener('change', function () {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        document.getElementById('poster-preview').innerHTML =
                            '<img src="' + e.target.result + '" class="img-fluid img-thumbnail rounded" style="max-height: 200px;">';
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Tự động focus vào trường tên phim
            document.getElementById('ten_phim').focus();

            // Xác nhận trước khi hủy
            document.querySelector('.btn-outline-secondary').addEventListener('click', function (e) {
                if (!confirm('Bạn có muốn hủy và quay lại danh sách?')) {
                    e.preventDefault();
                }
            });
        });
    </script>
@endsection