@extends('layouts.admin')

@section('title', 'Chỉnh sửa bài viết')
@section('page-title', 'Chỉnh sửa bài viết')
@section('breadcrumb', 'Chỉnh sửa bài viết')
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

        .img-preview {
            max-height: 200px;
            margin-top: 10px;
            display: block;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Chỉnh sửa bài viết</h5>
                <a href="{{ route('admin.bai-viet.index') }}" class="btn btn-light btn-sm" title="Quay lại">
                    <i class="fas fa-arrow-left me-1"></i> Quay lại
                </a>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.bai-viet.update', $baiViet->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="tieu_de" class="form-label fw-semibold">Tiêu đề <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('tieu_de') is-invalid @enderror" id="tieu_de" name="tieu_de"
                               value="{{ old('tieu_de', $baiViet->tieu_de) }}" placeholder="Nhập tiêu đề bài viết">
                        @error('tieu_de')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="noi_dung" class="form-label fw-semibold">Nội dung <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('noi_dung') is-invalid @enderror" id="noi_dung" name="noi_dung"
                                  rows="6" placeholder="Nhập nội dung bài viết">{{ old('noi_dung', $baiViet->noi_dung) }}</textarea>
                        @error('noi_dung')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="hinh_anh" class="form-label fw-semibold">Hình ảnh</label>
                        <input type="file" class="form-control @error('hinh_anh') is-invalid @enderror" id="hinh_anh" name="hinh_anh">
                        @if ($baiViet->hinh_anh)
                            <img src="{{ asset('storage/' . $baiViet->hinh_anh) }}" alt="Hình hiện tại" class="img-preview">
                        @endif
                        @error('hinh_anh')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="status" class="form-label fw-semibold">Trạng thái <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                                <option value="published" {{ old('status', $baiViet->status) === 'published' ? 'selected' : '' }}>Xuất bản</option>
                                <option value="draft" {{ old('status', $baiViet->status) === 'draft' ? 'selected' : '' }}>Bản nháp</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.bai-viet.index') }}" class="btn btn-outline-secondary" title="Hủy">Hủy</a>
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
        document.getElementById('tieu_de').focus();

        document.querySelector('.btn-outline-secondary').addEventListener('click', function (e) {
            if (!confirm('Bạn có chắc chắn muốn hủy chỉnh sửa?')) {
                e.preventDefault();
            }
        });
    </script>
@endsection
