@extends('layouts.admin')

@section('title', 'Cập nhật Combo')
@section('page-title', 'Cập nhật Combo')
@section('breadcrumb', 'Chỉnh sửa')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-warning text-white">
            <h5 class="mb-0 fw-bold">Cập nhật combo</h5>
        </div>

        <div class="card-body p-4">
            <form action="{{ route('admin.combos.update', $combo->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Tiêu đề</label>
                    <input type="text" name="tieu_de" class="form-control @error('tieu_de') is-invalid @enderror"
                           value="{{ old('tieu_de', $combo->tieu_de) }}" required>
                    @error('tieu_de')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Mô tả</label>
                    <textarea name="noi_dung" rows="3" class="form-control @error('noi_dung') is-invalid @enderror">{{ old('noi_dung', $combo->noi_dung) }}</textarea>
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

                    @if ($combo->hinh_anh)
                        <div class="mt-2">
                            <img src="{{ asset('storage/' . $combo->hinh_anh) }}" alt="Hình combo" style="max-height: 150px;">
                        </div>
                    @endif
                </div>

                <div class="mb-3">
                    <label class="form-label">Giá gốc (VNĐ)</label>
                   <input type="number" name="gia" id="gia" class="form-control" readonly>
                    @error('gia')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Giá sau giảm (Combo)</label>
                    <input type="number" name="gia_combo" class="form-control @error('gia_combo') is-invalid @enderror"
                           value="{{ old('gia_combo', $combo->gia_combo) }}" min="0" step="1000">
                    @error('gia_combo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
    <label class="form-label">Chọn món ăn</label>
    <select name="do_an_ids[]" id="do_an_ids" class="form-select" multiple size="8">
        @foreach($doAns as $doAn)
            <option value="{{ $doAn->id }}"
                data-gia="{{ $doAn->gia }}"
                {{ in_array($doAn->id, old('do_an_ids', $combo->doAns->pluck('id')->toArray())) ? 'selected' : '' }}>
                {{ $doAn->tieu_de }} ({{ number_format($doAn->gia) }} đ)
            </option>
        @endforeach
    </select>
    <small class="text-muted">Giữ Ctrl (hoặc Cmd) để chọn nhiều món</small>
</div>


                <div class="mb-3">
                    <label class="form-label">Trạng thái</label>
                    <select name="trang_thai" class="form-select">
                        <option value="hien" {{ old('trang_thai', $combo->trang_thai) == 'hien' ? 'selected' : '' }}>Hiện</option>
                        <option value="an" {{ old('trang_thai', $combo->trang_thai) == 'an' ? 'selected' : '' }}>Ẩn</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Cập nhật
                </button>
                <a href="{{ route('admin.combos.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Quay lại
                </a>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const checkboxes = document.querySelectorAll('.do-an-checkbox');
        const giaInput = document.getElementById('gia');

        function updateGia() {
            let total = 0;
            checkboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    total += parseFloat(checkbox.getAttribute('data-gia')) || 0;
                }
            });
            giaInput.value = Math.toFixed(total);
            
        }

        checkboxes.forEach(cb => cb.addEventListener('change', updateGia));

        // Tính giá khi load nếu có sẵn
        updateGia();
    });
</script>
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
            giaInput.value = Math.round(tong);
        }

        select.addEventListener('change', tinhTongGia);
        tinhTongGia(); // tính khi load
    });
</script>
