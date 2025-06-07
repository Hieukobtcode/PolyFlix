@extends('layouts.admin')

@section('content')
<div class="container">
    <h2 class="mb-4">Tạo suất chiếu mới</h2>

    <form action="{{ route('admin.suat-chieu.store') }}" method="POST">
        @csrf

        {{-- Thông tin cơ bản --}}
        <div class="mb-3">
            <label for="phim_id" class="form-label">Phim</label>
            <select name="phim_id" id="phim_id" class="form-select" required>
                <option value="">-- Chọn phim --</option>
                @foreach($phims as $phim)
                    <option value="{{ $phim->id }}" data-duration="{{ $phim->thoi_luong }}">
                        {{ $phim->ten_phim }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="chi_nhanh_id" class="form-label">Chi nhánh</label>
            <select name="chi_nhanh_id" class="form-select" required>
                <option value="">-- Chọn chi nhánh --</option>
                @foreach($chiNhanhs as $cn)
                    <option value="{{ $cn->id }}">{{ $cn->ten_chi_nhanh }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="rap_phim_id" class="form-label">Rạp phim</label>
            <select name="rap_phim_id" class="form-select">
                <option value="">-- Chọn rạp phim --</option>
                @foreach($rapPhims as $rap)
                    <option value="{{ $rap->id }}">{{ $rap->ten_rap }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="phong_chieu_id" class="form-label">Phòng chiếu</label>
            <select name="phong_chieu_id" class="form-select" required>
                <option value="">-- Chọn phòng chiếu --</option>
                @foreach($phongChieus as $pc)
                    <option value="{{ $pc->id }}">{{ $pc->ten_phong }}</option>
                @endforeach
            </select>
        </div>

        {{-- Ngày chiếu --}}
        <div class="mb-3">
            <label for="ngay_chieu" class="form-label">Ngày chiếu</label>
            <input type="date" name="ngay_chieu" id="ngay_chieu" class="form-control" required>
        </div>

        {{-- Tùy chọn chế độ tạo suất --}}
        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="auto_suat_check">
            <label class="form-check-label" for="auto_suat_check">Tự động thêm các suất chiếu trong ngày</label>
        </div>

        {{-- Chế độ tự động --}}
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="gio_mo" class="form-label">Giờ mở cửa</label>
                <input type="time" id="gio_mo" class="form-control">
            </div>
            <div class="col-md-6">
                <label for="gio_dong" class="form-label">Giờ đóng cửa</label>
                <input type="time" id="gio_dong" class="form-control">
            </div>
        </div>

        <button type="button" class="btn btn-outline-primary mb-3" id="generate-suat-btn">Thêm giờ chiếu</button>

        <ul id="suat-list" class="list-group mb-3"></ul>

        {{-- Chế độ thủ công --}}
        <div id="manual-slot-wrapper" style="display: none;">
            <button type="button" class="btn btn-outline-primary mb-3" id="add-manual-slot">➕ Thêm giờ chiếu</button>
            <div id="manual-slot-list"></div>
        </div>

        {{-- Hidden field chứa danh sách suất --}}
        <input type="hidden" name="generated_slots" id="generated_slots">

        {{-- Trạng thái & phiên bản --}}
        <div class="mb-3">
            <label for="phien_ban_phim" class="form-label">Phiên bản phim</label>
            <select name="phien_ban_phim" class="form-select" required>
                <option value="long_tieng">Lồng tiếng</option>
                <option value="phu_de">Phụ đề</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="trang_thai" class="form-label">Trạng thái</label>
            <select name="trang_thai" class="form-select">
                <option value="hoat_dong">Hoạt động</option>
                <option value="tam_dung">Tạm dừng</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Lưu suất chiếu</button>
        <a href="{{ route('admin.suat-chieu.index') }}" class="btn btn-secondary">Hủy</a>
    </form>
    @if(count($suatChieusHienTai))
    <div class="card mb-3">
        <div class="card-body">
            <h6 class="mb-3">Suất chiếu đang có:</h6>
            <table class="table table-sm table-bordered">
                <thead>
                    <tr>
                        <th>Thời gian</th>
                        <th>Phòng</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($suatChieusHienTai as $sc)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($sc->bat_dau)->format('H:i') }} - {{ \Carbon\Carbon::parse($sc->ket_thuc)->format('H:i') }}</td>
                            <td>{{ $sc->phongChieu->ten_phong ?? 'Không rõ' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@else
    <div class="alert alert-light text-muted">Không có suất chiếu nào cho ngày này.</div>
@endif

</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const phimSelect = document.getElementById('phim_id');
    const btnGenerate = document.getElementById('generate-suat-btn');
    const slotList = document.getElementById('suat-list');
    const hiddenSlots = document.getElementById('generated_slots');
    const autoCheck = document.getElementById('auto_suat_check');

    const manualWrapper = document.getElementById('manual-slot-wrapper');
    const manualList = document.getElementById('manual-slot-list');
    const btnAddManual = document.getElementById('add-manual-slot');

    // Toggle auto vs manual
    function toggleMode() {
        const isAuto = autoCheck.checked;
        document.getElementById('gio_mo').closest('.row').style.display = isAuto ? '' : 'none';
        btnGenerate.style.display = isAuto ? '' : 'none';
        slotList.style.display = isAuto ? '' : 'none';
        manualWrapper.style.display = isAuto ? 'none' : '';
    }

    autoCheck.addEventListener('change', toggleMode);
    toggleMode();

    // Tạo suất tự động
    btnGenerate.addEventListener('click', function () {
    const phimOption = phimSelect.options[phimSelect.selectedIndex];
    const duration = parseInt(phimOption.getAttribute('data-duration') || 0);
    const mo = document.getElementById('gio_mo').value;
    const dong = document.getElementById('gio_dong').value;

    if (!duration || !mo || !dong) {
        alert('Vui lòng chọn phim và nhập giờ mở/đóng cửa.');
        return;
    }

    const list = [];
    let current = new Date(`1970-01-01T${mo}`);
    let end = new Date(`1970-01-01T${dong}`);
    if (end <= current) {
        end.setDate(end.getDate() + 1); // xử lý qua ngày
    }

    while (current.getTime() + duration * 60000 <= end.getTime()) {
        const batDau = new Date(current);
        current.setMinutes(current.getMinutes() + duration);
        const ketThuc = new Date(current);

        list.push({
            bat_dau: batDau.toTimeString().slice(0, 5),
            ket_thuc: ketThuc.toTimeString().slice(0, 5)
        });

        current.setMinutes(current.getMinutes() + 20); // nghỉ 20 phút
    }

    slotList.innerHTML = '';
    list.forEach((slot, index) => {
        const li = document.createElement('li');
        li.className = 'list-group-item';
        li.textContent = `Suất ${index + 1}: ${slot.bat_dau} - ${slot.ket_thuc}`;
        slotList.appendChild(li);
    });

    hiddenSlots.value = JSON.stringify(list);
});

    // Thêm giờ thủ công
    btnAddManual.addEventListener('click', function () {
        const row = document.createElement('div');
        row.className = 'row mb-2 align-items-center';
        row.innerHTML = `
            <div class="col-md-5">
                <input type="time" class="form-control bat-dau" placeholder="Giờ chiếu">
            </div>
            <div class="col-md-5">
                <input type="time" class="form-control ket-thuc" placeholder="Giờ kết thúc">
            </div>
            <div class="col-md-2 text-end">
                <button type="button" class="btn btn-danger remove-slot">🗑</button>
            </div>
        `;
        manualList.appendChild(row);

        row.querySelector('.remove-slot').addEventListener('click', () => row.remove());
    });

    // Gom dữ liệu khi submit
    document.querySelector('form').addEventListener('submit', function () {
        if (!autoCheck.checked) {
            const slots = [];
            manualList.querySelectorAll('.row').forEach(row => {
                const batDau = row.querySelector('.bat-dau').value;
                const ketThuc = row.querySelector('.ket-thuc').value;
                if (batDau && ketThuc) {
                    slots.push({ bat_dau: batDau, ket_thuc: ketThuc });
                }
            });
            hiddenSlots.value = JSON.stringify(slots);
        }
    });
});
</script>
@endsection
