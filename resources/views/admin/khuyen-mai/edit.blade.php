@extends('layouts.admin')

@section('title', 'Chỉnh Sửa Khuyến Mãi')
@section('page-title', 'Quản lý Khuyến Mãi')
@section('breadcrumb', 'Chỉnh sửa khuyến mãi')

@section('styles')
<style>
    .card {
        border-radius: 10px;
    }
    .form-control, .form-select {
        border-radius: 8px;
    }
    .form-label {
        margin-bottom: 0.5rem;
    }
    .btn {
        border-radius: 8px;
    }
    .invalid-feedback {
        font-size: 0.9em;
    }
    .select2-container--bootstrap-5 .select2-selection {
        border-radius: 8px;
    }
</style>
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0 fw-bold">Chỉnh sửa khuyến mãi</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.khuyen-mai.update', $khuyenMai->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="ma_khuyen_mai" class="form-label">Mã khuyến mãi <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('ma_khuyen_mai') is-invalid @enderror" 
                                        id="ma_khuyen_mai" name="ma_khuyen_mai" value="{{ old('ma_khuyen_mai', $khuyenMai->ma_khuyen_mai) }}" required>
                                    @error('ma_khuyen_mai')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="ten" class="form-label">Tên khuyến mãi <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('ten') is-invalid @enderror" 
                                        id="ten" name="ten" value="{{ old('ten', $khuyenMai->ten) }}" required>
                                    @error('ten')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="mo_ta" class="form-label">Mô tả <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('mo_ta') is-invalid @enderror" 
                                id="mo_ta" name="mo_ta" rows="3" required>{{ old('mo_ta', $khuyenMai->mo_ta) }}</textarea>
                            @error('mo_ta')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="loai_giam_gia" class="form-label">Loại giảm giá <span class="text-danger">*</span></label>
                                    <select class="form-select @error('loai_giam_gia') is-invalid @enderror" 
                                        id="loai_giam_gia" name="loai_giam_gia" required>
                                        <option value="">-- Chọn loại giảm giá --</option>
                                        <option value="phan_tram" {{ old('loai_giam_gia', $khuyenMai->loai_giam_gia) == 'phan_tram' ? 'selected' : '' }}>Phần trăm (%)</option>
                                        <option value="tien" {{ old('loai_giam_gia', $khuyenMai->loai_giam_gia) == 'tien' ? 'selected' : '' }}>Tiền (VNĐ)</option>
                                    </select>
                                    @error('loai_giam_gia')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="gia_tri_giam" class="form-label">Giá trị giảm <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('gia_tri_giam') is-invalid @enderror" 
                                        id="gia_tri_giam" name="gia_tri_giam" value="{{ old('gia_tri_giam', $khuyenMai->gia_tri_giam) }}" min="0" step="0.01" required>
                                    @error('gia_tri_giam')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="giam_toi_da" class="form-label">Giảm tối đa (VNĐ)</label>
                                    <input type="number" class="form-control @error('giam_toi_da') is-invalid @enderror" 
                                        id="giam_toi_da" name="giam_toi_da" value="{{ old('giam_toi_da', $khuyenMai->giam_toi_da) }}" min="0" step="0.01">
                                    <small class="text-muted">Chỉ áp dụng cho giảm giá theo phần trăm</small>
                                    @error('giam_toi_da')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="ap_dung_cho" class="form-label">Áp dụng cho <span class="text-danger">*</span></label>
                                    <select class="form-select @error('ap_dung_cho') is-invalid @enderror" 
                                        id="ap_dung_cho" name="ap_dung_cho" required>
                                        <option value="">-- Chọn loại áp dụng --</option>
                                        <option value="ve" {{ old('ap_dung_cho', $khuyenMai->ap_dung_cho) == 've' ? 'selected' : '' }}>Vé xem phim</option>
                                        <option value="do_an" {{ old('ap_dung_cho', $khuyenMai->ap_dung_cho) == 'do_an' ? 'selected' : '' }}>Đồ ăn</option>
                                        <option value="tat_ca" {{ old('ap_dung_cho', $khuyenMai->ap_dung_cho) == 'tat_ca' ? 'selected' : '' }}>Tất cả</option>
                                    </select>
                                    @error('ap_dung_cho')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="don_toi_thieu" class="form-label">Đơn tối thiểu (VNĐ)</label>
                                    <input type="number" class="form-control @error('don_toi_thieu') is-invalid @enderror" 
                                        id="don_toi_thieu" name="don_toi_thieu" value="{{ old('don_toi_thieu', $khuyenMai->don_toi_thieu) }}" min="0" step="0.01">
                                    @error('don_toi_thieu')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="so_lan_su_dung_toi_da" class="form-label">Số lần sử dụng tối đa</label>
                                    <input type="number" class="form-control @error('so_lan_su_dung_toi_da') is-invalid @enderror" 
                                        id="so_lan_su_dung_toi_da" name="so_lan_su_dung_toi_da" value="{{ old('so_lan_su_dung_toi_da', $khuyenMai->so_lan_su_dung_toi_da) }}" min="1">
                                    <small class="text-muted">Để trống nếu không giới hạn</small>
                                    @error('so_lan_su_dung_toi_da')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="ngay_bat_dau" class="form-label">Ngày bắt đầu <span class="text-danger">*</span></label>
                                    <input type="datetime-local" class="form-control @error('ngay_bat_dau') is-invalid @enderror" 
                                        id="ngay_bat_dau" name="ngay_bat_dau" 
                                        value="{{ old('ngay_bat_dau', $khuyenMai->ngay_bat_dau->format('Y-m-d\TH:i')) }}" required>
                                    @error('ngay_bat_dau')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="ngay_ket_thuc" class="form-label">Ngày kết thúc <span class="text-danger">*</span></label>
                                    <input type="datetime-local" class="form-control @error('ngay_ket_thuc') is-invalid @enderror" 
                                        id="ngay_ket_thuc" name="ngay_ket_thuc" 
                                        value="{{ old('ngay_ket_thuc', $khuyenMai->ngay_ket_thuc->format('Y-m-d\TH:i')) }}" required>
                                    @error('ngay_ket_thuc')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="chi_nhanh_ids" class="form-label">Áp dụng cho chi nhánh <span class="text-danger">*</span></label>
                            <select class="form-select select2 @error('chi_nhanh_ids') is-invalid @enderror" 
                                id="chi_nhanh_ids" name="chi_nhanh_ids[]" multiple required>
                                @foreach($chiNhanhs as $chiNhanh)
                                    <option value="{{ $chiNhanh->id }}" 
                                        {{ in_array($chiNhanh->id, old('chi_nhanh_ids', $selectedChiNhanhIds)) ? 'selected' : '' }}>
                                        {{ $chiNhanh->ten_chi_nhanh }}
                                    </option>
                                @endforeach
                            </select>
                            @error('chi_nhanh_ids')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="trang_thai" class="form-label">Trạng thái <span class="text-danger">*</span></label>
                            <select class="form-select @error('trang_thai') is-invalid @enderror" 
                                id="trang_thai" name="trang_thai" required>
                                <option value="hoat_dong" {{ old('trang_thai', $khuyenMai->trang_thai) == 'hoat_dong' ? 'selected' : '' }}>Hoạt động</option>
                                <option value="tam_dung" {{ old('trang_thai', $khuyenMai->trang_thai) == 'tam_dung' ? 'selected' : '' }}>Tạm dừng</option>
                            </select>
                            @error('trang_thai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <a href="{{ route('admin.khuyen-mai.index') }}" class="btn btn-secondary me-2">
                                <i class="fas fa-arrow-left me-1"></i> Quay lại
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Cập nhật khuyến mãi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            theme: 'bootstrap-5',
            placeholder: 'Chọn chi nhánh áp dụng',
            allowClear: true
        });

        // Hiển thị/ẩn trường giảm tối đa dựa trên loại giảm giá
        $('#loai_giam_gia').change(function() {
            if ($(this).val() === 'phan_tram') {
                $('#giam_toi_da').closest('.col-md-4').show();
            } else {
                $('#giam_toi_da').closest('.col-md-4').hide();
            }
        });

        // Kích hoạt khi trang tải
        $('#loai_giam_gia').trigger('change');
    });
</script>
@endsection
