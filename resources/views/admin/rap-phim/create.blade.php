@extends('layouts.admin')


@section('title', 'Quản lý Rạp')
@section('page-title', 'Thêm Rạp Chiếu')
@section('breadcrumb', 'Thêm Rạp Chiếu')

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
            <div class="card-header bg-success text-white">
                <h5 class="mb-0 fw-bold">Thêm rạp chiếu mới cho chi nhánh: {{ $chiNhanh->ten_chi_nhanh }}</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.rap-phim.store') }}" method="POST">
                    @csrf
                    <input type="hidden" value="{{ $chiNhanh->id }}" name="chi_nhanh_id">
                    <div class="mb-3">
                        <label for="ten_rap" class="form-label">Tên Rạp <span class="text-danger">*</span></label>
                        <input type="text" name="ten_rap" class="form-control" value="{{ old('ten_rap') }}" >
                        @error('ten_rap')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="dia_chi" class="form-label">Địa Chỉ <span class="text-danger">*</span></label>
                        <textarea name="dia_chi" class="form-control" rows="3" >{{ old('dia_chi') }}</textarea>
                        @error('dia_chi')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="trang_thai" class="form-label">Trạng Thái</label>
                        <select name="trang_thai" class="form-select">
                            <option value="đang hoạt động" {{ old('trang_thai') == 'đang hoạt động' ? 'selected' : '' }}>
                                Hoạt động</option>
                            <option value="bảo trì" {{ old('trang_thai') == 'bảo trì' ? 'selected' : '' }}>Bảo trì</option>
                            <option value="đã đóng" {{ old('trang_thai') == 'đã đóng' ? 'selected' : '' }}>Đã đóng</option>
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
                            Lưu Rạp
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
