@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <form action="{{ route('admin.do-an.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <!-- Cột trái: thông tin món ăn -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tieu_de" class="form-label">Tiêu đề</label>
                                <input type="text" name="tieu_de"
                                    class="form-control @error('tieu_de') is-invalid @enderror" value="{{ old('tieu_de') }}"
                                    required>
                                @error('tieu_de')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="noi_dung" class="form-label">Mô tả / Nội dung</label>
                                <textarea name="noi_dung" rows="4" class="form-control @error('noi_dung') is-invalid @enderror">{{ old('noi_dung') }}</textarea>
                                @error('noi_dung')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="hinh_anh" class="form-label">Hình ảnh</label>
                                <input type="file" name="hinh_anh"
                                    class="form-control @error('hinh_anh') is-invalid @enderror">
                                @error('hinh_anh')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="gia" class="form-label">Giá (VNĐ)</label>
                                <input type="number" name="gia" class="form-control @error('gia') is-invalid @enderror"
                                    value="{{ old('gia') }}" min="0" step="1000" required>
                                @error('gia')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="danh_muc_id" class="form-label">Danh mục</label>
                                <select name="danh_muc_id" class="form-select @error('danh_muc_id') is-invalid @enderror"
                                    required>
                                    <option value="">-- Chọn danh mục --</option>
                                    @foreach ($danhMucs as $dm)
                                        <option value="{{ $dm->id }}"
                                            {{ old('danh_muc_id') == $dm->id ? 'selected' : '' }}>
                                            {{ $dm->ten }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('danh_muc_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="trang_thai" class="form-label">Trạng thái</label>
                                <select name="trang_thai" class="form-select">
                                    <option value="hien" {{ old('trang_thai') == 'hien' ? 'selected' : '' }}>Hiện
                                    </option>
                                    <option value="an" {{ old('trang_thai') == 'an' ? 'selected' : '' }}>Ẩn</option>
                                </select>
                            </div>
                        </div>

                        <!-- Cột phải: chọn chi nhánh -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Chọn chi nhánh</label>
                                <select id="select-chi-nhanh" class="form-select">
                                    <option value="">-- Chọn chi nhánh --</option>
                                    @foreach ($chiNhanhs as $cn)
                                        <option value="{{ $cn->id }}" data-ten="{{ $cn->ten_chi_nhanh }}">
                                            {{ $cn->ten_chi_nhanh }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Chi nhánh đã chọn</label>
                                <div id="danh-sach-chi-nhanh" class="border rounded p-3" style="min-height: 140px;">
                                    <!-- Chi nhánh đã chọn sẽ hiển thị ở đây -->
                                </div>
                            </div>

                            <div id="hidden-chi-nhanh-inputs"></div>
                        </div>

                    </div>

                    <!-- Hành động -->
                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('admin.do-an.index') }}" class="btn btn-outline-secondary">
                            <i class="ti ti-arrow-left me-1"></i> Quay lại
                        </a>
                        <button type="submit" class="btn btn-success px-4">
                            <i class="ti ti-device-floppy me-1"></i> Lưu món ăn
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectChiNhanh = document.getElementById('select-chi-nhanh');
        const danhSachChiNhanh = document.getElementById('danh-sach-chi-nhanh');
        const hiddenInputs = document.getElementById('hidden-chi-nhanh-inputs');
        const selectedIds = new Set();

        selectChiNhanh.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const id = selectedOption.value;
            const ten = selectedOption.dataset.ten;

            if (!id || selectedIds.has(id)) return;

            selectedIds.add(id);
            selectedOption.disabled = true;
            this.value = '';

            // Tạo container hiển thị chi nhánh + nút xóa
            const item = document.createElement('div');
            item.className =
                'd-flex align-items-center justify-content-between border rounded px-3 py-2 mb-2';
            item.dataset.id = id;
            item.id = 'chi-nhanh-' + id;

            const name = document.createElement('div');
            name.className = 'flex-grow-1 text-truncate';
            name.textContent = ten;

            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'btn btn-sm btn-outline-danger ms-3 flex-shrink-0';
            btn.innerHTML = '<i class="ti ti-x"></i>';

            btn.addEventListener('click', () => {
                selectedIds.delete(id);
                item.remove();
                document.getElementById('input-chi_nhanh-' + id)?.remove();

                // Enable lại option
                const optionToEnable = selectChiNhanh.querySelector(`option[value="${id}"]`);
                if (optionToEnable) optionToEnable.disabled = false;
            });

            item.appendChild(name);
            item.appendChild(btn);
            danhSachChiNhanh.appendChild(item);

            // Input hidden để submit về server
            const hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = 'chi_nhanh_ids[]';
            hidden.value = id;
            hidden.id = 'input-chi_nhanh-' + id;
            hiddenInputs.appendChild(hidden);
        });
    });
</script>
