@extends('layouts.admin')

@section('title', 'Cập nhật Món Ăn')
@section('page-title', 'Cập nhật Món Ăn')
@section('breadcrumb', 'Chỉnh sửa')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-warning text-white">
            <h5 class="mb-0 fw-bold">Cập nhật món ăn</h5>
        </div>

        <div class="card-body p-4">
            <form action="{{ route('admin.do-an.update', $doAn->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Tiêu đề</label>
                    <input type="text" name="tieu_de" class="form-control @error('tieu_de') is-invalid @enderror"
                           value="{{ old('tieu_de', $doAn->tieu_de) }}" required>
                    @error('tieu_de')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Mô tả / Nội dung</label>
                    <textarea name="noi_dung" rows="4" class="form-control @error('noi_dung') is-invalid @enderror">{{ old('noi_dung', $doAn->noi_dung) }}</textarea>
                    @error('noi_dung')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Hình ảnh</label>
                    <input type="file" name="hinh_anh" class="form-control @error('hinh_anh') is-invalid @enderror">
                    @error('hinh_anh')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    @if ($doAn->hinh_anh)
                        <div class="mt-2">
                            <img src="{{ asset('storage/' . $doAn->hinh_anh) }}" alt="Hình món ăn" style="max-height: 120px;">
                        </div>
                    @endif
                </div>

                <div class="mb-3">
                    <label class="form-label">Giá (VNĐ)</label>
                    <input type="number" name="gia" class="form-control @error('gia') is-invalid @enderror"
                           value="{{ old('gia', $doAn->gia) }}" min="0" step="1000" required>
                    @error('gia')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Danh mục</label>
                    <select name="danh_muc_id" class="form-select @error('danh_muc_id') is-invalid @enderror" required>
                        <option value="">-- Chọn danh mục --</option>
                        @foreach($danhMucs as $dm)
                            <option value="{{ $dm->id }}" {{ old('danh_muc_id', $doAn->danh_muc_id) == $dm->id ? 'selected' : '' }}>
                                {{ $dm->ten }}
                            </option>
                        @endforeach
                    </select>
                    @error('danh_muc_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Trạng thái</label>
                    <select name="trang_thai" class="form-select">
                        <option value="hien" {{ old('trang_thai', $doAn->trang_thai) == 'hien' ? 'selected' : '' }}>Hiện</option>
                        <option value="an" {{ old('trang_thai', $doAn->trang_thai) == 'an' ? 'selected' : '' }}>Ẩn</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Cập nhật
                </button>
                <a href="{{ route('admin.do-an.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Quay lại
                </a>
            </form>
        </div>
    </div>
</div>
@endsection
