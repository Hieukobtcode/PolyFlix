@extends('layouts.admin')

@section('title', 'Thêm Combo')
@section('page-title', 'Thêm Combo')
@section('breadcrumb', 'Thêm mới')

@section('content')
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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
                        <label class="form-label">Hình ảnh đồ ăn</label>
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
                        <select id="select-mon-an" class="form-select">
                            <option value="">-- Chọn món --</option>
                            @foreach ($doAns as $doAn)
                                <option value="{{ $doAn->id }}" data-gia="{{ $doAn->gia }}"
                                    data-ten="{{ $doAn->tieu_de }}">
                                    {{ $doAn->tieu_de }} ({{ number_format($doAn->gia) }} đ)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Hidden input để submit các ID món ăn đã chọn -->
                    <div id="selected-inputs"></div>

                    <!-- Danh sách món ăn đã chọn -->
                    <div class="mb-3">
                        <label class="form-label">Món ăn đã chọn</label>
                        <ul id="selected-food-list" class="list-group"></ul>
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
        const select = document.getElementById('select-mon-an');
        const foodList = document.getElementById('selected-food-list');
        const selectedInputs = document.getElementById('selected-inputs');
        const giaInput = document.getElementById('gia');

        let selectedIds = new Set();
        let tongGia = 0;

        function capNhatGia() {
            giaInput.value = tongGia.toFixed(0);
        }

        select.addEventListener('change', function () {
            const option = select.options[select.selectedIndex];
            const id = option.value;
            const ten = option.dataset.ten;
            const gia = parseFloat(option.dataset.gia) || 0;

            if (id && !selectedIds.has(id)) {
                selectedIds.add(id);
                tongGia += gia;
                capNhatGia();

                // Thêm vào danh sách hiển thị
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
            }

            select.selectedIndex = 0;
        });

        // Sự kiện xóa món
        foodList.addEventListener('click', function (e) {
            if (e.target.classList.contains('btn-xoa-mon')) {
                const id = e.target.dataset.id;
                const gia = parseFloat(e.target.dataset.gia) || 0;

                // Xóa khỏi danh sách
                const li = foodList.querySelector(`li[data-id="${id}"]`);
                if (li) li.remove();

                // Xóa input hidden
                const input = document.getElementById('input-do-an-' + id);
                if (input) input.remove();

                // Cập nhật dữ liệu
                selectedIds.delete(id);
                tongGia -= gia;
                capNhatGia();
            }
        });
    });
</script>
