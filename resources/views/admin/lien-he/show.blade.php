@extends('layouts.admin')

@section('title', 'Quản lý liên hệ')
@section('page-title', 'Chi tiết liên hệ')
@section('breadcrumb', 'Chi tiết liên hệ')

@section('content')
<div class="body flex-grow-1">
    <div class="container-lg px-4">
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <strong>Chi tiết liên hệ #{{ $lienHe->id }}</strong>
                    <div>
                        <a href="{{ route('admin.lien-he.index') }}" class="btn btn-outline-primary btn-sm me-2">
                            <svg class="icon me-2">
                                <use xlink:href="{{ asset('dist/vendors/@coreui/icons/svg/free.svg#cil-arrow-left') }}"></use>
                            </svg>Quay lại
                        </a>
                        <a href="{{ route('admin.lien-he.show', $lienHe->id) }}" class="btn btn-outline-info btn-sm">
                            <svg class="icon me-2">
                                <use xlink:href="{{ asset('dist/vendors/@coreui/icons/svg/free.svg#cil-info') }}"></use>
                            </svg>Chi tiết
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Thông tin liên hệ -->
                    <div class="col-md-8">
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <strong>Thông tin liên hệ</strong>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table table-striped">
                                            <tr>
                                                <th style="width: 150px">ID:</th>
                                                <td>{{ $lienHe->id }}</td>
                                            </tr>
                                            <tr>
                                                <th>Tên:</th>
                                                <td>{{ $lienHe->ten }}</td>
                                            </tr>
                                            <tr>
                                                <th>Email:</th>
                                                <td>{{ $lienHe->email }}</td>
                                            </tr>
                                            <tr>
                                                <th>Số điện thoại:</th>
                                                <td>{{ $lienHe->so_dien_thoai }}</td>
                                            </tr>
                                            <tr>
                                                <th>Trạng thái:</th>
                                                <td>
                                                    @if($lienHe->trang_thai)
                                                        <span class="badge bg-success">Đã xử lý</span>
                                                    @else
                                                        <span class="badge bg-warning">Chưa xử lý</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Đã phản hồi:</th>
                                                <td>
                                                    @if($lienHe->da_phan_hoi)
                                                        <span class="badge bg-success">Đã phản hồi</span>
                                                    @else
                                                        <span class="badge bg-secondary">Chưa phản hồi</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <table class="table table-striped">
                                            <tr>
                                                <th style="width: 150px">Mức độ ưu tiên:</th>
                                                <td>
                                                    <span class="badge {{ $lienHe->muc_do_uu_tien == 'cao' ? 'bg-danger' : ($lienHe->muc_do_uu_tien == 'thap' ? 'bg-info' : 'bg-warning') }}">
                                                        {{ $lienHe->muc_do_uu_tien == 'cao' ? 'Cao' : ($lienHe->muc_do_uu_tien == 'thap' ? 'Thấp' : 'Bình thường') }}
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Nguồn gốc:</th>
                                                <td>{{ $lienHe->nguon_goc ?? 'Không có' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Phân loại:</th>
                                                <td>{{ $lienHe->phan_loai ?? 'Không có' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Người phụ trách:</th>
                                                <td>{{ $lienHe->nguoi_phu_trach ?? 'Chưa phân công' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Ngày tạo:</th>
                                                <td>{{ $lienHe->formatted_create_at }}</td>
                                            </tr>
                                            <tr>
                                                <th>Cập nhật lần cuối:</th>
                                                <td>{{ $lienHe->formatted_update_at }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-12">
                                        <h6>Nội dung liên hệ:</h6>
                                        <div class="p-3 bg-light rounded">
                                            {{ $lienHe->noi_dung }}
                                        </div>
                                    </div>
                                </div>

                                @if($lienHe->ghi_chu_noi_bo)
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <h6>Ghi chú nội bộ:</h6>
                                        <div class="p-3 bg-light rounded">
                                            {{ $lienHe->ghi_chu_noi_bo }}
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Lịch sử hoạt động -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <strong>Lịch sử hoạt động</strong>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover table-striped">
                                        <thead>
                                            <tr>
                                                <th>Thời gian</th>
                                                <th>Hành động</th>
                                                <th>Mô tả</th>
                                                <th>Người thực hiện</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($activities as $activity)
                                                <tr>
                                                    <td>{{ $activity->formatted_created_at }}</td>
                                                    <td>
                                                        @switch($activity->hanh_dong)
                                                            @case('create')
                                                                <span class="badge bg-success">Tạo mới</span>
                                                                @break
                                                            @case('update')
                                                                <span class="badge bg-primary">Cập nhật</span>
                                                                @break
                                                            @case('delete')
                                                                <span class="badge bg-danger">Xóa</span>
                                                                @break
                                                            @case('add_note')
                                                                <span class="badge bg-info">Thêm ghi chú</span>
                                                                @break
                                                            @case('update_status')
                                                                <span class="badge bg-warning">Cập nhật trạng thái</span>
                                                                @break
                                                            @case('send_email')
                                                                <span class="badge bg-success">Gửi email</span>
                                                                @break
                                                            @default
                                                                <span class="badge bg-secondary">{{ $activity->hanh_dong }}</span>
                                                        @endswitch
                                                    </td>
                                                    <td>{{ $activity->mo_ta }}</td>
                                                    <td>{{ $activity->nguoi_thuc_hien }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center">Không có dữ liệu</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="col-md-4">
                        <!-- Thêm ghi chú -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <strong>Thêm ghi chú</strong>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('admin.lien-he.add-note', $lienHe->id) }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="noi_dung" class="form-label">Nội dung ghi chú</label>
                                        <textarea class="form-control" id="noi_dung" name="noi_dung" rows="4" required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100">
                                        <svg class="icon me-2">
                                            <use xlink:href="{{ asset('dist/vendors/@coreui/icons/svg/free.svg#cil-note-add') }}"></use>
                                        </svg>Thêm ghi chú
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Gửi email phản hồi -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <strong>Gửi email phản hồi</strong>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('admin.lien-he.send-email', $lienHe->id) }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="subject" class="form-label">Tiêu đề</label>
                                        <input type="text" class="form-control" id="subject" name="subject" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="message" class="form-label">Nội dung</label>
                                        <textarea class="form-control" id="message" name="message" rows="6" required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-success w-100">
                                        <svg class="icon me-2">
                                            <use xlink:href="{{ asset('dist/vendors/@coreui/icons/svg/free.svg#cil-send') }}"></use>
                                        </svg>Gửi email
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Danh sách ghi chú -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <strong>Ghi chú</strong>
                            </div>
                            <div class="card-body">
                                @forelse($notes as $note)
                                    <div class="note-item mb-3 p-3 border rounded">
                                        <div class="d-flex justify-content-between mb-2">
                                            <small class="text-muted">{{ $note->formatted_created_at }}</small>
                                            <small class="text-muted">{{ $note->nguoi_tao }}</small>
                                        </div>
                                        <p class="mb-0">{{ $note->noi_dung }}</p>
                                    </div>
                                @empty
                                    <p class="text-center text-muted">Chưa có ghi chú nào</p>
                                @endforelse
                            </div>
                        </div>

                        <!-- Thao tác -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <strong>Thao tác</strong>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <form action="{{ route('admin.lien-he.update', $lienHe->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-{{ $lienHe->trang_thai ? 'warning' : 'success' }} w-100">
                                            <svg class="icon me-2">
                                                <use xlink:href="{{ asset('dist/vendors/@coreui/icons/svg/free.svg#cil-check-circle') }}"></use>
                                            </svg>{{ $lienHe->trang_thai ? 'Đánh dấu chưa xử lý' : 'Đánh dấu đã xử lý' }}
                                        </button>
                                        <input type="hidden" name="trang_thai" value="{{ $lienHe->trang_thai ? '0' : '1' }}">
                                        <input type="hidden" name="ghi_chu_noi_bo" value="{{ $lienHe->ghi_chu_noi_bo }}">
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
