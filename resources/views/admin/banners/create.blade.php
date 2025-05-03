@extends('layouts.admin')

@section('title', 'Thêm banner')
@section('page-title', 'Thêm banner')
@section('breadcrumb', 'Thêm banner')

@section('styles')
    <style>
        .card { border-radius: 10px; }
        .form-control, .form-select { border-radius: 8px; }
        .form-label { margin-bottom: 0.5rem; }
        .btn { border-radius: 8px; }
        .invalid-feedback { font-size: 0.9em; }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Thêm banner mới</h5>
                <a href="{{ route('admin.banners.index') }}" class="btn btn-light btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Quay lại
                </a>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- Hình ảnh --}}
                    <div class="mb-4">
                        <label for="hinh_anh" class="form-label fw-semibold">Hình ảnh <span class="text-danger">*</span></label>
                        <input type="file" class="form-control @error('hinh_anh') is-invalid @enderror"
                               id="hinh_anh" name="hinh_anh">
                        @error('hinh_anh')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Đường dẫn --}}
                    <div class="mb-4">
                        <label for="duong_dan" class="form-label fw-semibold">Đường dẫn (URL)</label>
                        <input type="text"
                               class="form-control @error('duong_dan') is-invalid @enderror"
                               id="duong_dan"
                               name="duong_dan"
                               value="{{ old('duong_dan') }}"
                               placeholder="https://example.com/">
                        @error('duong_dan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Vị trí --}}
                    <div class="mb-4">
                        <label for="vi_tri" class="form-label fw-semibold">Vị trí <span class="text-danger">*</span></label>
                        <select class="form-select @error('vi_tri') is-invalid @enderror" name="vi_tri" id="vi_tri">
                            <option value="">-- Chọn vị trí hiển thị --</option>
                            <option value="trang_chu_top" {{ old('vi_tri') == 'trang_chu_top' ? 'selected' : '' }}>Trang chủ - Trên cùng</option>
                            <option value="trang_chu_mid" {{ old('vi_tri') == 'trang_chu_mid' ? 'selected' : '' }}>Trang chủ - Giữa</option>
                            <option value="footer" {{ old('vi_tri') == 'footer' ? 'selected' : '' }}>Cuối trang</option>
                        </select>
                        @error('vi_tri')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Trạng thái --}}
                    <div class="mb-4">
                        <label for="trang_thai" class="form-label fw-semibold">Trạng thái <span class="text-danger">*</span></label>
                        <select class="form-select @error('trang_thai') is-invalid @enderror"
                                id="trang_thai" name="trang_thai">
                            <option value="">-- Chọn trạng thái --</option>
                            <option value="1" {{ old('trang_thai') == '1' ? 'selected' : '' }}>Hiển thị</option>
                            <option value="0" {{ old('trang_thai') == '0' ? 'selected' : '' }}>Ẩn</option>
                        </select>
                        @error('trang_thai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Buttons --}}
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.banners.index') }}"
                           class="btn btn-outline-secondary btn-cancel">
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
            document.getElementById('hinh_anh').focus();

            document.querySelector('.btn-cancel').addEventListener('click', function (e) {
                if (!confirm('Bạn có muốn hủy và quay lại danh sách?')) {
                    e.preventDefault();
                }
            });
        });
    </script>
@endsection
