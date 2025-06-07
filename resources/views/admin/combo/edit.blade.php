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
                                <img src="{{ asset('storage/' . $combo->hinh_anh) }}" alt="Hình combo"
                                    style="max-height: 150px;">
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
                        <select id="select-mon-an" class="form-select">
                            <option value="">-- Chọn món --</option>
                            @foreach ($doAns as $doAn)
                                <option value="{{ $doAn->id }}" data-gia="{{ $doAn->gia }}"
                                    data-ten="{{ $doAn->tieu_de }}"
                                    {{ in_array($doAn->id, old('do_an_ids', $combo->doAns->pluck('id')->toArray())) ? 'disabled' : '' }}>
                                    {{ $doAn->tieu_de }} ({{ number_format($doAn->gia) }} đ)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Hidden inputs -->
                    <div id="selected-inputs">
                        @foreach ($combo->doAns as $doAn)
                            <input type="hidden" name="do_an_ids[]" value="{{ $doAn->id }}"
                                id="input-do-an-{{ $doAn->id }}">
                        @endforeach
                    </div>

                    <!-- Danh sách món ăn đã chọn -->
                    <div class="mb-3">
                        <label class="form-label">Món ăn đã chọn</label>
                        <ul id="selected-food-list" class="list-group">
                            @foreach ($combo->doAns as $doAn)
                                <li class="list-group-item d-flex justify-content-between align-items-center"
                                    data-id="{{ $doAn->id }}">
                                    <span>{{ $doAn->tieu_de }} - {{ number_format($doAn->gia) }} đ</span>
                                    <button type="button" class="btn btn-sm btn-danger btn-xoa-mon"
                                        data-id="{{ $doAn->id }}" data-gia="{{ $doAn->gia }}">
                                        Xóa
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                    </div>



                    <div class="mb-3">
                        <label class="form-label">Trạng thái</label>
                        <select name="trang_thai" class="form-select">
                            <option value="hien" {{ old('trang_thai', $combo->trang_thai) == 'hien' ? 'selected' : '' }}>
                                Hiện</option>
                            <option value="an" {{ old('trang_thai', $combo->trang_thai) == 'an' ? 'selected' : '' }}>Ẩn
                            </option>
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
@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const select = document.getElementById('select-mon-an');
            const foodList = document.getElementById('selected-food-list');
            const selectedInputs = document.getElementById('selected-inputs');
            const giaInput = document.getElementById('gia');

            let selectedIds = new Set(@json($combo->doAns->pluck('id'))); // Món đã chọn
            let tongGia = @json($combo->doAns->sum('gia'));

            function capNhatGia() {
                giaInput.value = Math.round(tongGia);
            }

            select.addEventListener('change', function() {
                const option = select.options[select.selectedIndex];
                const id = option.value;
                const ten = option.dataset.ten;
                const gia = parseFloat(option.dataset.gia) || 0;

                if (id && !selectedIds.has(id)) {
                    selectedIds.add(id);
                    tongGia += gia;
                    capNhatGia();

                    // Thêm dòng món ăn
                    const li = document.createElement('li');
                    li.className = 'list-group-item d-flex justify-content-between align-items-center';
                    li.dataset.id = id;
                    li.innerHTML = `
                    <span>${ten} - ${gia.toLocaleString()} đ</span>
                    <button type="button" class="btn btn-sm btn-danger btn-xoa-mon" data-id="${id}" data-gia="${gia}">Xóa</button>
                `;
                    foodList.appendChild(li);

                    // Tạo input hidden
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'do_an_ids[]';
                    input.value = id;
                    input.id = 'input-do-an-' + id;
                    selectedInputs.appendChild(input);

                    // Disable option
                    option.disabled = true;
                    select.selectedIndex = 0;
                }
            });

            // Xử lý xóa món
            foodList.addEventListener('click', function(e) {
                if (e.target.classList.contains('btn-xoa-mon')) {
                    const id = e.target.dataset.id;
                    const gia = parseFloat(e.target.dataset.gia) || 0;

                    const li = foodList.querySelector(`li[data-id="${id}"]`);
                    if (li) li.remove();

                    const input = document.getElementById('input-do-an-' + id);
                    if (input) input.remove();

                    selectedIds.delete(id);
                    tongGia -= gia;
                    capNhatGia();

                    // Re-enable lại option trong select
                    const option = select.querySelector(`option[value="${id}"]`);
                    if (option) option.disabled = false;
                }
            });

            // Khởi động tổng giá
            capNhatGia();
        });
    </script>
@endsection
