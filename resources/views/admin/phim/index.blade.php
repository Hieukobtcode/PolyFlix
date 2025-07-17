@extends('layouts.admin')

@php
    use Illuminate\Support\Str;
@endphp

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    @if (Auth::user()->vai_tro_id == 1)
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.phim.create') }}"
                                class="btn btn-primary btn-sm d-inline-flex align-items-center gap-2 px-3 py-2">
                                <i class="ti ti-plus fs-5"></i> Thêm phim
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Bộ lọc -->
                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <input type="text" id="searchInput" class="form-control" placeholder="Tìm theo tên phim...">
                    </div>
                    <div class="col-md-3">
                        <select id="statusFilter" class="form-select">
                            <option value="">Tất cả trạng thái</option>
                            <option value="đang chiếu">Đang chiếu</option>
                            <option value="sắp chiếu">Sắp chiếu</option>
                            <option value="đã kết thúc">Đã kết thúc</option>
                            <option value="bị hủy">Bị hủy</option>
                        </select>
                    </div>
                </div>

                <!-- Bảng dữ liệu -->
                <div class="table-responsive">
                    <table class="table text-nowrap align-middle mb-0">
                        <thead class="bg-dark text-white">
                            <tr>
                                <th class="text-center" style="width: 5%;">#</th>
                                <th class="text-center" style="width: 8%;">Poster</th>
                                <th style="width: 4%">Tên phim</th>
                                <th class="text-center" style="width: 8%;">Thời lượng</th>
                                <th class="text-center" style="width: 12%;">Ngày phát hành</th>
                                <th class="text-center" style="width: 12%;">Trạng thái</th>
                                <th class="text-center" style="width: 5%;">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody id="movieTable">
                            @forelse($phims as $index => $phim)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td class="text-center">
                                        @if ($phim->poster)
                                            <img src="{{ asset('storage/' . $phim->poster) }}" alt="{{ $phim->ten_phim }}"
                                                class="rounded img-thumbnail"
                                                style="width: 60px; height: 80px; object-fit: cover;">
                                        @else
                                            <span class="badge bg-secondary">Không có ảnh</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span title="{{ $phim->ten_phim }}">
                                            {{ Str::limit($phim->ten_phim, 20) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        {{ $phim->thoi_luong ? $phim->thoi_luong . ' phút' : 'N/A' }}
                                    </td>
                                    <td class="text-center">
                                        {{ $phim->ngay_phat_hanh ? $phim->ngay_phat_hanh->format('d/m/Y') : 'N/A' }}
                                    </td>
                                    <td class="text-center" data-status="{{ strtolower($phim->trang_thai) }}">
                                        <span
                                            class="badge rounded-pill
                                            {{ match (strtolower($phim->trang_thai)) {
                                                'đang chiếu' => 'bg-success',
                                                'sắp chiếu' => 'bg-warning text-dark',
                                                'đã kết thúc' => 'bg-secondary',
                                                'bị hủy' => 'bg-danger',
                                                default => 'bg-dark',
                                            } }}">
                                            {{ Str::title($phim->trang_thai) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="dropdown dropstart">
                                            <a href="#" class="text-muted" data-bs-toggle="dropdown">
                                                <i class="ti ti-dots-vertical fs-5"></i>
                                            </a>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a class="dropdown-item d-flex align-items-center gap-2"
                                                        href="{{ route('admin.phim.show', $phim->id) }}">
                                                        <i class="ti ti-eye fs-5"></i> Xem chi tiết
                                                    </a>
                                                </li>
                                                @if (Auth::user()->vai_tro_id !== 3)
                                                    <li>
                                                        <a class="dropdown-item d-flex align-items-center gap-2"
                                                            href="{{ route('admin.phim.edit', $phim->id) }}">
                                                            <i class="ti ti-edit fs-5"></i> Chỉnh sửa
                                                        </a>
                                                    </li>
                                                @endif
                                                @if (Auth::user()->vai_tro_id !== 2)
                                                    <li>
                                                        <a class="dropdown-item d-flex align-items-center gap-2"
                                                            href="{{ route('admin.suat-chieu.create', ['phimId' => $phim->id]) }}">
                                                            <i class="ti ti-calendar-plus fs-5"></i> Thêm suất chiếu
                                                        </a>
                                                    </li>
                                                @endif
                                                @if (Auth::user()->vai_tro_id == 1)
                                                    <li>
                                                        <form action="{{ route('admin.phim.destroy', $phim->id) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('Bạn có chắc chắn muốn xóa mềm phim này?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="dropdown-item d-flex align-items-center gap-2 text-danger">
                                                                <i class="ti ti-trash fs-5"></i> Xóa
                                                            </button>
                                                        </form>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr id="emptyRow">
                                    <td colspan="8" class="text-center text-muted py-3">
                                        <i class="ti ti-folder-open me-1"></i> Không có dữ liệu
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div>
                        <small class="text-muted">Hiển thị {{ $phims->count() }} trong tổng số {{ $phims->total() }}
                            phim</small>
                    </div>
                    <div>
                        {{ $phims->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const rows = document.querySelectorAll('#movieTable tr:not(#emptyRow)');
            const tableBody = document.getElementById('movieTable');
            const infoText = document.querySelector('.text-muted');

            function filterTable() {
                const searchText = document.getElementById('searchInput').value.toLowerCase();
                const statusFilter = document.getElementById('statusFilter').value.toLowerCase();
                let visibleCount = 0;

                rows.forEach(row => {
                    const nameCell = row.querySelector('td:nth-child(3)');
                    const statusCell = row.querySelector('td:nth-child(6)');

                    if (!nameCell || !statusCell) return;

                    const name = nameCell.textContent.toLowerCase();
                    const status = statusCell.getAttribute('data-status') || '';

                    const nameMatch = name.includes(searchText);
                    const statusMatch = statusFilter === '' || status.includes(statusFilter);

                    if (nameMatch && statusMatch) {
                        row.style.display = '';
                        visibleCount++;
                        const indexCell = row.querySelector('td:first-child');
                        if (indexCell) indexCell.textContent = visibleCount;
                    } else {
                        row.style.display = 'none';
                    }
                });

                const existingEmptyRow = document.getElementById('emptyFilterRow');
                if (visibleCount === 0 && !existingEmptyRow) {
                    const newEmptyRow = document.createElement('tr');
                    newEmptyRow.id = 'emptyFilterRow';
                    newEmptyRow.innerHTML = `
                        <td colspan="8" class="text-center text-muted py-3">
                            <i class="ti ti-search me-1"></i> Không tìm thấy kết quả phù hợp
                        </td>
                    `;
                    tableBody.appendChild(newEmptyRow);
                } else if (visibleCount > 0 && existingEmptyRow) {
                    existingEmptyRow.remove();
                }

                updateDisplayInfo(visibleCount);
            }

            function updateDisplayInfo(visibleCount) {
                if (infoText) {
                    const totalCount = {{ $phims->total() }};
                    infoText.textContent = `Hiển thị ${visibleCount} trong tổng số ${totalCount} phim`;
                }
            }

            document.getElementById('searchInput').addEventListener('input', filterTable);
            document.getElementById('statusFilter').addEventListener('change', filterTable);
        });
    </script>
@endsection
