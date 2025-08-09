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

                            @if (Auth::user()->vai_tro_id == 1)
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
                                            @php $cn = $chiNhanhs->where('id', $id)->first(); @endphp
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
                                    <div id="hidden-branch-inputs"></div> {{-- để JS append input mới --}}
                                </div>
                            @elseif(Auth::user()->vai_tro_id == 2)
                                <div class="mb-3">
                                    <label class="form-label">Chọn rạp</label>
                                    <select id="select-rap" class="form-select">
                                        <option value="">-- Chọn rạp --</option>
                                        @foreach ($rapPhims as $rap)
                                            <option value="{{ $rap->id }}" data-ten="{{ $rap->ten_rap }}">
                                                {{ $rap->ten_rap }} ({{ $rap->chiNhanh->ten_chi_nhanh }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Rạp đã chọn</label>
                                    <ul id="selected-raps" class="list-group">
                                        @foreach ($rapPhimSelected as $id)
                                            @php $rap = $rapPhims->where('id', $id)->first(); @endphp
                                            @if ($rap)
                                                <li class="list-group-item d-flex justify-content-between align-items-center"
                                                    data-id="{{ $id }}">
                                                    <span>{{ $rap->ten_rap }} ({{ $rap->chiNhanh->ten_chi_nhanh }})</span>
                                                    <button type="button" class="btn btn-sm btn-danger btn-xoa-rap"
                                                        data-id="{{ $id }}">Xoá</button>
                                                </li>
                                                <input type="hidden" name="rap_phim_ids[]" value="{{ $id }}"
                                                    id="input-rap-{{ $id }}">
                                            @endif
                                        @endforeach
                                    </ul>
                                    <div id="hidden-rap-inputs"></div>
                                </div>
                            @endif


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
            const vaiTroId = {{ Auth::user()->vai_tro_id }};
            const chiNhanhs = @json($chiNhanhs); // Pass từ controller
            const selectedChiNhanhIds = new Set();
            const selectedRapIds = new Set();

            // ===== Admin Tổng: Chỉnh sửa Chi nhánh =====
            if (vaiTroId === 1) {
                const selectChiNhanh = document.getElementById('select-chi-nhanh');
                const selectedBranches = document.getElementById('selected-branches');
                const hiddenBranchInputs = document.getElementById('hidden-branch-inputs');

                selectedBranches.querySelectorAll('li').forEach(li => {
                    selectedChiNhanhIds.add(li.dataset.id);
                });

                function attachRemoveChiNhanh(btn) {
                    btn.addEventListener('click', function() {
                        const id = this.dataset.id;

                        // Xoá khỏi Set đang lưu
                        selectedChiNhanhIds.delete(id);

                        // Xoá input hidden để form không gửi về
                        const hiddenInput = document.getElementById(`input-chi_nhanh-${id}`);
                        if (hiddenInput) hiddenInput.remove();

                        // Xoá li hiển thị
                        btn.closest('li').remove();

                        // Xoá tất cả rạp thuộc chi nhánh này
                        selectedRaps.querySelectorAll(`li[data-cn-id="${id}"]`).forEach(rLi => {
                            const rapId = rLi.dataset.id;
                            selectedRapIds.delete(rapId);
                            document.getElementById(`input-rap-${rapId}`)?.remove();
                            rLi.remove();
                        });

                        // Render lại dropdown rạp
                        renderRapsDropdown();
                    });
                }


                selectedBranches.querySelectorAll('.btn-xoa-cn').forEach(attachRemoveChiNhanh);

                selectChiNhanh.addEventListener('change', function() {
                    const id = this.value;
                    const ten = this.options[this.selectedIndex].dataset.ten;
                    if (!id || selectedChiNhanhIds.has(id)) return;

                    selectedChiNhanhIds.add(id);

                    const li = document.createElement('li');
                    li.className = 'list-group-item d-flex justify-content-between align-items-center';
                    li.dataset.id = id;
                    li.innerHTML = `
                    <span>${ten}</span>
                    <button type="button" class="btn btn-sm btn-danger btn-xoa-cn" data-id="${id}">Xoá</button>
                `;
                    selectedBranches.appendChild(li);

                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'chi_nhanh_ids[]';
                    input.value = id;
                    input.id = `input-chi_nhanh-${id}`;
                    hiddenBranchInputs.appendChild(input);

                    attachRemoveChiNhanh(li.querySelector('.btn-xoa-cn'));

                    selectChiNhanh.selectedIndex = 0;
                });
            }

            // ===== Admin Chi nhánh: Chỉnh sửa Rạp =====
            if (vaiTroId === 2) {
                const selectRap = document.getElementById('select-rap');
                const selectedRaps = document.getElementById('selected-raps');
                const hiddenRapInputs = document.getElementById('hidden-rap-inputs');

                selectedRaps.querySelectorAll('li').forEach(li => {
                    selectedRapIds.add(li.dataset.id);
                });

                function attachRemoveRap(btn) {
                    btn.addEventListener('click', function() {
                        const id = this.dataset.id;
                        selectedRapIds.delete(id);
                        document.getElementById(`input-rap-${id}`)?.remove();
                        btn.closest('li').remove();
                    });
                }

                selectedRaps.querySelectorAll('.btn-xoa-rap').forEach(attachRemoveRap);

                selectRap.disabled = false; // Enable dropdown

                selectRap.addEventListener('change', function() {
                    const rapId = this.value;
                    const rapText = this.options[this.selectedIndex].textContent;
                    if (!rapId || selectedRapIds.has(rapId)) return;

                    selectedRapIds.add(rapId);

                    const li = document.createElement('li');
                    li.className = 'list-group-item d-flex justify-content-between align-items-center';
                    li.dataset.id = rapId;
                    li.innerHTML = `
                    <span>${rapText}</span>
                    <button type="button" class="btn btn-sm btn-danger btn-xoa-rap" data-id="${rapId}">Xoá</button>
                `;
                    selectedRaps.appendChild(li);

                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'rap_phim_ids[]';
                    input.value = rapId;
                    input.id = `input-rap-${rapId}`;
                    hiddenRapInputs.appendChild(input);

                    attachRemoveRap(li.querySelector('.btn-xoa-rap'));

                    selectRap.selectedIndex = 0;
                });
            }
        });
    </script>
@endsection
