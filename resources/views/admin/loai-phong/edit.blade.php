@extends('layouts.admin')

@section('title', 'Quản lý Loại phòng')
@section('page-title', 'Chỉnh sửa Loại phòng')
@section('breadcrumb', 'Chỉnh sửa Loại phòng')
@section('styles')
    <style>
        .card {
            border-radius: 10px;
        }

        .form-control {
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
                <h5 class="mb-0 fw-bold">Chỉnh sửa Loại phòng</h5>
                <a href="{{ route('admin.loai-phong.index') }}" class="btn btn-light btn-sm" title="Quay lại">
                    <i class="fas fa-arrow-left me-1"></i> Quay lại
                </a>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.loai-phong.update', $loaiPhong->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="ten_loai_phong" class="form-label fw-semibold">Tên loại phòng <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control rounded @error('ten_loai_phong') is-invalid @enderror"
                            id="ten_loai_phong" name="ten_loai_phong"
                            value="{{ old('ten_loai_phong', $loaiPhong->ten_loai_phong) }}"
                            placeholder="Nhập tên loại phòng">
                        @error('ten_loai_phong')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="mo_ta" class="form-label fw-semibold">Mô tả</label>
                        <textarea class="form-control rounded @error('mo_ta') is-invalid @enderror" id="mo_ta" name="mo_ta"
                            rows="4" placeholder="Nhập mô tả loại phòng">{{ old('mo_ta', $loaiPhong->mo_ta) }}</textarea>
                        @error('mo_ta')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.loai-phong.index') }}" class="btn btn-outline-secondary"
                            title="Hủy">Hủy</a>
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
    <script>
        // Tự động focus vào trường tên loại phòng khi tải trang
        document.getElementById('ten_loai_phong').focus();

        // Xác nhận trước khi hủy
        document.querySelector('.btn-outline-secondary').addEventListener('click', function (e) {
            if (!confirm('Bạn có muốn hủy và quay lại danh sách?')) {
                e.preventDefault();
            }
        });
    </script>
@endsection
