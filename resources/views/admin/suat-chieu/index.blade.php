@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <!-- Bộ lọc -->
                <form method="GET" action="{{ route('admin.suat-chieu.index') }}" class="row g-3 mb-4 align-items-end">

                    {{-- Tên phim --}}
                    <div class="col-md-3">
                        <label for="ten_phim" class="form-label">Tên phim</label>
                        <input type="text" name="ten_phim" class="form-control" value="{{ request('ten_phim') }}">
                    </div>

                    {{-- Lọc theo chi nhánh (chỉ admin tổng mới thấy) --}}
                    @if ($user->vai_tro_id == 1)
                        <div class="col-md-3">
                            <label for="chi_nhanh" class="form-label">Chi nhánh</label>
                            <select name="chi_nhanh" id="chi_nhanh" class="form-select">
                                <option value="">-- Tất cả --</option>
                                @foreach ($chiNhanhs as $cn)
                                    <option value="{{ $cn->id }}"
                                        {{ request('chi_nhanh') == $cn->id ? 'selected' : '' }}>
                                        {{ $cn->ten_chi_nhanh }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    @if ($user->vai_tro_id == 2 && isset($chiNhanhs[0]))
                        <div class="col-md-3">
                            <label class="form-label">Chi nhánh</label>
                            <input type="text" class="form-control" value="{{ $chiNhanhs[0]->ten_chi_nhanh }}" readonly
                                disabled>
                        </div>
                    @endif

                    {{-- Lọc theo rạp (admin tổng và chi nhánh mới được lọc) --}}
                    @if (in_array($user->vai_tro_id, [1, 2]))
                        <div class="col-md-3">
                            <label for="rap" class="form-label">Rạp</label>
                            <select name="rap" id="rap" class="form-select">
                                <option value="">-- Tất cả --</option>
                                @foreach ($chiNhanhs as $cn)
                                    @foreach ($cn->rapPhims as $rap)
                                        <option value="{{ $rap->id }}"
                                            {{ request('rap') == $rap->id ? 'selected' : '' }}>
                                            {{ $rap->ten_rap }}
                                        </option>
                                    @endforeach
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100">Lọc</button>
                        <a href="{{ route('admin.suat-chieu.index') }}" class="btn btn-secondary w-100">Reset</a>
                    </div>
                </form>


            </div>

            <!-- Bảng dữ liệu -->
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="bg-gradient-dark text-white">
                        <tr>
                            <th class="text-center" style="width: 5%">
                                <h6 class="fw-semibold mb-0"></h6>
                            </th>
                            <th class="text-center" style="width: 10%">
                                <h6 class="fw-semibold mb-0">Poster</h6>
                            </th>
                            <th class="text-center">
                                <h6 class="fw-semibold mb-0">Phim</h6>
                            </th>
                            <th class="text-center" style="width: 15%">
                                <h6 class="fw-semibold mb-0">Thời lượng</h6>
                            </th>
                            <th class="text-center">
                                <h6 class="fw-semibold mb-0">Ngày khởi chiếu</h6>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $grouped = $suatChieus->groupBy('phim_id'); @endphp
                        @forelse($grouped as $phimId => $group)
                            @php $first = $group->first(); @endphp
                            <tr>
                                <td class="text-center">
                                    <button
                                        class="btn btn-sm btn-outline-primary toggle-btn d-flex justify-content-center align-items-center rounded-circle"
                                        data-target="details-{{ $phimId }}" title="Xem chi tiết suất chiếu"
                                        style="width: 30px; height: 30px;">
                                        <span class="icon fw-bold">+</span>
                                    </button>
                                </td>
                                <td class="text-center">
                                    @if ($first->phim->poster)
                                        <img src="{{ asset('storage/' . $first->phim->poster) }}"
                                            class="img-thumbnail rounded"
                                            style="width: 60px; height: 80px; object-fit: cover;"
                                            alt="{{ $first->phim->ten_phim }}">
                                    @else
                                        <span class="badge bg-secondary rounded-pill">Không có ảnh</span>
                                    @endif
                                </td>
                                <td class="text-center">{{ $first->phim->ten_phim }}</td>
                                <td class="text-center">
                                    {{ $first->phim->thoi_luong ? $first->phim->thoi_luong . ' phút' : 'N/A' }}</td>
                                <td class="text-center">
                                    {{ \Carbon\Carbon::parse($first->phim->ngay_phat_hanh)->format('d/m/Y') }} -
                                    {{ \Carbon\Carbon::parse($first->phim->ngay_ket_thuc)->format('d/m/Y') }}
                                </td>
                            </tr>

                            <tr class="details-row d-none" id="details-{{ $phimId }}">
                                <td colspan="5" class="p-0">
                                    <table class="table mb-0 text-center">
                                        <thead class="table-secondary">
                                            <tr>
                                                <th style="width: 5%">
                                                    <input type="checkbox" id="check-all-{{ $phimId }}">
                                                </th>
                                                <th style="width: 15%">Ngày chiếu</th>
                                                <th style="width: 15%">Giờ chiếu</th>
                                                <th style="width: 15%">Phòng</th>
                                                <th style="width: 15%">Phiên bản</th>
                                                <th style="width: 10%">Trạng thái</th>
                                                <th style="width: 25%">Thao tác</th>
                                            </tr>
                                            <tr>
                                                <td colspan="7" class="bg-light">
                                                    <div
                                                        class="d-flex justify-content-center gap-3 align-items-center px-3 py-2 flex-wrap">
                                                        <div class="d-flex align-items-center gap-2">
                                                            <input type="date"
                                                                class="form-control form-control-sm filter-date"
                                                                style="width: 160px;" data-group="{{ $phimId }}">
                                                        </div>
                                                        <div class="d-flex align-items-center gap-2">
                                                            <select class="form-select form-select-sm filter-room"
                                                                style="width: 200px;" data-group="{{ $phimId }}">
                                                                <option value="">-- Tất cả phòng --</option>
                                                                @foreach ($group->pluck('phongChieu.ten_phong')->unique()->filter() as $tenPhong)
                                                                    <option value="{{ $tenPhong }}">
                                                                        {{ $tenPhong }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <button type="button"
                                                            class="btn btn-sm btn-outline-secondary clear-filters"
                                                            data-group="{{ $phimId }}">
                                                            Xóa lọc
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach ($group as $suat)
                                                <tr class="no-result-row text-muted d-none">
                                                    <td colspan="7" class="py-4"><em>Không có suất chiếu nào khớp với
                                                            bộ lọc.</em></td>
                                                </tr>

                                                <tr class="suat-row"
                                                    data-ngay="{{ \Carbon\Carbon::parse($suat->ngay_bat_dau)->format('Y-m-d') }}"
                                                    data-room="{{ $suat->phongChieu->ten_phong }}">
                                                    <td>
                                                        <input type="checkbox" class="suat-checkbox"
                                                            value="{{ $suat->id }}"
                                                            data-group="check-all-{{ $phimId }}">
                                                    </td>
                                                    <td>{{ \Carbon\Carbon::parse($suat->ngay_bat_dau)->format('d/m/Y') }}
                                                    </td>
                                                    <td>{{ \Carbon\Carbon::parse($suat->bat_dau)->format('H:i') }} -
                                                        {{ \Carbon\Carbon::parse($suat->ket_thuc)->format('H:i') }}</td>
                                                    <td>{{ $suat->phongChieu->ten_phong ?? 'N/A' }}</td>
                                                    <td>{{ $suat->formatted_version }}</td>
                                                    <td>
                                                        <div class="form-check form-switch d-flex justify-content-center">
                                                            <input class="form-check-input toggle-status" type="checkbox"
                                                                data-id="{{ $suat->id }}"
                                                                {{ $suat->trang_thai == 'hoat_dong' ? 'checked' : '' }}>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('admin.suat-chieu.show', $suat->id) }}"
                                                            class="btn btn-sm btn-outline-info" title="Xem chi tiết">
                                                            <i class="ti ti-eye"></i>
                                                        </a>
                                                        @if ($suat->trang_thai !== 'hoat_dong')
                                                            <a href="{{ route('admin.suat-chieu.edit', $suat->id) }}"
                                                                class="btn btn-sm btn-outline-primary" title="Chỉnh sửa">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>

                                    <div class="p-3">
                                        <button class="btn btn-outline-danger btn-sm bulk-delete"
                                            data-group="check-all-{{ $phimId }}"
                                            title="Xóa các suất chiếu đã chọn">
                                            <i class="ti ti-trash me-1"></i> Xóa tất cả
                                        </button>
                                        <button class="btn btn-outline-secondary btn-sm bulk-toggle"
                                            data-group="check-all-{{ $phimId }}"
                                            title="Bật trạng thái các suất chiếu đã chọn">
                                            <i class="ti ti-reload me-1"></i> Bật/tắt trạng thái tất cả
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr id="emptyRow">
                                <td colspan="5" class="text-center text-muted py-3">
                                    <i class="fas fa-folder-open me-1"></i> Không có suất chiếu nào
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle chi tiết suất chiếu
            document.querySelectorAll('.toggle-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-target');
                    const row = document.getElementById(targetId);
                    const icon = this.querySelector('.icon');

                    row.classList.toggle('d-none');
                    icon.textContent = row.classList.contains('d-none') ? '+' : '−';

                    const filterDate = row.querySelector('.filter-date');
                    const filterRoom = row.querySelector('.filter-room');
                    const suatRows = row.querySelectorAll('.suat-row');
                    const clearBtn = row.querySelector('.clear-filters');
                    const noResultRow = row.querySelector('.no-result-row');

                    const filter = () => {
                        const dateValue = filterDate.value;
                        const roomValue = filterRoom.value;

                        let visibleCount = 0;

                        suatRows.forEach(suat => {
                            const ngay = suat.dataset.ngay;
                            const room = suat.dataset.room;

                            const matchNgay = !dateValue || ngay === dateValue;
                            const matchRoom = !roomValue || room === roomValue;

                            if (matchNgay && matchRoom) {
                                suat.style.display = '';
                                visibleCount++;
                            } else {
                                suat.style.display = 'none';
                            }
                        });

                        if (noResultRow) {
                            noResultRow.classList.toggle('d-none', visibleCount !== 0);
                        }

                        if (dateValue || roomValue) {
                            clearBtn.classList.remove('d-none');
                        } else {
                            clearBtn.classList.add('d-none');
                        }
                    };

                    filterDate.addEventListener('change', filter);
                    filterRoom.addEventListener('change', filter);

                    clearBtn.addEventListener('click', function() {
                        filterDate.value = '';
                        filterRoom.value = '';

                        suatRows.forEach(suat => suat.style.display = '');

                        if (noResultRow) {
                            noResultRow.classList.add('d-none');
                        }

                        clearBtn.classList.add('d-none');
                    });
                });
            });

            // Toggle trạng thái suất chiếu
            document.querySelectorAll('.toggle-status').forEach(switchBtn => {
                switchBtn.addEventListener('change', function() {
                    // if (this.disabled) {
                    //     alert('Không thể thay đổi trạng thái khi đang hoạt động.');
                    //     return;
                    // }

                    const suatChieuId = this.getAttribute('data-id');
                    const newStatus = this.checked ? 'hoat_dong' : 'tam_dung';

                    fetch(`/admin/suat-chieu/${suatChieuId}/toggle-status`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                trang_thai: newStatus
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                location.reload();
                            } else {
                                alert('Đã có lỗi xảy ra khi cập nhật trạng thái.');
                                this.checked = !this.checked;
                            }
                        })
                        .catch(() => {
                            alert('Lỗi kết nối máy chủ.');
                            this.checked = !this.checked;
                        });
                });
            });

            // Checkbox chọn tất cả
            document.querySelectorAll('[id^="check-all-"]').forEach(checkAll => {
                checkAll.addEventListener('change', function() {
                    const groupId = this.id;
                    const isChecked = this.checked;
                    document.querySelectorAll(`.suat-checkbox[data-group="${groupId}"]`).forEach(
                        cb => cb.checked = isChecked);
                });
            });

            // Xóa hàng loạt
            document.querySelectorAll('.bulk-delete').forEach(button => {
                button.addEventListener('click', function() {
                    const groupId = this.getAttribute('data-group');
                    const ids = Array.from(document.querySelectorAll(
                        `.suat-checkbox[data-group="${groupId}"]:checked`)).map(cb => cb.value);
                    if (ids.length === 0) return alert('Chưa chọn suất chiếu nào.');
                    if (!confirm('Bạn có chắc chắn muốn xóa các suất chiếu đã chọn?')) return;

                    fetch('{{ route('admin.suat-chieu.bulk-delete') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                ids
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (!data.success) {
                                alert(data.message || 'Lỗi khi xóa các suất chiếu.');
                                return;
                            }

                            // Có suất chiếu bị chặn xóa?
                            if (data.blocked && data.blocked.length) {
                                let list = data.blocked.map(sc =>
                                    `Ngày ${sc.ngay_bat_dau} | Giờ chiếu: ${sc.bat_dau} - ${sc.ket_thuc} (${sc.reason})`
                                ).join('\n');

                                alert(data.message + '\n' + list);
                            }

                            // Nếu có xóa được cái nào thì reload để cập nhật UI
                            if (data.deleted_count && data.deleted_count > 0) {
                                location.reload();
                            }
                        })
                        .catch(() => alert('Lỗi kết nối máy chủ.'));
                });
            });

            // Bật trạng thái hàng loạt
            document.querySelectorAll('.bulk-toggle').forEach(button => {
                button.addEventListener('click', function() {
                    const groupId = this.getAttribute('data-group');
                    const ids = Array.from(document.querySelectorAll(
                        `.suat-checkbox[data-group="${groupId}"]:checked`)).map(cb => cb.value);
                    if (ids.length === 0) return alert('Chưa chọn suất chiếu nào.');
                    if (!confirm('Bạn có chắc chắn muốn bật trạng thái các suất chiếu đã chọn?'))
                        return;

                    fetch('{{ route('admin.suat-chieu.bulk-toggle-status') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                ids
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) location.reload();
                            else alert('Không thể cập nhật trạng thái.');
                        })
                        .catch(() => alert('Lỗi kết nối máy chủ.'));
                });
            });

            // Tải danh sách rạp theo chi nhánh
            const chiNhanhSelect = document.getElementById('chi_nhanh');
            const rapSelect = document.getElementById('rap');
            const chiNhanhs = @json($chiNhanhs);
            const selectedChiNhanh = '{{ request('chi_nhanh') }}';
            const selectedRap = '{{ request('rap') }}';

            function renderRaps(chiNhanhId) {
                rapSelect.innerHTML = '<option value="">-- Tất cả rạp --</option>';
                if (!chiNhanhId) return;

                const found = chiNhanhs.find(cn => cn.id == chiNhanhId);
                if (found && found.rap_phims) {
                    found.rap_phims.forEach(rap => {
                        const opt = document.createElement('option');
                        opt.value = rap.id;
                        opt.textContent = rap.ten_rap;
                        rapSelect.appendChild(opt);
                    });

                    if (selectedRap) rapSelect.value = selectedRap;
                }
            }

            chiNhanhSelect.addEventListener('change', function() {
                renderRaps(this.value);
            });

            if (selectedChiNhanh) {
                chiNhanhSelect.value = selectedChiNhanh;
                renderRaps(selectedChiNhanh);
            }

        });
    </script>
@endsection
