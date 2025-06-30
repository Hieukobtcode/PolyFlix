@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <form action="{{ route('admin.chi-nhanh.update', $chiNhanh->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="ten_chi_nhanh" class="form-label">Tên Chi Nhánh <span class="text-danger">*</span></label>
                        <input type="text" name="ten_chi_nhanh" class="form-control"
                            value="{{ old('ten_chi_nhanh', $chiNhanh->ten_chi_nhanh) }}" required>
                        @error('ten_chi_nhanh')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="dia_chi" class="form-label">Địa Chỉ <span class="text-danger">*</span></label>
                        <textarea name="dia_chi" class="form-control" rows="3" required>{{ old('dia_chi', $chiNhanh->dia_chi) }}</textarea>
                        @error('dia_chi')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="trang_thai" class="form-label">Trạng Thái</label>
                        <select name="trang_thai" class="form-select">
                            <option value="hoat_dong" {{ old('trang_thai', $chiNhanh->trang_thai) == 'hoat_dong' ? 'selected' : '' }}>Hoạt động</option>
                            <option value="tam_dung" {{ old('trang_thai', $chiNhanh->trang_thai) == 'tam_dung' ? 'selected' : '' }}>Tạm dừng</option>
                            <option value="dong_cua" {{ old('trang_thai', $chiNhanh->trang_thai) == 'dong_cua' ? 'selected' : '' }}>Đóng cửa</option>
                        </select>
                        @error('trang_thai')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.chi-nhanh.index') }}" class="btn btn-secondary">
                            <i class="ti ti-arrow-left me-2"></i> Quay lại
                        </a>
                        <button type="submit" class="btn btn-success">
                            Cập nhật
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection