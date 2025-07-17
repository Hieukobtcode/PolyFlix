@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">

                <!-- Thanh tìm kiếm -->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <input type="text" id="searchInput" class="form-control" placeholder="Tìm theo tên cấp bậc...">
                    </div>
                </div>

                <!-- Nút thêm -->
                <a href="{{ route('admin.cap-bac-the.create') }}"
                    class="btn btn-sm btn-primary d-inline-flex align-items-center gap-2 py-2 px-3 mb-3">
                    <i class="ti ti-plus fs-5"></i> Thêm cấp bậc
                </a>

                <!-- Bảng danh sách -->
                <div class="table-responsive">
                    <table class="table text-nowrap align-middle mb-0">
                        <thead class="bg-gradient-dark text-white">
                            <tr>
                                <th class="text-center" style="width: 5%">
                                    <h6 class="fs-4 fw-semibold mb-0">#</h6>
                                </th>
                                <th class="text-center" style="width: 25%">
                                    <h6 class="fs-4 fw-semibold mb-0">Tên cấp bậc</h6>
                                </th>
                                <th class="text-center" style="width: 15%">
                                    <h6 class="fs-4 fw-semibold mb-0">Tổng chi tiêu</h6>
                                </th>
                                <th class="text-center" style="width: 15%">
                                    <h6 class="fs-4 fw-semibold mb-0">% Hoàn điểm</h6>
                                </th>
                                {{-- <th class="text-center" style="width: 15%">
                                    <h6 class="fs-4 fw-semibold mb-0">% Ưu đãi DV</h6>
                                </th> --}}
                                <th class="text-center" style="width: 15%">
                                    <h6 class="fs-4 fw-semibold mb-0">Trạng thái</h6>
                                </th>
                                <th class="text-center" style="width: 20%">
                                    <h6 class="fs-4 fw-semibold mb-0">Thao tác</h6>
                                </th>
                            </tr>
                        </thead>
                        <tbody id="membershipTable">
                            @forelse($capBacThes as $index => $capBacThe)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td class="text-center">{{ $capBacThe->ten }}</td>
                                    <td class="text-center">{{ number_format($capBacThe->tong_chi_tieu) }} đ</td>
                                    <td class="text-center">{{ $capBacThe->phan_tram_ve }}%</td>
                                    {{-- <td class="text-center">{{ $capBacThe->phan_tram_dich_vu }}%</td> --}}
                                    <td class="text-center">
                                        <span
                                            class="badge rounded-pill {{ $capBacThe->is_default ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $capBacThe->is_default ? 'Mặc định' : 'Thường' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="dropdown dropstart">
                                            <a href="javascript:void(0)" class="text-muted" data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                                <i class="ti ti-dots-vertical fs-6"></i>
                                            </a>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a class="dropdown-item d-flex align-items-center gap-2"
                                                        href="{{ route('admin.cap-bac-the.edit', $capBacThe->id) }}">
                                                        <i class="ti ti-edit fs-5"></i> Chỉnh sửa
                                                    </a>
                                                </li>
                                                @if (!$capBacThe->is_default)
                                                    <li>
                                                        <form
                                                            action="{{ route('admin.cap-bac-the.destroy', $capBacThe->id) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('Bạn có chắc chắn muốn xóa cấp bậc thẻ này?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="dropdown-item d-flex align-items-center gap-2">
                                                                <i class="ti ti-trash fs-5 text-danger"></i> Xóa
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
                                    <td colspan="7" class="text-center text-muted py-3">
                                        <i class="ti ti-folder-off me-1"></i> Không có dữ liệu
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Phân trang -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div>
                        <small class="text-muted">Hiển thị {{ $capBacThes->count() }} trong tổng số
                            {{ $capBacThes->total() }} cấp bậc thẻ</small>
                    </div>
                    <div>
                        {{ $capBacThes->links('pagination::bootstrap-5') }}
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const rows = document.querySelectorAll('#membershipTable tr');

            function filterTable() {
                const searchText = searchInput.value.toLowerCase();
                let visibleCount = 0;

                rows.forEach(row => {
                    if (row.querySelector('td')) {
                        const nameCell = row.querySelector('td:nth-child(2)');
                        if (!nameCell) return;
                        const name = nameCell.textContent.toLowerCase();
                        const nameMatch = name.includes(searchText);

                        if (nameMatch) {
                            row.style.display = '';
                            visibleCount++;
                            const indexCell = row.querySelector('td:first-child');
                            if (indexCell) indexCell.textContent = visibleCount;
                        } else {
                            row.style.display = 'none';
                        }
                    }
                });

                const emptyRow = document.getElementById('emptyRow');
                if (visibleCount === 0) {
                    if (!emptyRow) {
                        const newEmptyRow = document.createElement('tr');
                        newEmptyRow.id = 'emptyRow';
                        newEmptyRow.innerHTML = `
                            <td colspan="7" class="text-center text-muted py-4">
                                <div class="py-3">
                                    <i class="ti ti-search fs-3 mb-3"></i>
                                    <p class="mb-0">Không tìm thấy kết quả phù hợp</p>
                                </div>
                            </td>`;
                        document.getElementById('membershipTable').appendChild(newEmptyRow);
                    }
                } else if (emptyRow) {
                    emptyRow.remove();
                }

                const infoText = document.querySelector('.text-muted');
                if (infoText) {
                    const totalCount = rows.length - (document.getElementById('emptyRow') ? 1 : 0);
                    infoText.textContent = `Hiển thị ${visibleCount} trong tổng số ${totalCount} cấp bậc thẻ`;
                }
            }

            searchInput.addEventListener('input', filterTable);
        });
    </script>
@endsection
