@extends('layouts.admin')

@section('title', 'Quản lý Chi Nhánh')
@section('page-title', 'Thêm chi nhánh')
@section('breadcrumb', 'Thêm chi nhánh')
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
                <h5 class="mb-0 fw-bold">Thêm chi nhánh mới</h5>
                <a href="{{ route('admin.chi-nhanh.index') }}" class="btn btn-light btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Quay lại
                </a>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.chi-nhanh.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label for="ten_chi_nhanh" class="form-label fw-semibold">Tên chi nhánh <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('ten_chi_nhanh') is-invalid @enderror"
                            id="ten_chi_nhanh" name="ten_chi_nhanh" value="{{ old('ten_chi_nhanh') }}"
                            placeholder="Nhập tên chi nhánh">
                        @error('ten_chi_nhanh')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="dia_chi" class="form-label fw-semibold">Địa chỉ <span
                                class="text-danger">*</span></label>
                        <textarea class="form-control @error('dia_chi') is-invalid @enderror" id="dia_chi" name="dia_chi"
                            rows="4" placeholder="Nhập địa chỉ chi nhánh">{{ old('dia_chi') }}</textarea>
                        @error('dia_chi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="quan_ly_id" class="form-label fw-semibold">Quản lý ID <span
                                class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('quan_ly_id') is-invalid @enderror" id="quan_ly_id"
                            name="quan_ly_id" value="{{ old('quan_ly_id') }}" placeholder="Nhập ID của quản lý chi nhánh">
                        @error('quan_ly_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="trang_thai" class="form-label fw-semibold">Trạng thái <span
                                class="text-danger">*</span></label>
                        <select class="form-select @error('trang_thai') is-invalid @enderror" id="trang_thai"
                            name="trang_thai">
                            <option value="">Chọn trạng thái</option>
                            <option value="hoat_dong" {{ old('trang_thai') === 'hoat_dong' ? 'selected' : '' }}>Hoạt động
                            </option>
                            <option value="tam_dung" {{ old('trang_thai') === 'tam_dung' ? 'selected' : '' }}>Tạm dừng
                            </option>
                            <option value="dong_cua" {{ old('trang_thai') === 'dong_cua' ? 'selected' : '' }}>Đóng cửa
                            </option>
                        </select>
                        @error('trang_thai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.chi-nhanh.index') }}" class="btn btn-outline-secondary btn-cancel">
                            Hủy
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Lưu
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('ten_chi_nhanh').focus();

            document.querySelector('.btn-cancel').addEventListener('click', function (e) {
                if (!confirm('Bạn có muốn hủy và quay lại danh sách?')) {
                    e.preventDefault();
                }
            });
        });
    </script>
@endsection