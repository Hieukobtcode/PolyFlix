    @extends('layouts.admin')

    @section('title', 'Quản lý Chi Nhánh')
    @section('page-title', 'Quản lý Chi Nhánh')
    @section('breadcrumb', 'Danh sách Chi Nhánh')

    @section('content')



        <div class="container-fluid">

            <div class="card shadow-lg border-0 mb-4">

                <div class="card-body">
                    @if (Auth::user()->vai_tro_id == 1)
                        <!-- Form search realtime + select filter -->
                        <div class="row mb-4 g-2 align-items-center">

                            <!-- Tên chi nhánh -->
                            <div class="col-md-3">
                                <input type="text" id="searchTenChiNhanh" class="form-control"
                                    placeholder="Tìm theo tên chi nhánh...">
                            </div>

                            <!-- Tên quản lý -->
                            <div style="display: none" class="col-md-3">
                                <input type="text" id="searchTenQuanLy" class="form-control"
                                    placeholder="Tìm theo tên quản lý...">
                            </div>

                            <!-- Trạng thái quản lý -->
                            <div style="display: none" class="col-md-3">
                                <select name="quan_ly" class="form-select">
                                    <option value="" disabled {{ request('quan_ly') === null ? 'selected' : '' }}
                                        hidden>
                                        Quản lý</option>
                                    <option value="all" {{ request('quan_ly') === 'all' ? 'selected' : '' }}>Tất cả
                                    </option>
                                    <option value="phan_cong" {{ request('quan_ly') === 'phan_cong' ? 'selected' : '' }}>Đã
                                        phân
                                        công</option>
                                    <option value="chua_phan_cong"
                                        {{ request('quan_ly') === 'chua_phan_cong' ? 'selected' : '' }}>Chưa phân công
                                    </option>
                                </select>
                            </div>

                            <!-- Trạng thái chi nhánh (cái cũ) -->
                            <div style="display: none" class="col-md-3">
                                <select id="statusFilter" class="form-select">
                                    <option value="" {{ request('statusFilter') === null ? 'selected' : '' }} disabled
                                        hidden>Trạng thái</option>
                                    <option value="hoat_dong"
                                        {{ request('statusFilter') == 'hoat_dong' ? 'selected' : '' }}>
                                        Hoạt động</option>
                                    <option value="tam_dung" {{ request('statusFilter') == 'tam_dung' ? 'selected' : '' }}>
                                        Tạm
                                        dừng</option>
                                    <option value="dong_cua" {{ request('statusFilter') == 'dong_cua' ? 'selected' : '' }}>
                                        Đóng
                                        cửa</option>
                                </select>
                            </div>


                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <a href="{{ route('admin.chi-nhanh.create') }}"
                                class="btn btn-sm btn-primary d-inline-flex align-items-center gap-2 py-2 px-3">
                                <i class="ti ti-plus"></i> Thêm chi nhánh
                            </a>
                        </div>
                    @endif
                    <!-- Table mới đẹp -->
                    <div class="table-responsive">
                        <table class="table text-nowrap align-middle mb-0">
                            <thead class="bg-gradient-dark text-white">
                                <tr>
                                    <th class="text-center" style="width: 5%">
                                        <h6 class="fs-4 fw-semibold mb-0">#</h6>
                                    </th>
                                    <th>
                                        <h6 class="fs-4 fw-semibold mb-0">Tên Chi Nhánh</h6>
                                    </th>
                                    <th>
                                        <h6 class="fs-4 fw-semibold mb-0">Địa Chỉ</h6>
                                    </th>
                                    <th class="text-center" style="width: 15%">
                                        <h6 class="fs-4 fw-semibold mb-0">Quản Lý</h6>
                                    </th>
                                    <th class="text-center" style="width: 15%">
                                        <h6 class="fs-4 fw-semibold mb-0">Ngày Tạo</h6>
                                    </th>
                                    <th class="text-center" style="width: 15%">
                                        <h6 class="fs-4 fw-semibold mb-0">Trạng Thái</h6>
                                    </th>
                                    <th class="text-center" style="width: 20%">
                                        <h6 class="fs-4 fw-semibold mb-0">Thao Tác</h6>
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="chiNhanhTable">
                                @if (Auth::user()->vai_tro_id == 1)
                                    @forelse($chiNhanhs as $index => $chiNhanh)
                                        <tr class="data-row"
                                            data-state-ql="@if ($chiNhanh->quan_ly_id) da_phan_cong
                                        @elseif (in_array($chiNhanh->id, $pendingInvites)) dang_phan_cong
                                        @else chua_phan_cong @endif"
                                            data-state-cn="{{ $chiNhanh->trang_thai }}"
                                            data-ten-ql="@if ($chiNhanh->quan_ly_id) {{ $chiNhanh->quanLy->name ?? '' }} @elseif (in_array($chiNhanh->id, $pendingInvites)) Đang phân công @else Chưa phân công @endif">

                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td>{{ $chiNhanh->ten_chi_nhanh }}</td>
                                            <td>{{ $chiNhanh->dia_chi }}</td>

                                            <td class="text-center">
                                                @if ($chiNhanh->quan_ly_id)
                                                    <a href="{{ route('admin.users.show', $chiNhanh->quan_ly_id) }}"
                                                        class="text-decoration-none fw-medium">
                                                        {{ $chiNhanh->quanLy->name ?? 'ID: ' . $chiNhanh->quan_ly_id }}
                                                    </a>
                                                @elseif (in_array($chiNhanh->id, $pendingInvites))
                                                    <button type="button"
                                                        class="badge bg-warning-subtle text-warning border-0"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#cancelInviteModal{{ $chiNhanh->id }}">
                                                        Đang phân công
                                                    </button>
                                                @else
                                                    <span class="badge bg-secondary-subtle text-muted">Chưa phân công</span>
                                                @endif
                                            </td>

                                            <td class="text-center">
                                                {{ \Carbon\Carbon::parse($chiNhanh->created_at)->format('d/m/Y H:i') }}
                                            </td>
                                            <td class="text-center">
                                                @if ($chiNhanh->trang_thai === 'hoat_dong')
                                                    <span class="badge bg-success-subtle text-success">Hoạt động</span>
                                                @elseif ($chiNhanh->trang_thai === 'tam_dung')
                                                    <span class="badge bg-warning-subtle text-warning">Tạm dừng</span>
                                                @else
                                                    <span class="badge bg-secondary-subtle text-muted">Đóng cửa</span>
                                                @endif
                                            </td>

                                            <td class="text-center">
                                                <div class="dropdown dropstart">
                                                    <a href="javascript:void(0)" class="text-muted"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="ti ti-dots-vertical fs-6"></i>
                                                    </a>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a class="dropdown-item d-flex align-items-center gap-2"
                                                                href="{{ route('admin.chi-nhanh.show', $chiNhanh->id) }}">
                                                                <i class="ti ti-eye fs-5"></i> Xem chi nhánh
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item d-flex align-items-center gap-2"
                                                                href="{{ route('admin.chi-nhanh.edit', $chiNhanh->id) }}">
                                                                <i class="ti ti-edit fs-5"></i> Chỉnh sửa
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item d-flex align-items-center gap-2"
                                                                href="{{ route('admin.rap-phim.create', ['chiNhanhId' => $chiNhanh->id]) }}">
                                                                <i class="ti ti-plus fs-5"></i> Thêm rạp chiếu
                                                            </a>
                                                        </li>
                                                        @if (!$chiNhanh->quan_ly_id && !in_array($chiNhanh->id, $pendingInvites))
                                                            <li>
                                                                <button
                                                                    class="dropdown-item d-flex align-items-center gap-2"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#inviteModal{{ $chiNhanh->id }}">
                                                                    <i class="ti ti-user-plus fs-5 text-warning"></i> Phân
                                                                    công
                                                                    quản lý
                                                                </button>
                                                            </li>
                                                        @endif
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-3">
                                                <i class="ti ti-folder-open me-1"></i> Không có dữ liệu
                                            </td>
                                        </tr>
                                    @endforelse
                                @elseif(Auth::user()->vai_tro_id == 2)
                                    @forelse($chiNhanhs as $index => $chiNhanh)
                                        <tr class="data-row" data-state-cn="{{ $chiNhanh->trang_thai }}">

                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td>{{ $chiNhanh->ten_chi_nhanh }}</td>
                                            <td>{{ $chiNhanh->dia_chi }}</td>

                                            <td class="text-center">
                                                <span class="badge bg-success-subtle text-success">Bạn quản lý</span>
                                            </td>

                                            <td class="text-center">
                                                {{ \Carbon\Carbon::parse($chiNhanh->created_at)->format('d/m/Y H:i') }}
                                            </td>
                                            <td class="text-center">
                                                @if ($chiNhanh->trang_thai === 'hoat_dong')
                                                    <span class="badge bg-success-subtle text-success">Hoạt động</span>
                                                @elseif ($chiNhanh->trang_thai === 'tam_dung')
                                                    <span class="badge bg-warning-subtle text-warning">Tạm dừng</span>
                                                @else
                                                    <span class="badge bg-secondary-subtle text-muted">Đóng cửa</span>
                                                @endif
                                            </td>

                                            <td class="text-center">
                                                <div class="dropdown dropstart">
                                                    <a href="javascript:void(0)" class="text-muted"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="ti ti-dots-vertical fs-6"></i>
                                                    </a>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a class="dropdown-item d-flex align-items-center gap-2"
                                                                href="{{ route('admin.chi-nhanh.show', $chiNhanh->id) }}">
                                                                <i class="ti ti-eye fs-5"></i> Xem chi nhánh
                                                            </a>
                                                        </li>
                                                       
                                                        <li>
                                                            <a class="dropdown-item d-flex align-items-center gap-2"
                                                                href="{{ route('admin.rap-phim.create', ['chiNhanhId' => $chiNhanh->id]) }}">
                                                                <i class="ti ti-plus fs-5"></i> Thêm rạp chiếu
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-3">
                                                <i class="ti ti-folder-open me-1"></i> Không có chi nhánh nào bạn quản lý
                                            </td>
                                        </tr>
                                    @endforelse
                                @endif

                            </tbody>
                        </table>

                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div>
                            <small class="text-muted" id="tableCount">
                                Hiển thị {{ $chiNhanhs->count() }} trên tổng số {{ $chiNhanhs->total() }} chi nhánh
                            </small>
                        </div>
                        <div>
                            {{ $chiNhanhs->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
        {{-- Modal hủy lời mời cho Chi Nhánh --}}
        @foreach ($chiNhanhs as $chiNhanh)
            @if (in_array($chiNhanh->id, $pendingInvites))
                <div class="modal fade" id="cancelInviteModal{{ $chiNhanh->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-md">
                        <div class="modal-content">
                            <div class="modal-header modal-colored-header bg-warning text-white py-2 px-3">
                                <h5 class="modal-title text-white mb-0">Thông tin lời mời</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                    aria-label="Đóng"></button>
                            </div>
                            <div class="modal-body text-center py-3 px-3">
                                <p class="mb-3"><strong>Email:</strong><br>
                                    <span class="text-muted">{{ $pendingEmails[$chiNhanh->id] ?? 'Không rõ' }}</span>
                                </p>
                                <p class="text-muted mb-0">Bạn có chắc chắn muốn hủy lời mời này?</p>
                            </div>
                            <div class="modal-footer justify-content-between px-3 pb-3">
                                <button class="btn btn-light btn-sm rounded-pill px-4"
                                    data-bs-dismiss="modal">Đóng</button>
                                <form action="{{ route('admin.invite.cancel') }}" method="POST"
                                    onsubmit="return confirm('Bạn có chắc chắn muốn hủy lời mời đã gửi đến {{ $pendingEmails[$chiNhanh->id] ?? '' }}?')">
                                    @csrf
                                    <input type="hidden" name="chi_nhanh_id" value="{{ $chiNhanh->id }}">
                                    <input type="hidden" name="loai_quan_ly" value="1">
                                    <button type="submit"
                                        class="btn bg-warning-subtle text-warning btn-sm rounded-pill px-4">
                                        <i class="ti ti-trash"></i> Hủy lời mời
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach

        {{-- Modal gửi lời mời cho Chi Nhánh --}}
        @foreach ($chiNhanhs as $chiNhanh)
            @if (!$chiNhanh->quan_ly_id && !in_array($chiNhanh->id, $pendingInvites))
                <div class="modal fade" id="inviteModal{{ $chiNhanh->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable modal-md">
                        <form method="POST" action="{{ route('invite.send') }}">
                            @csrf
                            <input type="hidden" name="loai_quan_ly" value="1">
                            <input type="hidden" name="chi_nhanh_id" value="{{ $chiNhanh->id }}">
                            <div class="modal-content">
                                <div class="modal-header modal-colored-header bg-success text-white">
                                    <h4 class="modal-title text-white">Phân công quản lý chi nhánh</h4>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                        aria-label="Đóng"></button>
                                </div>
                                <div class="modal-body py-4 px-4">
                                    <label class="fw-bold mb-2">Email người quản lý</label>
                                    <input type="email" name="email" class="form-control" required>
                                </div>
                                <div class="modal-footer justify-content-between px-3 pb-3">
                                    <button type="button" class="btn btn-light btn-sm rounded-pill px-4"
                                        data-bs-dismiss="modal">
                                        Hủy
                                    </button>
                                    <button type="submit"
                                        class="btn bg-success-subtle text-success btn-sm rounded-pill px-4">
                                        <i class="ti ti-send"></i> Gửi lời mời
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        @endforeach



    @endsection

    @section('scripts')
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Kích hoạt tooltip
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                tooltipTriggerList.forEach(function(el) {
                    new bootstrap.Tooltip(el);
                });

                // Hover tr nhẹ nhàng
                document.querySelectorAll('.table tbody tr').forEach(row => {
                    row.addEventListener('mouseover', () => row.style.transition =
                        'background-color 0.4s ease, box-shadow 0.4s ease');
                    row.addEventListener('mouseout', () => row.style.transition =
                        'background-color 0.4s ease, box-shadow 0.4s ease');
                });
            });

            function toggleCancelBtn(id) {
                const form = document.getElementById('cancel-form-' + id);
                form.style.display = (form.style.display === 'none') ? 'inline-block' : 'none';
            }

            function confirmCancelInvite(form, email) {
                return confirm(`Bạn có chắc chắn muốn hủy lời mời đã gửi đến ${email}?`);
            }

            $('#statusFilter').select2({
                theme: 'bootstrap-5',
                placeholder: 'Tất cả trạng thái',
                allowClear: true,
                dropdownParent: $('body'),
                width: '100%'
            }).on('change', function() {
                var val = $(this).val();
                var selection = $(this).next('.select2-container').find('.select2-selection--single');

                // Xóa hết class trước
                selection.removeClass('is-active is-pause is-closed');

                // Add class theo giá trị
                if (val === 'hoat_dong') {
                    selection.addClass('is-active');
                } else if (val === 'tam_dung') {
                    selection.addClass('is-pause');
                } else if (val === 'dong_cua') {
                    selection.addClass('is-closed');
                }
            });
            $('#filterTrangThaiQL').select2({
                theme: 'bootstrap-5',
                placeholder: 'Quản lý',
                allowClear: true,
                dropdownParent: $('body'),
                width: '100%'
            }).on('change', function() {
                var val = $(this).val();
                var selection = $(this).next('.select2-container').find('.select2-selection--single');

                // Xóa hết class trước
                selection.removeClass('is-active is-pause is-closed');

                // Add class theo giá trị
                if (val === 'chua_phan_cong') {
                    selection.addClass('is-closed');
                } else if (val === 'dang_phan_cong') {
                    selection.addClass('is-pause');
                } else if (val === 'da_phan_cong') {
                    selection.addClass('is-active');
                }
            });
            $('#searchTenChiNhanh, #searchTenQuanLy, #filterTrangThaiQL, #statusFilter').on('input change', function() {
                performSearch();
            });

            function performSearch() {
                const keywordCN = $('#searchTenChiNhanh').val().toLowerCase();
                const keywordQL = $('#searchTenQuanLy').val().toLowerCase();
                const trangThaiQL = $('#filterTrangThaiQL').val();
                const trangThaiCN = $('#statusFilter').val();

                let matchCount = 0;

                $('#chiNhanhTable tr.data-row').each(function() {
                    const nameCN = $(this).find('td:nth-child(2)').text().toLowerCase();
                    const nameQL = $(this).data('ten-ql').toLowerCase().trim();
                    const isHasManager = nameQL !== 'chưa phân công' && nameQL !== 'đang phân công';


                    const stateQL = $(this).data('state-ql').trim();
                    const stateCN = $(this).data('state-cn').trim();

                    const matchCN = nameCN.includes(keywordCN);
                    const matchQL = !keywordQL || (isHasManager && nameQL.includes(keywordQL));
                    const matchStateQL = !trangThaiQL || stateQL === trangThaiQL;
                    const matchStateCN = !trangThaiCN || stateCN === trangThaiCN;

                    if (matchCN && matchQL && matchStateQL && matchStateCN) {
                        $(this).show();
                        matchCount++;
                    } else {
                        $(this).hide();
                    }
                });

                // Xử lý "Không tìm thấy"
                if (matchCount === 0) {
                    if ($('#chiNhanhTable tr.no-result').length === 0) {
                        $('#chiNhanhTable').append(`
                <tr class="no-result">
                    <td colspan="7" class="text-center text-muted py-3">
                        <i class="fas fa-folder-open me-1"></i> Không tìm thấy kết quả phù hợp
                    </td>
                </tr>
            `);
                    }
                } else {
                    $('#chiNhanhTable .no-result').remove();
                }

                // Cập nhật số lượng hiển thị
                $('#tableCount').text(`Hiển thị ${matchCount} trên tổng số {{ $chiNhanhs->total() }} chi nhánh`);
            }
        </script>
    @endsection
