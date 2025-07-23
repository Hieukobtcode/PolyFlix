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
                            {{-- Chọn chi nhánh --}}
                            @if (Auth::user()->vai_tro_id == 1)
                                <div class="mb-3">
                                    <label class="form-label">Chọn chi nhánh</label>
                                    <select id="select-chi-nhanh" class="form-select">
                                        <option value="">-- Chọn chi nhánh --</option>
                                        @foreach ($chiNhanhs as $cn)
                                            <option value="{{ $cn->id }}" data-ten="{{ $cn->ten_chi_nhanh }}"
                                                {{ $doAn->chiNhanhs->pluck('id')->contains($cn->id) ? 'disabled' : '' }}>
                                                {{ $cn->ten_chi_nhanh }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Chi nhánh đã chọn --}}
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
                            @endif
                            @if (Auth::user()->vai_tro_id == 2)
                                {{-- Chọn rạp --}}
                                <div class="mb-3">
                                    <label class="form-label">Chọn rạp</label>
                                    <select id="select-rap" class="form-select">
                                        <option value="">-- Chọn rạp --</option>
                                        @foreach ($rapPhims as $rap)
                                            <option value="{{ $rap->id }}" data-ten="{{ $rap->ten_rap }}"
                                                {{ $doAn->rapPhims->pluck('id')->contains($rap->id) ? 'disabled' : '' }}>
                                                {{ $rap->ten_rap }}
                                                ({{ $rap->chiNhanh?->ten_chi_nhanh ?? 'Không có chi nhánh' }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Rạp đã chọn --}}
<div class="mb-3">
    <label class="form-label">Rạp đã chọn</label>
    <ul id="selected-raps" class="list-group">
        @foreach ($doAn->rapPhims as $rap)
            <li class="list-group-item d-flex justify-content-between align-items-center"
                data-id="{{ $rap->id }}">
                <span>{{ $rap->ten_rap }}
                    ({{ $rap->chiNhanh?->ten_chi_nhanh ?? 'Không có chi nhánh' }})
                </span>
                <button type="button" class="btn btn-sm btn-danger btn-xoa-rap"
                    data-id="{{ $rap->id }}">
                    Xoá
                </button>
            </li>
            <input type="hidden" name="rap_phim_ids[]" value="{{ $rap->id }}"
                id="input-rap-{{ $rap->id }}">
        @endforeach
    </ul>
    <div id="hidden-rap-inputs"></div> {{-- nơi JS append input --}}
</div>

                            @endif

                            {{-- Hidden --}}
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
        const hiddenChiNhanhInputs = document.getElementById('hidden-chi-nhanh-inputs');
        const selectedChiNhanhIds = new Set(
            Array.from(document.querySelectorAll('input[name="chi_nhanh_ids[]"]')).map(input => input.value)
        );

        const selectRap = document.getElementById('select-rap');
        const danhSachRap = document.getElementById('selected-raps');
        const hiddenRapInputs = document.getElementById('hidden-rap-inputs');
        const selectedRapIds = new Set(
            Array.from(document.querySelectorAll('input[name="rap_phim_ids[]"]')).map(input => input.value)
        );

        // ===== Xử lý chọn chi nhánh =====
        if (selectChiNhanh) {
            selectChiNhanh.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const id = selectedOption.value;
                const ten = selectedOption.dataset.ten;

                if (!id || selectedChiNhanhIds.has(id)) return;

                selectedChiNhanhIds.add(id);
                selectedOption.disabled = true;
                this.value = '';

                const div = document.createElement('div');
                div.className = 'd-flex align-items-center justify-content-between border rounded px-3 py-2 mb-2';
                div.dataset.id = id;

                const name = document.createElement('div');
                name.className = 'flex-grow-1 text-truncate';
                name.textContent = ten;

                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'btn btn-sm btn-outline-danger ms-3';
                btn.innerHTML = '<i class="ti ti-x"></i>';

                div.appendChild(name);
                div.appendChild(btn);
                danhSachChiNhanh.appendChild(div);

                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'chi_nhanh_ids[]';
                input.value = id;
                input.id = 'input-chi_nhanh-' + id;
                hiddenChiNhanhInputs.appendChild(input);
            });

            // Delegate XÓA CHI NHÁNH
            danhSachChiNhanh.addEventListener('click', function(e) {
                if (e.target.closest('.btn-outline-danger')) {
                    const div = e.target.closest('[data-id]');
                    const id = div.dataset.id;
                    removeChiNhanh(id);
                }
            });
        }

        window.removeChiNhanh = function(id) {
            selectedChiNhanhIds.delete(id);
            document.querySelector(`div[data-id="${id}"]`)?.remove();
            document.getElementById('input-chi_nhanh-' + id)?.remove();
            const option = selectChiNhanh.querySelector(`option[value="${id}"]`);
            if (option) option.disabled = false;
        };

        // ===== Xử lý chọn rạp =====
        if (selectRap) {
            selectRap.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const id = selectedOption.value;
                const ten = selectedOption.dataset.ten;

                if (!id || selectedRapIds.has(id)) return;

                selectedRapIds.add(id);
                selectedOption.disabled = true;
                this.value = '';

                const li = document.createElement('li');
                li.className = 'list-group-item d-flex justify-content-between align-items-center';
                li.dataset.id = id;

                const span = document.createElement('span');
                span.textContent = ten;

                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'btn btn-sm btn-danger btn-xoa-rap';
                btn.dataset.id = id;
                btn.textContent = 'Xoá';

                li.appendChild(span);
                li.appendChild(btn);
                danhSachRap.appendChild(li);

                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'rap_phim_ids[]';
                input.value = id;
                input.id = 'input-rap-' + id;
                hiddenRapInputs.appendChild(input);
            });

            // Delegate XÓA RẠP
            danhSachRap.addEventListener('click', function(e) {
                if (e.target.closest('.btn-xoa-rap')) {
                    const li = e.target.closest('[data-id]');
                    const id = li.dataset.id;
                    removeRap(id);
                }
            });
        }

        window.removeRap = function(id) {
            selectedRapIds.delete(id);
            document.querySelector(`li[data-id="${id}"]`)?.remove();
            document.getElementById('input-rap-' + id)?.remove();
            const option = selectRap.querySelector(`option[value="${id}"]`);
            if (option) option.disabled = false;
        };
    });
</script>


