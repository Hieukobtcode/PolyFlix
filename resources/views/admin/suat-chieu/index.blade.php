@extends('layouts.admin')

@section('title', 'Suất chiếu')
@section('page-title', 'Danh sách Suất chiếu')
@section('breadcrumb', 'Danh sách Suất chiếu')

@section('styles')
    <style>
        .toggle-btn .icon {
            font-size: 18px;
            line-height: 1;
        }

        .card {
            border-radius: 10px;
        }

        .table th,
        .table td {
            vertical-align: middle;
        }

        .badge {
            font-size: 0.9em;
            padding: 0.5em 1em;
        }

        .btn,
        .form-control,
        .form-select {
            border-radius: 8px;
        }

        .table-dark {
            background-color: #343a40;
        }

        .img-thumbnail {
            border-radius: 8px;
        }

        .details-row table {
            border-radius: 8px;
            overflow: hidden;
        }

        .toggle-btn {
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2em;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Danh sách suất chiếu</h5>
            </div>
            <div class="card-body p-4">
                <!-- Bộ lọc -->
                <form method="GET" action="{{ route('admin.suat-chieu.index') }}" class="row g-3 mb-4">
                    <div class="col-md-3">
                        <label for="chi_nhanh" class="form-label fw-semibold">Chi nhánh</label>
                        <select name="chi_nhanh" id="chi_nhanh" class="form-select rounded">
                            <option value="">-- Tất cả chi nhánh --</option>
                            @foreach ($chiNhanhs as $cn)
                                <option value="{{ $cn->id }}" {{ request('chi_nhanh') == $cn->id ? 'selected' : '' }}>
                                    {{ $cn->ten_chi_nhanh }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="rap" class="form-label fw-semibold">Rạp</label>
                        <select name="rap" id="rap" class="form-select rounded">
                            <option value="">-- Tất cả rạp --</option>
                            <!-- Option rạp sẽ được cập nhật bằng JS -->
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="phim" class="form-label fw-semibold">Phim</label>
                        <select name="phim" id="phim" class="form-select rounded">
                            <option value="">-- Tất cả phim--</option>
                            <!-- Option rạp sẽ được cập nhật bằng JS -->
                        </select>
                    </div>
                    {{-- <div class="col-md-3">
                        <label for="ngay_chieu" class="form-label fw-semibold">Ngày chiếu</label>
                        <input type="date" name="ngay_chieu" id="ngay_chieu" class="form-control rounded"
                            value="{{ request('ngay_chieu') }}">
                    </div> --}}
                    <div class="col-md-3 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary" title="Lọc">
                            <i class="fas fa-filter me-1"></i> Lọc
                        </button>
                        <a href="{{ route('admin.suat-chieu.index') }}" class="btn btn-outline-secondary"
                            title="Xóa bộ lọc">
                            <i class="fas fa-sync-alt me-1"></i> Xóa bộ lọc
                        </a>
                    </div>


                </form>

                <!-- Bảng dữ liệu -->
                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th scope="col" class="text-center" style="width: 5%"></th>
                                <th scope="col" class="text-center" style="width: 10%">Poster</th>
                                <th scope="col" class="text-center">Phim</th>
                                <th scope="col" class="text-center" style="width: 15%">Thời lượng</th>
                                <th scope="col" class="text-center">Ngày khởi chiếu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $grouped = $suatChieus->groupBy('phim_id'); @endphp
                            @forelse($grouped as $phimId => $group)
                                @php
                                    $first = $group->first();
                                    $hasActive = $group->contains(fn($s) => $s->trang_thai === 'hoat_dong');
                                @endphp
                                <tr>
                                    <td class="text-center">
                                        <button
                                            class="btn btn-sm btn-outline-primary toggle-btn d-flex justify-content-center align-items-center"
                                            data-target="details-{{ $phimId }}" title="Xem chi tiết suất chiếu"
                                            style="width: 30px; height: 30px;">
                                            <span class="icon">+</span>
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
                                        {{ $first->phim->thoi_luong ? $first->phim->thoi_luong . ' phút' : 'N/A' }}
                                    </td>
                                    <td class="text-center">
                                        {{ \Carbon\Carbon::parse($first->phim->ngay_phat_hanh)->format('d/m/Y') }}
                                        -
                                        {{ \Carbon\Carbon::parse($first->phim->ngay_ket_thuc)->format('d/m/Y') }}
                                    </td>

                                </tr>
                                <tr class="details-row d-none" id="details-{{ $phimId }}">
                                    <td colspan="5" class="p-0">
                                        <table class="table table-bordered mb-0">
                                            <thead class="table-secondary">
                                                <tr>
                                                    <th scope="col" class="text-center" style="width: 5%">
                                                        <input type="checkbox" id="check-all-{{ $phimId }}">
                                                    </th>
                                                    <th scope="col" style="width: 15%">Ngày chiếu</th>
                                                    <th scope="col" style="width: 15%">Giờ chiếu</th>
                                                    <th scope="col" style="width: 15%">Phòng</th>
                                                    <th scope="col" style="width: 15%">Phiên bản</th>
                                                    <th scope="col" class="text-center" style="width: 10%">Trạng thái
                                                    </th>
                                                    <th scope="col" class="text-center" style="width: 25%">Thao tác
                                                    </th>
                                                </tr>

                                                <tr>
                                                    <td colspan="7" class="bg-light">
                                                        <div class="d-flex gap-3 align-items-center px-3 py-2">
                                                            <div class="d-flex align-items-center gap-2">
                                                                <input type="date"
                                                                    class="form-control form-control-sm filter-date"
                                                                    style="width: 160px;"
                                                                    data-group="{{ $phimId }}">
                                                            </div>
                                                            <div class="d-flex align-items-center gap-2">
                                                                <select class="form-select form-select-sm filter-room"
                                                                    style="width: 200px;"
                                                                    data-group="{{ $phimId }}">
                                                                    <option value="">-- Tất cả phòng --</option>
                                                                    @foreach ($group->pluck('phongChieu.ten_phong')->unique()->filter() as $tenPhong)
                                                                        <option value="{{ $tenPhong }}">
                                                                            {{ $tenPhong }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <button type="button"
                                                                class="btn btn-sm btn-outline-secondary ms-auto clear-filters"
                                                                data-group="{{ $phimId }}">
                                                                Xóa lọc
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @foreach ($group as $suat)
                                                    <tr class="no-result-row text-center text-muted d-none">
                                                        <td colspan="7" class="py-4">
                                                            <em>Không có suất chiếu nào khớp với bộ lọc.</em>
                                                        </td>
                                                    </tr>

                                                    <tr class="suat-row"
                                                        data-ngay="{{ \Carbon\Carbon::parse($suat->ngay_chieu)->format('Y-m-d') }}"
                                                        data-room="{{ $suat->phongChieu->ten_phong }}">

                                                        <td class="text-center">
                                                            <input type="checkbox" class="suat-checkbox"
                                                                value="{{ $suat->id }}"
                                                                data-group="check-all-{{ $phimId }}">
                                                        </td>
                                                        <td>{{ \Carbon\Carbon::parse($suat->ngay_chieu)->format('d/m/Y') }}
                                                        </td>
                                                        <td>{{ \Carbon\Carbon::parse($suat->bat_dau)->format('H:i') }} -
                                                            {{ \Carbon\Carbon::parse($suat->ket_thuc)->format('H:i') }}
                                                        </td>
                                                        <td>{{ $suat->phongChieu->ten_phong ?? 'N/A' }}</td>
                                                        <td>
                                                            {{ $suat->formatted_version }}
                                                        </td>
                                                        <td class="text-center">
                                                            <div class="form-check form-switch">
                                                                <input class="form-check-input toggle-status"
                                                                    type="checkbox" data-id="{{ $suat->id }}"
                                                                    {{ $suat->trang_thai == 'hoat_dong' ? 'checked disabled' : '' }}>
                                                            </div>
                                                        </td>
                                                        <td class="text-center">
                                                            <a href="{{ route('admin.suat-chieu.show', $suat->id) }}"
                                                                class="btn btn-sm btn-outline-info" title="Xem chi tiết">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                            @if ($suat->trang_thai !== 'hoat_dong')
                                                                <a href="{{ route('admin.suat-chieu.edit', $suat->id) }}"
                                                                    class="btn btn-sm btn-outline-primary"
                                                                    title="Chỉnh sửa">
                                                                    <i class="fas fa-edit"></i>
                                                                </a>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        @if (!$hasActive)
                                            <div class="p-3">
                                                <button class="btn btn-outline-danger btn-sm bulk-delete"
                                                    data-group="check-all-{{ $phimId }}"
                                                    title="Xóa các suất chiếu đã chọn">
                                                    <i class="fas fa-trash me-1"></i> Xóa tất cả
                                                </button>
                                                <button class="btn btn-outline-secondary btn-sm bulk-toggle"
                                                    data-group="check-all-{{ $phimId }}"
                                                    title="Bật trạng thái các suất chiếu đã chọn">
                                                    <i class="fas fa-power-off me-1"></i> Bật trạng thái tất cả
                                                </button>
                                            </div>
                                        @endif
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

                {{-- @if ($suatChieus->hasPages())
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4 gap-2">
                        <small class="text-muted">
                            Hiển thị {{ $suatChieus->firstItem() }} – {{ $suatChieus->lastItem() }} /
                            {{ $suatChieus->total() }} suất chiếu
                        </small>

                        <nav aria-label="Phân trang">
                            {{ $suatChieus->appends(request()->query())->links('pagination::bootstrap-5') }}
                        </nav>
                    </div>
                @endif --}}
                    
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
                    if (this.disabled) {
                        alert('Không thể thay đổi trạng thái khi đang hoạt động.');
                        return;
                    }

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
                            if (data.success) location.reload();
                            else alert('Lỗi khi xóa các suất chiếu.');
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
