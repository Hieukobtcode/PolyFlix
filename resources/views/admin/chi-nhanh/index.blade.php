    @extends('layouts.admin')

    @section('title', 'Quản lý Chi Nhánh')
    @section('page-title', 'Quản lý Chi Nhánh')
    @section('breadcrumb', 'Danh sách Chi Nhánh')

    @section('styles')
        <style>
            .card {
                border-radius: 16px;
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
                border: 1px solid #e0e6ed;
                overflow: hidden;
            }

            .card-header {
                background: linear-gradient(90deg, #f6c343, #f08a24);
                color: #fff;
                padding: 1rem 1.5rem;
                border-bottom: 2px solid #fff;
            }

            .card-header h5 {
                font-weight: 700;
                letter-spacing: 0.5px;
                font-size: 1.25rem;
            }

            /* Input đẹp */
            .form-control {
                border-radius: 12px;
                border: 1px solid #d8dbe0;
                padding: 10px 16px;
                font-size: 1rem;
                transition: all 0.3s ease;
            }

            .form-control:focus {
                border-color: #f08a24;
                box-shadow: 0 0 0 0.2rem rgba(240, 138, 36, 0.25);
                outline: none;
            }

            .select2-container .select2-selection--single {
                border-radius: 12px !important;
                border: 1px solid #d8dbe0 !important;
                padding: 8px 12px;
                height: auto !important;
                min-height: 44px;
                font-size: 1rem;
                display: flex;
                align-items: center;
                transition: all 0.3s ease;
                background-color: #fff;
            }

            /* Khi có chọn trạng thái — vùng cam ôm trọn box */
            .select2-selection--single.is-active {
                background-color: #28a745 !important;
                border-color: #28a745 !important;
                color: #fff !important;
                box-shadow: 0 4px 8px rgba(40, 167, 69, 0.25);
            }

            /* Tạm dừng → cam */
            .select2-selection--single.is-pause {
                background-color: #f08a24 !important;
                border-color: #f08a24 !important;
                color: #fff !important;
                box-shadow: 0 4px 8px rgba(240, 138, 36, 0.25);
            }

            /* Đóng cửa → xám */
            .select2-selection--single.is-closed {
                background-color: #6c757d !important;
                border-color: #6c757d !important;
                color: #fff !important;
                box-shadow: 0 4px 8px rgba(108, 117, 125, 0.25);
            }

            /* Text của option được chọn */
            .select2-container--default .select2-selection--single .select2-selection__rendered {
                padding: 0;
                margin: 0;
                color: inherit;
                font-weight: 600;
                font-size: 0.95rem;
                line-height: 1.5;
            }

            /* Placeholder khi chưa chọn */
            .select2-container--default .select2-selection__placeholder {
                color: #999 !important;
                background: none !important;
            }

            /* Clear button X */
            .select2-container--default .select2-selection__clear {
                color: #fff !important;
                font-size: 1.2em;
                margin-right: 10px;
                cursor: pointer;
            }

            .select2-container--default .select2-selection__clear:hover {
                color: #ffdcb3 !important;
            }

            /* Focus */
            .select2-container--default.select2-container--focus .select2-selection {
                border-color: #f08a24 !important;
                box-shadow: 0 0 0 0.2rem rgba(240, 138, 36, 0.25) !important;
            }

            /* Dropdown option hover */
            .select2-results__option {
                transition: all 0.2s ease;
            }

            .select2-results__option--highlighted {
                background-color: #f08a24 !important;
                color: #fff !important;
                transition: all 0.2s ease;
            }

            /* Option selected */
            .select2-results__option--selected {
                background-color: #f6c343 !important;
                color: #fff !important;
            }



            .table {
                border-collapse: separate;
                border-spacing: 0;
                border-radius: 12px;
                overflow: hidden;
            }

            .table thead {
                color: #fff;
            }

            .table thead th {
                background: #f6c343;
                padding: 1rem;
                font-weight: 700;
                letter-spacing: 0.5px;
                border: none;
                color: #fff;
                /* để chữ trắng */
            }


            .table tbody tr {
                background-color: #fff;
                transition: none;
            }


            .table tbody tr:hover td {
                background-color: rgba(142, 244, 255, 0.277);
                transform: scale(1.015);
            }

            .table tbody td {
                border: none !important;
                /* Xóa toàn bộ border */
                box-shadow: none;
                /* Không bị line giữa các td */
                padding: 1.1rem 0.9rem;
                transition: transform 0.8s ease, box-shadow 0.8s ease, background-color 0.8s ease;
            }

            .pagination {
                justify-content: flex-end;
            }

            .pagination .page-item .page-link {
                border-radius: 8px;
                margin: 0 2px;
                transition: all 0.2s ease;
            }

            .pagination .page-item .page-link:hover {
                background-color: #5A8DEE;
                color: #fff;
                border-color: #5A8DEE;
            }



            /* Modal animation */
            .modal.fade .modal-dialog {
                transform: scale(0.95);
                transition: all 0.3s ease-out;
                opacity: 0;
            }

            .modal.fade.show .modal-dialog {
                transform: scale(1);
                opacity: 1;
            }



            .btn-poly {
                background-color: #fff3cd;
                color: #f08a24;
                border: 1px solid #f08a24;
                font-weight: 600;
                letter-spacing: 0.5px;
                transition: all 0.3s ease;
            }

            .btn-poly:hover {
                background-color: #ff5900;
                color: #ffffff;
                border-color: #f08a24;
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(248, 191, 49, 0.637);
            }
        </style>
    @endsection

    @section('content')



        <div class="container-fluid">
            {{-- Thông báo lỗi validate --}}
            @if ($errors->any())
                <div class="alert alert-danger d-flex align-items-start gap-3 alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle fa-lg mt-1"></i>
                    <div>
                        <ul class="mb-0 mt-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <button type="button" class="btn-close mt-1" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif


            <div class="card shadow-lg border-0 mb-4">
                <div
                    class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center py-3">
                    <h4 class="mb-0">Danh sách Chi Nhánh</h4>
                    <a href="{{ route('admin.chi-nhanh.create') }}" class="btn btn-poly rounded-pill px-4 py-2">
                        <i class="fas fa-plus me-2"></i> Thêm chi nhánh
                    </a>
                </div>

                <div class="card-body">
                    <!-- Form search realtime + select filter -->
                    <div class="row mb-4 g-2 align-items-center">

                        <!-- Tên chi nhánh -->
                        <div class="col-md-3">
                            <input type="text" id="searchTenChiNhanh" class="form-control"
                                placeholder="Tìm theo tên chi nhánh...">
                        </div>

                        <!-- Tên quản lý -->
                        <div class="col-md-3">
                            <input type="text" id="searchTenQuanLy" class="form-control"
                                placeholder="Tìm theo tên quản lý...">
                        </div>

                        <!-- Trạng thái quản lý -->
                        <div class="col-md-3">
                            <select id="filterTrangThaiQL" class="form-select select2">
                                <option value="">Quản lý</option>
                                <option value="chua_phan_cong">Chưa phân công</option>
                                <option value="dang_phan_cong">Đang phân công</option>
                                <option value="da_phan_cong">Đã phân công</option>
                            </select>
                        </div>

                        <!-- Trạng thái chi nhánh (cái cũ) -->
                        <div class="col-md-3">
                            <select id="statusFilter" class="form-select select2">
                                <option value="">Tất cả trạng thái</option>
                                <option value="hoat_dong">Hoạt động</option>
                                <option value="tam_dung">Tạm dừng</option>
                                <option value="dong_cua">Đóng cửa</option>
                            </select>
                        </div>

                    </div>

                    <!-- Table mới đẹp -->
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="bg-gradient-dark text-white">
                                <tr>
                                    <th scope="col" class="text-center" style="width: 5%">#</th>
                                    <th scope="col">Tên Chi Nhánh</th>
                                    <th scope="col">Địa Chỉ</th>
                                    <th scope="col" class="text-center" style="width: 15%">Quản Lý</th>
                                    <th scope="col" class="text-center" style="width: 15%">Ngày Tạo</th>
                                    <th scope="col" class="text-center" style="width: 15%">Trạng Thái</th>
                                    <th scope="col" class="text-center" style="width: 20%">Thao Tác</th>
                                </tr>
                            </thead>
                            <tbody id="chiNhanhTable">
                                @forelse($chiNhanhs as $index => $chiNhanh)
                                    <tr class="data-row"
                                        data-state-ql="@if ($chiNhanh->quan_ly_id) da_phan_cong
                                        @elseif (in_array($chiNhanh->id, $pendingInvites)) dang_phan_cong
                                        @else chua_phan_cong @endif"
                                        data-state-cn="{{ $chiNhanh->trang_thai }}"
                                        data-ten-ql="
                                    @if ($chiNhanh->quan_ly_id) {{ $chiNhanh->quanLy->name ?? '' }}
                                    @elseif (in_array($chiNhanh->id, $pendingInvites))
                                        Đang phân công
                                    @else
                                        Chưa phân công @endif
                                ">

                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td>{{ $chiNhanh->ten_chi_nhanh }}</td>
                                        <td>{{ $chiNhanh->dia_chi }}</td>

                                        <td class="text-center">
                                            @if ($chiNhanh->quan_ly_id)
                                                <a href="{{ route('admin.users.show', $chiNhanh->quan_ly_id) }}"
                                                    class="text-decoration-none">
                                                    {{ $chiNhanh->quanLy->name ?? 'ID: ' . $chiNhanh->quan_ly_id }}
                                                </a>
                                            @elseif (in_array($chiNhanh->id, $pendingInvites))
                                                <button type="button" class="badge bg-warning text-dark border-0"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#cancelInviteModal{{ $chiNhanh->id }}">
                                                    Đang phân công
                                                </button>
                                            @else
                                                <span class="badge bg-secondary text-dark border-0">Chưa phân công</span>
                                            @endif
                                        </td>

                                        <td class="text-center">
                                            {{ \Carbon\Carbon::parse($chiNhanh->created_at)->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="text-center">
                                            @if ($chiNhanh->trang_thai === 'hoat_dong')
                                                <span class="badge bg-success">Hoạt động</span>
                                            @elseif ($chiNhanh->trang_thai === 'tam_dung')
                                                <span class="badge bg-warning">Tạm dừng</span>
                                            @else
                                                <span class="badge bg-secondary">Đóng cửa</span>
                                            @endif
                                        </td>
                                        <td class="text-center">

                                            <!-- View -->
                                            <a href="{{ route('admin.chi-nhanh.show', $chiNhanh->id) }}"
                                                class="btn btn-sm btn-outline-info" data-bs-toggle="tooltip"
                                                data-bs-placement="top" title="Xem chi nhánh">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            {{-- Edit --}}
                                            <a href="{{ route('admin.chi-nhanh.edit', $chiNhanh->id) }}"
                                                class="btn btn-sm btn-outline-primary" title="Chỉnh sửa chi nhánh">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <!-- Add Cinema -->
                                            <a href="{{ route('admin.rap-phim.create', ['chiNhanhId' => $chiNhanh->id]) }}"
                                                class="btn btn-sm btn-outline-success" title="Thêm rạp chiếu"
                                                data-bs-toggle="tooltip">
                                                <i class="fas fa-plus-circle"></i>
                                            </a>

                                            {{-- Quản lý --}}
                                            @if (!$chiNhanh->quan_ly_id && !in_array($chiNhanh->id, $pendingInvites))
                                                <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal"
                                                    data-bs-target="#inviteModal{{ $chiNhanh->id }}"
                                                    title="Phân công quản lý">
                                                    <i class="fa-solid fa-user-plus" style="color: #FFD43B;"></i>
                                                </button>
                                            @endif

                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-3">
                                            <i class="fas fa-folder-open me-1"></i> Không có dữ liệu
                                        </td>
                                    </tr>
                                @endforelse
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
                    <div class="modal-dialog modal-dialog-centered modal-sm">
                        <div class="modal-content"
                            style="border-radius: 16px; overflow: hidden; border: 1px solid #e0e6ed; box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);">
                            <div class="modal-header"
                                style="background: linear-gradient(90deg, #f6c343, #f08a24); color: #fff; padding: 1rem 1.5rem;">
                                <h5 class="modal-title fw-semibold mb-0">Thông tin lời mời</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Đóng"></button>
                            </div>
                            <div class="modal-body text-center py-4 px-4">
                                <p class="mb-3"><strong>Email:</strong><br>
                                    <span class="text-muted">{{ $pendingEmails[$chiNhanh->id] ?? 'Không rõ' }}</span>
                                </p>
                                <p class="text-muted mb-0">Bạn có chắc chắn muốn hủy lời mời này?</p>
                            </div>
                            <div class="modal-footer justify-content-between px-3 pb-3">
                                <button class="btn btn-secondary btn-sm rounded-pill px-4"
                                    data-bs-dismiss="modal">Đóng</button>
                                <form action="{{ route('admin.invite.cancel') }}" method="POST"
                                    onsubmit="return confirm('Bạn có chắc chắn muốn hủy lời mời đã gửi đến {{ $pendingEmails[$chiNhanh->id] ?? '' }}?')">
                                    @csrf
                                    <input type="hidden" name="chi_nhanh_id" value="{{ $chiNhanh->id }}">
                                    <input type="hidden" name="loai_quan_ly" value="1">
                                    <button type="submit" class="btn btn-poly btn-sm rounded-pill px-4">
                                        <i class="fas fa-times-circle me-1"></i> Hủy lời mời
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
                    <div class="modal-dialog">
                        <form method="POST" action="{{ route('invite.send') }}">
                            @csrf
                            <input type="hidden" name="loai_quan_ly" value="1">
                            <input type="hidden" name="chi_nhanh_id" value="{{ $chiNhanh->id }}">
                            <div class="modal-content"
                                style="border-radius: 16px; overflow: hidden; border: 1px solid #e0e6ed; box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);">
                                <div class="modal-header"
                                    style="background: linear-gradient(90deg, #f6c343, #f08a24); color: #fff; padding: 1rem 1.5rem;">
                                    <h5 class="modal-title fw-semibold mb-0">Phân công quản lý chi nhánh</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Đóng"></button>
                                </div>
                                <div class="modal-body py-4 px-4">
                                    <label class="fw-bold mb-2">Email người quản lý</label>
                                    <input type="email" name="email" class="form-control" required>
                                </div>
                                <div class="modal-footer justify-content-between px-3 pb-3">
                                    <button type="button" class="btn btn-secondary btn-sm rounded-pill px-4"
                                        data-bs-dismiss="modal">Hủy</button>
                                    <button type="submit" class="btn btn-poly btn-sm rounded-pill px-4">
                                        <i class="fas fa-paper-plane me-1"></i> Gửi lời mời
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
