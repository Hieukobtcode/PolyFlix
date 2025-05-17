@extends('layouts.admin')

@section('title', 'Quản lý Rạp Phim')
@section('page-title', 'Chỉnh sửa Rạp Phim')
@section('breadcrumb', 'Chỉnh sửa Rạp Phim')

@section('styles')
    <style>
        .card { border-radius: 10px; }
        .form-control, .form-select { border-radius: 8px; }
        .form-label { font-weight: 500; }
        .btn { border-radius: 8px; }
        .invalid-feedback { font-size: 0.9em; }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Chỉnh sửa Rạp: {{ $rapPhim->ten_rap }}</h5>
                <a href="{{ route('admin.rap-phim.index') }}" class="btn btn-light btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Quay lại
                </a>
            </div>

            <div class="card-body p-4">
                <form action="{{ route('admin.rap-phim.update', $rapPhim->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-4">
                        <div class="col-md-8">
                            <div class="mb-4">
                                <label for="ten_rap" class="form-label">Tên Rạp <span class="text-danger">*</span></label>
                                <input type="text" id="ten_rap" name="ten_rap"
                                    class="form-control @error('ten_rap') is-invalid @enderror"
                                    value="{{ old('ten_rap', $rapPhim->ten_rap) }}" placeholder="Nhập tên rạp">
                                @error('ten_rap')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="dia_chi" class="form-label">Địa chỉ</label>
                                <input type="text" id="dia_chi" name="dia_chi"
                                    class="form-control @error('dia_chi') is-invalid @enderror"
                                    value="{{ old('dia_chi', $rapPhim->dia_chi) }}" placeholder="Nhập địa chỉ">
                                @error('dia_chi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="so_dien_thoai" class="form-label">Số điện thoại</label>
                                <input type="text" id="so_dien_thoai" name="so_dien_thoai"
                                    class="form-control @error('so_dien_thoai') is-invalid @enderror"
                                    value="{{ old('so_dien_thoai', $rapPhim->so_dien_thoai) }}" placeholder="Nhập số điện thoại">
                                @error('so_dien_thoai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                              <div class="mb-4">
                                <label for="email" class="form-label">Email</label>
                                <input type="text" id="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email', $rapPhim->email) }}" placeholder="Nhập email">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="chi_nhanh_id" class="form-label">Chi nhánh <span class="text-danger">*</span></label>
                                <select name="chi_nhanh_id" id="chi_nhanh_id"
                                    class="form-select @error('chi_nhanh_id') is-invalid @enderror">
                                    <option value="">-- Chọn chi nhánh --</option>
                                    @foreach ($chiNhanhs as $chiNhanh)
                                        <option value="{{ $chiNhanh->id }}"
                                            {{ old('chi_nhanh_id', $rapPhim->chi_nhanh_id) == $chiNhanh->id ? 'selected' : '' }}>
                                            {{ $chiNhanh->ten_chi_nhanh }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('chi_nhanh_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="trang_thai" class="form-label">Trạng thái</label>
                                <select name="trang_thai" id="trang_thai"
                                    class="form-select @error('trang_thai') is-invalid @enderror">
                                    <option value="đang hoạt động" {{ old('trang_thai', $rapPhim->trang_thai) == 'đang hoạt động' ? 'selected' : '' }}>Đang Hoạt động</option>
                                    <option value="bảo trì" {{ old('trang_thai', $rapPhim->trang_thai) == 'bảo trì' ? 'selected' : '' }}>Bảo Trì</option>
                                    <option value="đã đóng" {{ old('trang_thai', $rapPhim->trang_thai) == 'đã đóng' ? 'selected' : '' }}>Đã Đóng</option>
                                </select>
                                @error('trang_thai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('admin.rap-phim.index') }}" class="btn btn-outline-secondary">Hủy</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Cập nhật
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
            document.getElementById('ten_rap').focus();
            document.querySelector('.btn-outline-secondary').addEventListener('click', function (e) {
                if (!confirm('Bạn có muốn hủy và quay lại danh sách?')) {
                    e.preventDefault();
                }
            });
        });
    </script>
@endsection
