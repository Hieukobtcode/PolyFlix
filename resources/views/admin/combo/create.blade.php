@extends('layouts.admin')

@section('title', 'Thêm Combo')
@section('page-title', 'Thêm Combo')
@section('breadcrumb', 'Thêm mới')

<style>
    #selected-food-list .list-group-item {
        border: 1px solid #dee2e6;
        border-radius: 8px;
        margin-bottom: 8px;
        padding: 12px 16px;
        background-color: #fffdfd;
        transition: all 0.2s ease-in-out;
    }

    #selected-food-list .list-group-item:hover {
        background-color: #f9f9f9;
    }

    .input-group-sm .btn {
        padding: 0.25rem 0.6rem;
    }

    .so-luong-input {
        max-width: 40px;
    }
</style>

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

                    <!-- Hiển thị danh sách chi nhánh đã chọn -->
                    <div class="mb-3">
                        <label class="form-label">Chi nhánh đã chọn</label>
                        <ul id="selected-branches" class="list-group"></ul>
                    </div>

                    <!-- Input hidden để submit -->
                    <div id="hidden-branch-inputs"></div>

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

                    <!-- Hidden input để submit các ID món ăn đã chọn -->
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

                    <ul id="danh-sach-do-an" class="list-group mb-3"></ul>
                    <div id="inputs-hidden"></div>
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
    document.addEventListener('DOMContentLoaded', function() {
        const selectDoAn = document.getElementById('chon-do-an');
        const list = document.getElementById('danh-sach-do-an');
        const inputs = document.getElementById('inputs-hidden');
        const giaInput = document.getElementById('gia');

        let selected = new Set();

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
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        `;

            list.appendChild(li);

            // input hidden để báo là món này đã được chọn
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = `do_ans[${id}][selected]`;
            input.value = 1;
            input.id = `input-selected-${id}`;
            inputs.appendChild(input);

            capNhatTongGia();
            this.value = ''; // reset
        });

        // Xử lý tăng / giảm số lượng & xóa
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

        // Nếu người dùng thay đổi số lượng thủ công
        list.addEventListener('input', function(e) {
            if (e.target.classList.contains('so-luong')) {
                capNhatTongGia();
            }
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const select = document.getElementById('select-chi-nhanh');
        const list = document.getElementById('selected-branches');
        const hiddenInputs = document.getElementById('hidden-branch-inputs');
        const selectedIds = new Set();

        select.addEventListener('change', function() {
            const selectedOption = select.options[select.selectedIndex];
            const id = selectedOption.value;
            const ten = selectedOption.dataset.ten;

            if (!id || selectedIds.has(id)) return;

            selectedIds.add(id);

            // Hiển thị ra danh sách đã chọn
            const li = document.createElement('li');
            li.className = 'list-group-item d-flex justify-content-between align-items-center';
            li.dataset.id = id;
            li.innerHTML = `
            <span>${ten}</span>
            <button type="button" class="btn btn-sm btn-danger btn-xoa-cn" data-id="${id}">Xoá</button>
        `;
            list.appendChild(li);

            // Thêm input hidden
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'chi_nhanh_ids[]';
            input.value = id;
            input.id = 'input-chi_nhanh-' + id;
            hiddenInputs.appendChild(input);

            // Xoá khi nhấn nút
            li.querySelector('.btn-xoa-cn').addEventListener('click', function() {
                selectedIds.delete(id);
                li.remove();
                document.getElementById('input-chi_nhanh-' + id)?.remove();
            });

            // Reset select
            select.selectedIndex = 0;
        });
    });
</script>
