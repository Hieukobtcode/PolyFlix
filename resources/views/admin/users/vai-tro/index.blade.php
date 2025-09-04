@extends('layouts.admin')
@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">

                <div class="table-responsive">
                    <table class="table text-nowrap align-middle mb-0">
                        <thead class="bg-gradient-dark text-white">
                            <tr>
                                <th class="text-center" style="width: 5%">
                                    <h6 class="fs-4 fw-semibold mb-0">#</h6>
                                </th>
                                <th>
                                    <h6 class="fs-4 fw-semibold mb-0">Tên vai trò</h6>
                                </th>
                                <th>
                                    <h6 class="fs-4 fw-semibold mb-0">Mô tả</h6>
                                </th>
                                <th class="text-center" style="width: 20%">
                                    <h6 class="fs-4 fw-semibold mb-0">Thao tác</h6>
                                </th>
                            </tr>
                        </thead>
                        <tbody id="vaiTroTable">
                            @forelse($vaiTros as $index => $vaiTro)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>{{ $vaiTro->ten }}</td>
                                    <td>{{ Str::limit($vaiTro->mo_ta, 50) }}</td>
                                    <td class="text-center">
                                        <div class="dropdown dropstart">
                                            <a href="javascript:void(0)" class="text-muted" data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                                <i class="ti ti-dots-vertical fs-6"></i>
                                            </a>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a class="dropdown-item d-flex align-items-center gap-2"
                                                        href="{{ route('admin.vai-tro.show', $vaiTro->id) }}">
                                                        <i class="ti ti-eye fs-5"></i> Xem
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item d-flex align-items-center gap-2"
                                                        href="{{ route('admin.vai-tro.edit', $vaiTro->id) }}">
                                                        <i class="ti ti-edit fs-5"></i> Chỉnh sửa
                                                    </a>
                                                </li>
                                                <li>
                                                    <form action="{{ route('admin.vai-tro.destroy', $vaiTro->id) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Bạn có chắc chắn muốn xóa?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="dropdown-item d-flex align-items-center gap-2 text-danger">
                                                            <i class="ti ti-trash fs-5"></i> Xóa
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr id="emptyRow">
                                    <td colspan="5" class="text-center text-muted py-4">
                                        <div class="py-3">
                                            <i class="ti ti-folder-open fs-3 mb-3"></i>
                                            <p class="mb-0">Không có dữ liệu</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div>
                        <small class="text-muted">Hiển thị {{ $vaiTros->count() }} trong tổng số {{ $vaiTros->total() }} vai
                            trò</small>
                    </div>
                    <div>
                        {{ $vaiTros->links('pagination::bootstrap-5') }}
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
            const resetBtn = document.getElementById('resetFilter');
            const rows = document.querySelectorAll('#vaiTroTable tr');

            function filterTable() {
                const searchText = searchInput.value.toLowerCase();
                let visibleCount = 0;

                rows.forEach((row) => {
                    const nameCell = row.querySelector('td:nth-child(2)');
                    if (!nameCell) return;

                    const name = nameCell.textContent.toLowerCase();
                    const match = name.includes(searchText);

                    if (match) {
                        row.style.display = '';
                        visibleCount++;
                        row.querySelector('td:first-child').textContent = visibleCount;
                    } else {
                        row.style.display = 'none';
                    }
                });

                const emptyRow = document.getElementById('emptyRow');
                if (visibleCount === 0 && !emptyRow) {
                    const newRow = document.createElement('tr');
                    newRow.id = 'emptyRow';
                    newRow.innerHTML = `
                        <td colspan="5" class="text-center text-muted py-4">
                            <div class="py-3">
                                <i class="ti ti-search fs-3 mb-3"></i>
                                <p class="mb-0">Không tìm thấy kết quả phù hợp</p>
                            </div>
                        </td>`;
                    document.getElementById('vaiTroTable').appendChild(newRow);
                } else if (visibleCount > 0 && emptyRow) {
                    emptyRow.remove();
                }

                const infoText = document.querySelector('.text-muted');
                if (infoText) {
                    const total = rows.length - (document.getElementById('emptyRow') ? 1 : 0);
                    infoText.textContent = `Hiển thị ${visibleCount} trong tổng số ${total} vai trò`;
                }
            }

            searchInput.addEventListener('input', filterTable);
            resetBtn.addEventListener('click', function() {
                searchInput.value = '';
                filterTable();
                this.classList.add('animate-spin');
                setTimeout(() => this.classList.remove('animate-spin'), 300);
            });

            document.head.insertAdjacentHTML('beforeend', `
                <style>
                    @keyframes spin {
                        0% { transform: rotate(0deg); }
                        100% { transform: rotate(360deg); }
                    }
                    .animate-spin i {
                        animation: spin 0.5s linear;
                    }
                </style>
            `);
        });
    </script>
@endsection
