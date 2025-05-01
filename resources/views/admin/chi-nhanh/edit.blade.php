@extends('layouts.admin')

@section('title', 'Chỉnh sửa chi nhánh')
@section('page-title', 'Chỉnh sửa chi nhánh')
@section('breadcrumb', 'Chỉnh sửa chi nhánh')
@section('styles')
    <style>
        .card {
            border-radius: 10px;
        }

        .form-control,
        .form-select {
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
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Chỉnh sửa chi nhánh</h5>
                <a href="{{ route('admin.chi-nhanh.index') }}" class="btn btn-light btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Quay lại
                </a>
            </div>
            <div class="card-body p-4">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.chi-nhanh.update', $chiNhanh->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="ten_chi_nhanh" class="form-label fw-semibold">Tên chi nhánh <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('ten_chi_nhanh') is-invalid @enderror"
                               id="ten_chi_nhanh" name="ten_chi_nhanh" value="{{ old('ten_chi_nhanh', $chiNhanh->ten_chi_nhanh) }}"
                               placeholder="Nhập tên chi nhánh">
                        @error('ten_chi_nhanh')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="dia_chi" class="form-label fw-semibold">Địa chỉ <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('dia_chi') is-invalid @enderror" id="dia_chi" name="dia_chi"
                                  rows="4" placeholder="Nhập địa chỉ">{{ old('dia_chi', $chiNhanh->dia_chi) }}</textarea>
                        @error('dia_chi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="quan_ly_id" class="form-label fw-semibold">Quản lý ID <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('quan_ly_id') is-invalid @enderror"
                               id="quan_ly_id" name="quan_ly_id" value="{{ old('quan_ly_id', $chiNhanh->quan_ly_id) }}"
                               placeholder="Nhập quản lý ID">
                        @error('quan_ly_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="trang_thai" class="form-label fw-semibold">Trạng thái <span class="text-danger">*</span></label>
                        <select class="form-select @error('trang_thai') is-invalid @enderror" id="trang_thai" name="trang_thai">
                            <option value="hoat_dong" {{ old('trang_thai', $chiNhanh->trang_thai) === 'hoat_dong' ? 'selected' : '' }}>Hoạt động</option>
                            <option value="tam_dung" {{ old('trang_thai', $chiNhanh->trang_thai) === 'tam_dung' ? 'selected' : '' }}>Tạm dừng</option>
                            <option value="dong_cua" {{ old('trang_thai', $chiNhanh->trang_thai) === 'dong_cua' ? 'selected' : '' }}>Đóng cửa</option>
                        </select>
                        @error('trang_thai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.chi-nhanh.index') }}" class="btn btn-outline-secondary">Hủy</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Cập nhật
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
