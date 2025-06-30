@extends('layouts.admin')
@section('content')
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.1.1/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet" />

    <style>
        .select2-container--bootstrap-5 .select2-selection {
            min-height: 38px;
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            line-height: 1.5;
        }

        .select2-selection__choice {
            padding: 0.25rem 0.5rem !important;
            background-color: #ffffff !important;
            color: #000000 !important;
            /* Màu chữ trắng */
            font-size: 0.875rem;
            border: 10px dashed black;
        }

        .select2-container {
            width: 100% !important;
        }
    </style>


    <div class="container-fluid">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Thêm phim mới</h5>
                <a href="{{ route('admin.phim.index') }}" class="btn btn-primary btn-sm" title="Quay lại">
                    <i class="ti ti-arrow-left me-1"></i> Quay lại
                </a>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.phim.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-4">
                        <div class="col-md-8">
                            <div class="mb-4">
                                <label for="ten_phim" class="form-label fw-semibold">Tên phim <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control rounded @error('ten_phim') is-invalid @enderror"
                                    id="ten_phim" name="ten_phim" value="{{ old('ten_phim') }}"
                                    placeholder="Nhập tên phim">
                                @error('ten_phim')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="mo_ta" class="form-label fw-semibold">Mô tả</label>
                                <textarea class="form-control rounded @error('mo_ta') is-invalid @enderror" id="mo_ta" name="mo_ta" rows="4"
                                    placeholder="Nhập mô tả phim">{{ old('mo_ta') }}</textarea>
                                @error('mo_ta')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label for="dao_dien" class="form-label fw-semibold">Đạo diễn</label>
                                    <input type="text"
                                        class="form-control rounded @error('dao_dien') is-invalid @enderror" id="dao_dien"
                                        name="dao_dien" value="{{ old('dao_dien') }}" placeholder="Nhập tên đạo diễn">
                                    @error('dao_dien')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-4">
                                    <label for="thoi_luong" class="form-label fw-semibold">Thời lượng (phút)</label>
                                    <input type="number"
                                        class="form-control rounded @error('thoi_luong') is-invalid @enderror"
                                        id="thoi_luong" name="thoi_luong" value="{{ old('thoi_luong') }}" min="1"
                                        placeholder="Nhập thời lượng">
                                    @error('thoi_luong')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="dien_vien" class="form-label fw-semibold">Diễn viên</label>
                                <textarea class="form-control rounded @error('dien_vien') is-invalid @enderror" id="dien_vien" name="dien_vien"
                                    rows="2" placeholder="Nhập danh sách diễn viên">{{ old('dien_vien') }}</textarea>
                                @error('dien_vien')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label for="ngay_phat_hanh" class="form-label fw-semibold">Ngày phát hành</label>
                                    <input type="text"
                                        class="form-control rounded datepicker @error('ngay_phat_hanh') is-invalid @enderror"
                                        id="ngay_phat_hanh" name="ngay_phat_hanh" value="{{ old('ngay_phat_hanh') }}"
                                        placeholder="YYYY-MM-DD">
                                    @error('ngay_phat_hanh')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-4">
                                    <label for="ngay_ket_thuc" class="form-label fw-semibold">Ngày kết thúc</label>
                                    <input type="text"
                                        class="form-control rounded datepicker @error('ngay_ket_thuc') is-invalid @enderror"
                                        id="ngay_ket_thuc" name="ngay_ket_thuc" value="{{ old('ngay_ket_thuc') }}"
                                        placeholder="YYYY-MM-DD">
                                    @error('ngay_ket_thuc')
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
                                    id="trailer" name="trailer" value="{{ old('trailer') }}"
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
                                <div class="mt-2" id="poster-preview"></div>
                            </div>

                            <div class="mb-4">
                                <label for="do_tuoi" class="form-label fw-semibold">Độ tuổi</label>
                                <input type="text" class="form-control rounded @error('do_tuoi') is-invalid @enderror"
                                    id="do_tuoi" name="do_tuoi" value="{{ old('do_tuoi') }}"
                                    placeholder="VD: 16+, 18+, P">
                                @error('do_tuoi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold" for="the_loai_ids">Thể loại <span
                                        class="text-danger">*</span></label>
                                <select id="the_loai_ids" name="the_loai_ids[]"
                                    class="form-control select2 rounded @error('the_loai_ids') is-invalid @enderror"
                                    multiple>
                                    @foreach ($theLoaiPhims as $theLoai)
                                        <option value="{{ $theLoai->id }}"
                                            {{ in_array($theLoai->id, old('the_loai_ids', [])) ? 'selected' : '' }}>
                                            {{ $theLoai->ten_the_loai }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('the_loai_ids')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>


                            <div class="mb-4">
                                <label for="dinh_dang_ids" class="form-label fw-semibold">Định dạng <span
                                        class="text-danger">*</span></label>
                                <select class="form-control select2 rounded @error('dinh_dang_ids') is-invalid @enderror"
                                    id="dinh_dang_ids" name="dinh_dang_ids[]" multiple>
                                    @foreach ($dinhDangPhims as $dinhDang)
                                        <option value="{{ $dinhDang->id }}"
                                            {{ in_array($dinhDang->id, old('dinh_dang_ids', [])) ? 'selected' : '' }}>
                                            {{ $dinhDang->ten_dinh_dang }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('dinh_dang_ids')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="phu_de_ids" class="form-label fw-semibold">Phụ đề <span
                                        class="text-danger">*</span></label>
                                <select class="form-control select2 rounded @error('phu_de_ids') is-invalid @enderror"
                                    id="phu_de_ids" name="phu_de_ids[]" multiple>
                                    @foreach ($phuDePhims as $phuDe)
                                        <option value="{{ $phuDe->id }}"
                                            {{ in_array($phuDe->id, old('phu_de_ids', [])) ? 'selected' : '' }}>
                                            {{ $phuDe->ten_phu_de }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('dinh_dang_ids')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="chi_nhanh_ids" class="form-label fw-semibold">Chi nhánh <span
                                        class="text-danger">*</span></label>
                                <select class="form-control select2 rounded @error('chi_nhanh_ids') is-invalid @enderror"
                                    id="chi_nhanh_ids" name="chi_nhanh_ids[]" multiple>
                                    @foreach ($chiNhanhs as $chiNhanh)
                                        <option value="{{ $chiNhanh->id }}"
                                            {{ in_array($chiNhanh->id, old('chi_nhanh_ids', [])) ? 'selected' : '' }}>
                                            {{ $chiNhanh->ten_chi_nhanh }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('chi_nhanh_ids')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="rap_phim_ids" class="form-label fw-semibold">Rạp <span
                                        class="text-danger">*</span></label>
                                <select class="form-control select2 rounded @error('rap_phim_ids') is-invalid @enderror"
                                    id="rap_phim_ids" name="rap_phim_ids[]" multiple>
                                    @foreach ($rapPhims as $rapPhim)
                                        <option value="{{ $rapPhim->id }}"
                                            {{ in_array($rapPhim->id, old('rap_phim_ids', [])) ? 'selected' : '' }}>
                                            {{ $rapPhim->ten_rap }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('rap_phim_ids')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('admin.phim.index') }}" class="btn btn-outline-secondary"
                            title="Hủy">Hủy</a>
                        <button type="submit" class="btn btn-primary" title="Lưu">
                            Lưu
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/vn.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Khởi tạo select2 cho tất cả
            function initSelect2(selector) {
                $(selector).select2({
                    theme: 'bootstrap-5',
                    width: '100%',
                    allowClear: true,
                    placeholder: $(selector).attr('placeholder') || 'Chọn'
                });
            }

            $('.select2').each(function() {
                initSelect2(this);
            });


            // Flatpickr
            flatpickr(".datepicker", {
                dateFormat: "Y-m-d",
                locale: "vn",
                allowInput: true,
            });

            // *** FIX: Dữ liệu ngôn ngữ được mở rộng và dịch sang tiếng Việt ***
            const ngonNguData = [{
                    value: 'Vietnamese',
                    text: 'Tiếng Việt'
                },
                {
                    value: 'English',
                    text: 'Tiếng Anh'
                },
                {
                    value: 'Chinese',
                    text: 'Tiếng Trung'
                },
                {
                    value: 'Korean',
                    text: 'Tiếng Hàn'
                },
                {
                    value: 'Japanese',
                    text: 'Tiếng Nhật'
                },
                {
                    value: 'French',
                    text: 'Tiếng Pháp'
                },
                {
                    value: 'German',
                    text: 'Tiếng Đức'
                },
                {
                    value: 'Spanish',
                    text: 'Tiếng Tây Ban Nha'
                },
                {
                    value: 'Russian',
                    text: 'Tiếng Nga'
                },
                {
                    value: 'Hindi',
                    text: 'Tiếng Hindi'
                },
                {
                    value: 'Thai',
                    text: 'Tiếng Thái'
                },
                {
                    value: 'Indonesian',
                    text: 'Tiếng Indonesia'
                }
            ].sort((a, b) => a.text.localeCompare(b.text)); // Sắp xếp theo tên tiếng Việt

            const ngonNguSelect = document.getElementById('ngon_ngu');
            const oldNgonNgu = "{{ old('ngon_ngu') }}";
            ngonNguData.forEach(lang => {
                const option = document.createElement('option');
                option.value = lang.value;
                option.textContent = lang.text;
                if (oldNgonNgu === lang.value) option.selected = true;
                ngonNguSelect.appendChild(option);
            });

            // *** FIX: API quốc gia ưu tiên tiếng Việt ***
            // Chỉ yêu cầu các trường cần thiết để tăng tốc độ tải
            fetch('https://restcountries.com/v3.1/all?fields=name,translations')
                .then(res => res.json())
                .then(data => {
                    const quocGiaSelect = document.getElementById('quoc_gia');
                    const oldQuocGia = "{{ old('quoc_gia') }}";

                    // Xử lý và sắp xếp dữ liệu
                    const countries = data.map(country => {
                        // Ưu tiên tên tiếng Việt, nếu không có thì dùng tên chung
                        const displayName = country.translations.vie?.common || country.name.common;
                        return {
                            value: country.name.common,
                            text: displayName
                        };
                    }).sort((a, b) => a.text.localeCompare(b.text, 'vi')); // Sắp xếp theo tiếng Việt

                    countries.forEach(country => {
                        const option = document.createElement('option');
                        option.value = country.value;
                        option.textContent = country.text;
                        if (oldQuocGia === country.value) option.selected = true;
                        quocGiaSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Lỗi khi tải danh sách quốc gia:', error));


            // Poster preview
            document.getElementById('poster').addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        document.getElementById('poster-preview').innerHTML =
                            '<img src="' + e.target.result +
                            '" class="img-fluid img-thumbnail rounded" style="max-height: 200px;">';
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Focus vào tên phim khi tải trang
            document.getElementById('ten_phim').focus();

            // Xác nhận khi hủy
            document.querySelector('a.btn-outline-secondary').addEventListener('click', function(e) {
                if (!confirm('Bạn có chắc muốn hủy bỏ và quay lại trang danh sách không?')) {
                    e.preventDefault();
                }
            });
        });
    </script>
@endsection
