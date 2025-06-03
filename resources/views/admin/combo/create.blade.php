@extends('layouts.admin')

@section('title', 'Thêm Combo')
@section('page-title', 'Thêm Combo')
@section('breadcrumb', 'Thêm mới')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0 fw-bold">Thêm combo mới</h5>
            </div>

            <div class="card-body p-4">
                <form action="{{ route('admin.combos.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Tiêu đề</label>
                        <input type="text" name="tieu_de" class="form-control @error('tieu_de') is-invalid @enderror"
                            value="{{ old('tieu_de') }}" required>
                        @error('tieu_de')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mô tả</label>
                        <textarea name="noi_dung" rows="3" class="form-control @error('noi_dung') is-invalid @enderror">{{ old('noi_dung') }}</textarea>
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
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Giá (VNĐ)</label>
                       <input type="number" name="gia" id="gia" class="form-control" value="0" readonly>
                        @error('gia')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Giá sau giảm (Combo)</label>
                        <input type="number" name="gia_combo" class="form-control"
                            value="{{ old('gia_combo', $combo->gia_combo ?? 0) }}" min="0" step="1000">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Chọn món ăn</label>
                        <select name="do_an_ids[]" id="do_an_ids" class="form-select" multiple size="8">
                            @foreach ($doAns as $doAn)
                                <option value="{{ $doAn->id }}" data-gia="{{ $doAn->gia }}">
                                    {{ $doAn->tieu_de }} ({{ number_format($doAn->gia) }} đ)
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Giữ Ctrl (hoặc Cmd) để chọn nhiều món</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Trạng thái</label>
                        <select name="trang_thai" class="form-select">
                            <option value="hien" {{ old('trang_thai') == 'hien' ? 'selected' : '' }}>Hiện</option>
                            <option value="an" {{ old('trang_thai') == 'an' ? 'selected' : '' }}>Ẩn</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-1"></i> Lưu combo
                    </button>
                    <a href="{{ route('admin.combos.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Quay lại
                    </a>
                </form>
            </div>
        </div>
    </div>
@endsection
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const select = document.getElementById('do_an_ids');
        const giaInput = document.getElementById('gia');

        function tinhTongGia() {
            let tong = 0;
            for (const option of select.selectedOptions) {
                tong += parseFloat(option.getAttribute('data-gia')) || 0;
            }
            giaInput.value = tong.toFixed(0);
        }

        select.addEventListener('change', tinhTongGia);
    });
</script>
