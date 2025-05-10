@extends('layouts.admin')

@section('title', 'Quản lý Thể loại phim')
@section('page-title', 'Chi tiết thể loại phim')
@section('breadcrumb', 'Chi tiết thể loại phim')
@section('styles')
<style>
    .card {
        border-radius: 10px;
    }
    .table th, .table td {
        vertical-align: middle;
    }
    .badge {
        font-size: 0.9em;
        padding: 0.5em 1em;
    }
    .btn {
        border-radius: 8px;
    }
    .table-dark {
        background-color: #343a40;
    }
</style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Thông tin thể loại phim</h5>
                <div class="btn-group gap-2">
                    <a href="{{ route('admin.the-loai-phim.edit', $theLoaiPhim->id) }}"
                       class="btn btn-light btn-sm" title="Chỉnh sửa">
                        <i class="fas fa-edit me-1"></i> Chỉnh sửa
                    </a>
                    <a href="{{ route('admin.the-loai-phim.index') }}"
                       class="btn btn-outline-light btn-sm" title="Quay lại">
                        <i class="fas fa-arrow-left me-1"></i> Quay lại
                    </a>
                </div>
            </div>
            <div class="card-body p-4">
                <!-- Thông tin chi tiết -->
                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <div class="d-flex">
                            <div class="fw-semibold text-muted" style="width: 150px;">ID:</div>
                            <div>{{ $theLoaiPhim->id }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex">
                            <div class="fw-semibold text-muted" style="width: 150px;">Tên thể loại:</div>
                            <div>{{ $theLoaiPhim->ten_the_loai }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex">
                            <div class="fw-semibold text-muted" style="width: 150px;">Trạng thái:</div>
                            <div>
                                <span class="badge rounded-pill {{ $theLoaiPhim->trang_thai === 'hoạt động' ? 'bg-success' : 'bg-danger' }}">
                                    {{ ucfirst($theLoaiPhim->trang_thai) }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex">
                            <div class="fw-semibold text-muted" style="width: 150px;">Ngày tạo:</div>
                            <div>{{ $theLoaiPhim->create_at->format('d/m/Y H:i:s') }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex">
                            <div class="fw-semibold text-muted" style="width: 150px;">Cập nhật lần cuối:</div>
                            <div>{{ $theLoaiPhim->updated_at ? $theLoaiPhim->updated_at->format('d/m/Y H:i') : 'Chưa cập nhật' }}</div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="d-flex">
                            <div class="fw-semibold text-muted" style="width: 150px;">Mô tả:</div>
                            <div>{{ $theLoaiPhim->mo_ta ?? 'Không có mô tả' }}</div>
                        </div>
                    </div>
                </div>

                <!-- Danh sách phim -->
                <div class="mt-5">
                    <h5 class="fw-bold mb-3">Danh sách phim thuộc thể loại này</h5>
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th scope="col" class="text-center" style="width: 5%">#</th>
                                    <th scope="col">Tên phim</th>
                                    <th scope="col" class="text-center" style="width: 15%">Trạng thái</th>
                                    <th scope="col" class="text-center" style="width: 10%">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($theLoaiPhim->phims as $index => $phim)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td>{{ $phim->ten_phim }}</td>
                                        <td class="text-center">
                                            <span class="badge rounded-pill {{ $phim->trang_thai === 'đang chiếu' ? 'bg-success' : ($phim->trang_thai === 'sắp chiếu' ? 'bg-warning' : 'bg-secondary') }}">
                                                {{ ucfirst($phim->trang_thai) }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.phim.show', $phim->id) }}"
                                               class="btn btn-sm btn-outline-info" title="Xem chi tiết">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-3">
                                            <i class="fas fa-folder-open me-1"></i> Không có phim nào thuộc thể loại này
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection