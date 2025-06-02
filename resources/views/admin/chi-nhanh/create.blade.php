@extends('layouts.admin')

@section('title', 'Quản lý chi nhánh')
@section('page-title', 'Thêm chi nhánh')
@section('breadcrumb', 'Thêm chi nhánh')

@section('styles')
    <style>
        .card {
            border-radius: 10px;
        }

        .form-label {
            font-weight: 600;
        }

        .btn {
            border-radius: 5px;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0 fw-bold">Thêm Chi Nhánh Mới</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.chi-nhanh.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="ten_chi_nhanh" class="form-label">Tên Chi Nhánh <span class="text-danger">*</span></label>
                        <input type="text" name="ten_chi_nhanh" class="form-control" value="{{ old('ten_chi_nhanh') }}"
                            required>
                        @error('ten_chi_nhanh')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="dia_chi" class="form-label">Địa Chỉ <span class="text-danger">*</span></label>
                        <textarea name="dia_chi" class="form-control" rows="3" required>{{ old('dia_chi') }}</textarea>
                        @error('dia_chi')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="trang_thai" class="form-label">Trạng Thái</label>
                        <select name="trang_thai" class="form-select">
                            <option value="hoat_dong" {{ old('trang_thai') == 'hoat_dong' ? 'selected' : '' }}>Hoạt động
                            </option>
                            <option value="tam_dung" {{ old('trang_thai') == 'tam_dung' ? 'selected' : '' }}>Tạm dừng
                            </option>
                            <option value="dong_cua" {{ old('trang_thai') == 'dong_cua' ? 'selected' : '' }}>Đóng cửa
                            </option>
                        </select>
                        @error('trang_thai')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.chi-nhanh.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Quay lại
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-1"></i> Lưu Chi Nhánh
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

