@extends('layouts.admin')

@section('title', 'Quản lý Chi Nhánh')
@section('page-title', 'Quản lý Chi Nhánh')
@section('breadcrumb', 'Danh sách Chi Nhánh')

@section('styles')
    <style>
        .card {
            border-radius: 10px;
        }

        .table th,
        .table td {
            vertical-align: middle;
        }

        .btn-group .btn {
            border-radius: 5px;
        }

        .badge {
            font-size: 0.9em;
            padding: 0.5em 1em;
        }

        .pagination {
            justify-content: end;
        }

        .table-dark {
            background-color: #343a40;
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

        <div class="card shadow-sm border-0">

            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Danh sách Chi Nhánh</h5>

                <a href="{{ route('admin.chi-nhanh.create') }}" class="btn btn-light btn-sm" title="Thêm chi nhánh">
                    <i class="fas fa-plus me-1"></i> Thêm chi nhánh
                </a>

            </div>

            <div class="card-body p-4">

                <form method="GET" action="{{ route('admin.chi-nhanh.index') }}" class="row mb-4">

                    <div class="col-md-4 mb-2">

                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fas fa-search"></i></span>
                            <input type="text" name="keyword" class="form-control" value="{{ request('keyword') }}"
                                placeholder="Tìm theo tên chi nhánh...">
                        </div>

                    </div>

                    <div class="col-md-3 mb-2">
                        <select name="status" class="form-select">
                            <option value="">Tất cả trạng thái</option>
                            <option value="hoat_dong" {{ request('status') == 'hoat_dong' ? 'selected' : '' }}>Hoạt động
                            </option>
                            <option value="tam_dung" {{ request('status') == 'tam_dung' ? 'selected' : '' }}>Tạm dừng
                            </option>
                            <option value="dong_cua" {{ request('status') == 'dong_cua' ? 'selected' : '' }}>Đóng cửa
                            </option>
                        </select>
                    </div>

                    <div class="col-md-2 mb-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-filter me-1"></i> Lọc
                        </button>
                    </div>

                </form>

                <div class="table-responsive">

                    <table class="table table-hover table-bordered align-middle">

                        <thead class="table-dark">
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
                                <tr>
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

                                            <!-- Modal xác nhận hủy -->
                                            <div class="modal fade" id="cancelInviteModal{{ $chiNhanh->id }}"
                                                tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered modal-sm">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Thông tin lời mời</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Đóng"></button>
                                                        </div>
                                                        <div class="modal-body text-center">
                                                            <p><strong>Email:</strong><br>{{ $pendingEmails[$chiNhanh->id] ?? 'Không rõ' }}
                                                            </p>
                                                        </div>
                                                        <div class="modal-footer justify-content-between">
                                                            <button class="btn btn-secondary btn-sm"
                                                                data-bs-dismiss="modal">Đóng</button>
                                                            <form id="cancel-form-{{ $chiNhanh->id }}"
                                                                action="{{ route('admin.invite.cancel') }}" method="POST"
                                                                onsubmit="return confirmCancelInvite(this, '{{ $pendingEmails[$chiNhanh->id] ?? '' }}')">
                                                                @csrf
                                                                <input type="hidden" name="chi_nhanh_id"
                                                                    value="{{ $chiNhanh->id }}">
                                                                <input type="hidden" name="loai_quan_ly" value="1">
                                                                <button type="submit" class="btn btn-danger btn-sm">
                                                                    <i class="fas fa-times-circle me-1"></i> Hủy lời mời
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
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

                                            <!-- Modal nhập email -->
                                            <div class="modal fade" id="inviteModal{{ $chiNhanh->id }}" tabindex="-1"
                                                aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <form method="POST" action="{{ route('invite.send') }}">
                                                        @csrf
                                                        <input type="hidden" name="loai_quan_ly" value="1">
                                                        <input type="hidden" name="chi_nhanh_id"
                                                            value="{{ $chiNhanh->id }}">

                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Phân công quản lý chi nhánh</h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Đóng"></button>
                                                            </div>

                                                            <div class="modal-body">
                                                                <label>Email người quản lý</label>
                                                                <input type="email" name="email" class="form-control"
                                                                    required>
                                                            </div>

                                                            <div class="modal-footer">
                                                                <button type="submit" class="btn btn-primary">Gửi lời
                                                                    mời</button>
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Hủy</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        {{-- @elseif($chiNhanh->quanLy)
                                            <a href="{{ route('admin.users.show', $chiNhanh->quan_ly_id) }}"
                                                class="btn btn-sm btn-outline-warning" title="Xem thông tin quản lý">
                                                <i class="fa-solid fa-user" style="color: #FFD43B;"></i>
                                            </a> --}}
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
                        <small class="text-muted">Hiển thị {{ $chiNhanhs->count() }} trong tổng số
                            {{ $chiNhanhs->total() }}
                            chi nhánh</small>
                    </div>
                    <div>
                        {{ $chiNhanhs->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(
                document.querySelectorAll('[data-bs-toggle="tooltip"]')
            );
            tooltipTriggerList.forEach(function(el) {
                new bootstrap.Tooltip(el);
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Kích hoạt tooltip cho các button nếu cần
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.forEach(function(el) {
                new bootstrap.Tooltip(el);
            });
        });

        function toggleCancelBtn(id) {
            const form = document.getElementById('cancel-form-' + id);
            form.style.display = (form.style.display === 'none') ? 'inline-block' : 'none';
        }

        function confirmCancelInvite(form, email) {
            return confirm(`Bạn có chắc chắn muốn hủy lời mời đã gửi đến ${email}?`);
        }
    </script>
@endsection
