@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <form action="{{ isset($combo) ? route('admin.combos.update', $combo->id) : route('admin.combos.store') }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    @if (isset($combo))
                        @method('PUT')
                    @endif

                    <div class="row">
                        <!-- Cột trái -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Tiêu đề</label>
                                <input type="text" name="tieu_de"
                                    class="form-control @error('tieu_de') is-invalid @enderror"
                                    value="{{ old('tieu_de', $combo->tieu_de ?? '') }}" required>
                                @error('tieu_de')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Mô tả</label>
                                <textarea name="noi_dung" rows="3" class="form-control @error('noi_dung') is-invalid @enderror">{{ old('noi_dung', $combo->noi_dung ?? '') }}</textarea>
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
                                @if (isset($combo) && $combo->hinh_anh)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/' . $combo->hinh_anh) }}" alt="Hình combo"
                                            style="max-height: 150px;">
                                    </div>
                                @endif
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Giá (VNĐ)</label>
                                <input type="number" name="gia" id="gia" class="form-control" value="0"
                                    readonly>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Giá sau giảm (Combo)</label>
                                <input type="number" name="gia_combo"
                                    class="form-control @error('gia_combo') is-invalid @enderror"
                                    value="{{ old('gia_combo', $combo->gia_combo ?? 0) }}" min="0" step="1000">
                                @error('gia_combo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Trạng thái</label>
                                <select name="trang_thai" class="form-select">
                                    <option value="hien"
                                        {{ old('trang_thai', $combo->trang_thai ?? 'hien') == 'hien' ? 'selected' : '' }}>
                                        Hiện</option>
                                    <option value="an"
                                        {{ old('trang_thai', $combo->trang_thai ?? '') == 'an' ? 'selected' : '' }}>Ẩn
                                    </option>
                                </select>
                            </div>
                        </div>

                        <!-- Cột phải -->
                        <div class="col-md-6">
                            @php
                                $chiNhanhSelected = old(
                                    'chi_nhanh_ids',
                                    isset($combo) ? $combo->chiNhanhs->pluck('id')->toArray() : [],
                                );
                            @endphp

                            <div class="mb-3">
                                <label class="form-label">Chọn chi nhánh</label>
                                <select id="select-chi-nhanh" class="form-select">
                                    <option value="">-- Chọn chi nhánh --</option>
                                    @foreach ($chiNhanhs as $cn)
                                        <option value="{{ $cn->id }}" data-ten="{{ $cn->ten_chi_nhanh }}">
                                            {{ $cn->ten_chi_nhanh }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Chi nhánh đã chọn</label>
                                <ul id="selected-branches" class="list-group">
                                    @foreach ($chiNhanhSelected as $id)
                                        @php
                                            $cn = $chiNhanhs->where('id', $id)->first();
                                        @endphp
                                        <li class="list-group-item d-flex justify-content-between align-items-center"
                                            data-id="{{ $id }}">
                                            <span>{{ $cn->ten_chi_nhanh }}</span>
                                            <button type="button" class="btn btn-sm btn-danger btn-xoa-cn"
                                                data-id="{{ $id }}">Xoá</button>
                                        </li>
                                        <input type="hidden" name="chi_nhanh_ids[]" value="{{ $id }}"
                                            id="input-chi_nhanh-{{ $id }}">
                                    @endforeach
                                </ul>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Chọn món ăn</label>
                                <select id="chon-do-an" class="form-select">
                                    <option value="">-- Chọn món --</option>
                                    @foreach ($doAns as $doAn)
                                        <option value="{{ $doAn->id }}" data-ten="{{ $doAn->tieu_de }}"
                                            data-gia="{{ $doAn->gia }}">
                                            {{ $doAn->tieu_de }} ({{ number_format($doAn->gia) }} đ)
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <ul id="danh-sach-do-an" class="list-group mb-3">
                                @if (isset($combo))
                                    @foreach ($combo->doAns as $doAn)
                                        <li class="list-group-item d-flex justify-content-between align-items-center"
                                            data-id="{{ $doAn->id }}" data-gia="{{ $doAn->gia }}">
                                            <div><strong>{{ $doAn->tieu_de }}</strong>
                                                <div class="text-muted small">{{ number_format($doAn->gia) }} đ</div>
                                            </div>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="input-group input-group-sm">
                                                    <button type="button" class="btn btn-outline-secondary btn-giam"
                                                        data-id="{{ $doAn->id }}">-</button>
                                                    <input type="number" name="do_ans[{{ $doAn->id }}][so_luong]"
                                                        class="form-control text-center so-luong"
                                                        value="{{ $doAn->pivot->so_luong }}" min="1"
                                                        style="width:60px;">
                                                    <button type="button" class="btn btn-outline-secondary btn-tang"
                                                        data-id="{{ $doAn->id }}">+</button>
                                                </div>
                                                <button type="button" class="btn btn-sm btn-danger xoa-mon"
                                                    data-id="{{ $doAn->id }}">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </div>
                                        </li>
                                        <input type="hidden" name="do_ans[{{ $doAn->id }}][selected]"
                                            value="1" id="input-selected-{{ $doAn->id }}">
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('admin.combos.index') }}" class="btn btn-outline-secondary">
                            <i class="ti ti-arrow-left me-1"></i> Quay lại
                        </a>
                        <button type="submit" class="btn btn-{{ isset($combo) ? 'primary' : 'success' }} px-4">
                            {{ isset($combo) ? 'Cập nhật' : 'Lưu combo' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ======== MÓN ĂN =========
            const selectDoAn = document.getElementById('chon-do-an');
            const list = document.getElementById('danh-sach-do-an');
            const inputs = document.getElementById('inputs-hidden');
            const giaInput = document.getElementById('gia');

            let selected = new Set();

            // Đọc sẵn các món đã chọn (trường hợp edit)
            document.querySelectorAll('input[id^="input-selected-"]').forEach(input => {
                const id = input.id.replace('input-selected-', '');
                selected.add(id);
            });

            function capNhatTongGia() {
                let tongGia = 0;
                list.querySelectorAll('li').forEach(li => {
                    const gia = parseFloat(li.dataset.gia);
                    const soLuong = parseInt(li.querySelector('.so-luong').value);
                    tongGia += gia * soLuong;
                });
                giaInput.value = tongGia.toFixed(0);
            }

            selectDoAn.addEventListener('change', function() {
                const option = this.options[this.selectedIndex];
                const id = option.value;
                const ten = option.dataset.ten;
                const gia = parseFloat(option.dataset.gia);

                if (!id || selected.has(id)) return;

                selected.add(id);

                const li = document.createElement('li');
                li.className = 'list-group-item d-flex justify-content-between align-items-center';
                li.dataset.id = id;
                li.dataset.gia = gia;

                li.innerHTML = `
                <div><strong>${ten}</strong><div class="text-muted small">${gia.toLocaleString()} đ</div></div>
                <div class="d-flex align-items-center gap-2">
                    <div class="input-group input-group-sm">
                        <button type="button" class="btn btn-outline-secondary btn-giam" data-id="${id}">-</button>
                        <input type="number" name="do_ans[${id}][so_luong]" class="form-control text-center so-luong" value="1" min="1" style="width:60px;">
                        <button type="button" class="btn btn-outline-secondary btn-tang" data-id="${id}">+</button>
                    </div>
                    <button type="button" class="btn btn-sm btn-danger xoa-mon" data-id="${id}">
                        <i class="ti ti-trash"></i>
                    </button>
                </div>
            `;

                list.appendChild(li);

                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = `do_ans[${id}][selected]`;
                input.value = 1;
                input.id = `input-selected-${id}`;
                inputs.appendChild(input);

                capNhatTongGia();
                this.value = '';
            });

            list.addEventListener('click', function(e) {
                const btn = e.target.closest('button');
                if (!btn) return;

                const id = btn.dataset.id;
                const input = list.querySelector(`li[data-id="${id}"] input.so-luong`);
                let sl = parseInt(input.value);

                if (btn.classList.contains('btn-giam')) {
                    if (sl > 1) sl--;
                }

                if (btn.classList.contains('btn-tang')) {
                    sl++;
                }

                if (btn.classList.contains('xoa-mon')) {
                    list.querySelector(`li[data-id="${id}"]`)?.remove();
                    document.getElementById(`input-selected-${id}`)?.remove();
                    selected.delete(id);
                    capNhatTongGia();
                    return;
                }

                input.value = sl;
                capNhatTongGia();
            });

            list.addEventListener('input', function(e) {
                if (e.target.classList.contains('so-luong')) {
                    capNhatTongGia();
                }
            });

            // ======== CHI NHÁNH =========
            const selectChiNhanh = document.getElementById('select-chi-nhanh');
            const listCN = document.getElementById('selected-branches');
            const inputsCN = document.getElementById('hidden-branch-inputs');
            const selectedCN = new Set();

            document.querySelectorAll('input[name="chi_nhanh_ids[]"]').forEach(input => {
                selectedCN.add(input.value);
            });

            selectChiNhanh.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const id = selectedOption.value;
                const ten = selectedOption.dataset.ten;

                if (!id || selectedCN.has(id)) return;

                selectedCN.add(id);

                const li = document.createElement('li');
                li.className = 'list-group-item d-flex justify-content-between align-items-center';
                li.dataset.id = id;
                li.innerHTML = `
                <span>${ten}</span>
                <button type="button" class="btn btn-sm btn-danger btn-xoa-cn" data-id="${id}">Xoá</button>
            `;

                li.querySelector('.btn-xoa-cn').addEventListener('click', function() {
                    selectedCN.delete(id);
                    li.remove();
                    document.getElementById('input-chi_nhanh-' + id)?.remove();
                });

                listCN.appendChild(li);

                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'chi_nhanh_ids[]';
                input.value = id;
                input.id = 'input-chi_nhanh-' + id;
                inputsCN.appendChild(input);

                selectChiNhanh.selectedIndex = 0;
            });

            // Tính giá lần đầu (trường hợp edit có sẵn món)
            capNhatTongGia();
        });
    </script>
@endsection
