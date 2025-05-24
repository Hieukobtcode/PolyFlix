@extends('layouts.admin')

@section('title', 'Quản lý Rạp phim')
@section('page-title', 'Thêm rạp phim mới')
@section('breadcrumb', 'Thêm rạp phim mới')

@section('styles')
    <style>
        .card {
            border-radius: 10px;
        }

        .form-control,
        .form-select {
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
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Thêm rạp phim mới</h5>
                <a href="{{ route('admin.rap-phim.index') }}" class="btn btn-light btn-sm" title="Quay lại">
                    <i class="fas fa-arrow-left me-1"></i> Quay lại
                </a>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.rap-phim.store') }}" method="POST">
                    @csrf
@if ($errors->any())
    <div class="alert alert-danger">
        <strong>Đã có lỗi xảy ra:</strong>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

                    <div class="row g-4">
                        <div class="col-md-8">
                            <div class="mb-4">
                                <label for="ten_rap" class="form-label fw-semibold">Tên rạp <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('ten_rap') is-invalid @enderror" id="ten_rap" name="ten_rap" value="{{ old('ten_rap') }}" placeholder="Nhập tên rạp">
                                @error('ten_rap')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="dia_chi" class="form-label fw-semibold">Địa chỉ <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('dia_chi') is-invalid @enderror" id="dia_chi" name="dia_chi" value="{{ old('dia_chi') }}" placeholder="Nhập địa chỉ rạp">
                                @error('dia_chi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="so_dien_thoai" class="form-label fw-semibold">Số điện thoại</label>
                                <input type="text" class="form-control @error('so_dien_thoai') is-invalid @enderror" id="so_dien_thoai" name="so_dien_thoai" value="{{ old('so_dien_thoai') }}" placeholder="Nhập số điện thoại">
                                @error('so_dien_thoai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-4">
    <label for="email" class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="Nhập email rạp">
    @error('email')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>


                            <div class="mb-4">
                                <label for="suc_chua" class="form-label fw-semibold">Sức chứa</label>
                                <input type="number" class="form-control @error('suc_chua') is-invalid @enderror" id="suc_chua" name="suc_chua" value="{{ old('suc_chua') }}" min="1" placeholder="Nhập sức chứa (số ghế)">
                                @error('suc_chua')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-4">
                                <label for="chi_nhanh_id" class="form-label fw-semibold">Chi nhánh <span class="text-danger">*</span></label>
                                <select class="form-select @error('chi_nhanh_id') is-invalid @enderror" id="chi_nhanh_id" name="chi_nhanh_id">
                                    <option value="">-- Chọn chi nhánh --</option>
                                    @foreach($chiNhanhs as $chiNhanh)
                                        <option value="{{ $chiNhanh->id }}" {{ old('chi_nhanh_id') == $chiNhanh->id ? 'selected' : '' }}>
                                            {{ $chiNhanh->ten_chi_nhanh }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('chi_nhanh_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="trang_thai" class="form-label fw-semibold">Trạng thái</label>
                                <select class="form-select @error('trang_thai') is-invalid @enderror" id="trang_thai" name="trang_thai">
                                    <option value="">-- Chọn trạng thái --</option>
                                    <option value="đang hoạt động" {{ old('trang_thai') == 'đang hoạt động' ? 'selected' : '' }}>Đang hoạt động</option>
                                    <option value="bảo trì" {{ old('trang_thai') == 'bảo trì' ? 'selected' : '' }}>Bảo trì</option>
                                    <option value="đã đóng" {{ old('trang_thai') == 'đã đóng' ? 'selected' : '' }}>Đã đóng</option>
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
            document.getElementById('ten_rap').focus();

            document.querySelector('.btn-outline-secondary').addEventListener('click', function (e) {
                if (!confirm('Bạn có muốn hủy và quay lại danh sách?')) {
                    e.preventDefault();
                }
            });
        });
    </script>
@endsection
