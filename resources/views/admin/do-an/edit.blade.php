@extends('layouts.admin')


@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0">

            <div class="card-body p-4">
                <form action="{{ route('admin.do-an.update', $doAn->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <!-- Cột trái: Thông tin món ăn -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Tiêu đề</label>
                                <input type="text" name="tieu_de"
                                    class="form-control @error('tieu_de') is-invalid @enderror"
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
                                <input type="file" name="hinh_anh"
                                    class="form-control @error('hinh_anh') is-invalid @enderror">
                                @error('hinh_anh')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @if ($doAn->hinh_anh)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/' . $doAn->hinh_anh) }}" alt="Hình món ăn"
                                            style="max-height: 120px;">
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
                                <select name="danh_muc_id" class="form-select @error('danh_muc_id') is-invalid @enderror"
                                    required>
                                    <option value="">-- Chọn danh mục --</option>
                                    @foreach ($danhMucs as $dm)
                                        <option value="{{ $dm->id }}"
                                            {{ old('danh_muc_id', $doAn->danh_muc_id) == $dm->id ? 'selected' : '' }}>
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
                                    <option value="hien"
                                        {{ old('trang_thai', $doAn->trang_thai) == 'hien' ? 'selected' : '' }}>Hiện
                                    </option>
                                    <option value="an"
                                        {{ old('trang_thai', $doAn->trang_thai) == 'an' ? 'selected' : '' }}>Ẩn</option>
                                </select>
                            </div>
                        </div>

                        <!-- Cột phải: Chọn chi nhánh -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Chọn chi nhánh</label>
                                <select id="select-chi-nhanh" class="form-select">
                                    <option value="">-- Chọn chi nhánh --</option>
                                    @foreach ($chiNhanhs as $cn)
                                        <option value="{{ $cn->id }}" data-ten="{{ $cn->ten_chi_nhanh }}"
                                            {{ in_array($cn->id, $doAn->chiNhanhs->pluck('id')->toArray()) ? 'disabled' : '' }}>
                                            {{ $cn->ten_chi_nhanh }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Chi nhánh đã chọn</label>
                                <div id="danh-sach-chi-nhanh" class="border rounded p-3">
                                    @foreach ($doAn->chiNhanhs as $cn)
                                        <div class="d-flex align-items-center justify-content-between border rounded px-3 py-2 mb-2"
                                            data-id="{{ $cn->id }}">
                                            <div class="flex-grow-1 text-truncate">{{ $cn->ten_chi_nhanh }}</div>
                                            <button type="button" class="btn btn-sm btn-outline-danger ms-3"
                                                onclick="removeChiNhanh({{ $cn->id }})">
                                                <i class="ti ti-x"></i>
                                            </button>
                                        </div>
                                        <input type="hidden" name="chi_nhanh_ids[]" value="{{ $cn->id }}"
                                            id="input-chi_nhanh-{{ $cn->id }}">
                                    @endforeach
                                </div>
                            </div>

                            <div id="hidden-chi-nhanh-inputs"></div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('admin.do-an.index') }}" class="btn btn-secondary">
                            <i class="ti ti-arrow-left me-1"></i> Quay lại
                        </a>
                        <button type="submit" class="btn btn-primary">
                            Cập nhật
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
        const selectedIds = new Set(
            Array.from(document.querySelectorAll('input[name="chi_nhanh_ids[]"]')).map(input => input.value)
        );

        selectChiNhanh.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const id = selectedOption.value;
            const ten = selectedOption.dataset.ten;

            if (!id || selectedIds.has(id)) return;

            selectedIds.add(id);
            selectedOption.disabled = true;
            this.value = '';

            const div = document.createElement('div');
            div.className =
                'd-flex align-items-center justify-content-between border rounded px-3 py-2 mb-2';
            div.dataset.id = id;

            const name = document.createElement('div');
            name.className = 'flex-grow-1 text-truncate';
            name.textContent = ten;

            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'btn btn-sm btn-outline-danger ms-3';
            btn.innerHTML = '<i class="ti ti-x"></i>';
            btn.addEventListener('click', () => {
                selectedIds.delete(id);
                div.remove();
                document.getElementById('input-chi_nhanh-' + id)?.remove();
                const option = selectChiNhanh.querySelector(`option[value="${id}"]`);
                if (option) option.disabled = false;
            });

            div.appendChild(name);
            div.appendChild(btn);
            danhSachChiNhanh.appendChild(div);

            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'chi_nhanh_ids[]';
            input.value = id;
            input.id = 'input-chi_nhanh-' + id;
            hiddenInputs.appendChild(input);
        });

        window.removeChiNhanh = function(id) {
            selectedIds.delete(id);
            document.querySelector(`div[data-id="${id}"]`)?.remove();
            document.getElementById('input-chi_nhanh-' + id)?.remove();
            const option = selectChiNhanh.querySelector(`option[value="${id}"]`);
            if (option) option.disabled = false;
        };
    });
</script>
