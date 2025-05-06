@extends('layouts.admin')

@section('title', 'Quản lý liên hệ')
@section('page-title', 'Quản lý liên hệ')
@section('breadcrumb', 'Danh sách liên hệ')
@section('styles')
<style>
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

    .pagination {
        justify-content: end;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Thống kê tổng quan -->
    <div class="row mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card text-white bg-primary shadow-sm">
                <div class="card-body">
                    <div class="fs-4 fw-semibold">{{ $stats['total'] }}</div>
                    <div>Tổng số liên hệ</div>
                    <div class="progress progress-white progress-thin my-2">
                        <div class="progress-bar" role="progressbar" style="width: 100%"></div>
                    </div>
                    <small class="text-white">Tất cả liên hệ trong hệ thống</small>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card text-white bg-warning shadow-sm">
                <div class="card-body">
                    <div class="fs-4 fw-semibold">{{ $stats['pending'] }}</div>
                    <div>Chưa xử lý</div>
                    <div class="progress progress-white progress-thin my-2">
                        <div class="progress-bar" role="progressbar" style="width: {{ $stats['total'] > 0 ? ($stats['pending'] / $stats['total'] * 100) : 0 }}%"></div>
                    </div>
                    <small class="text-white">Liên hệ đang chờ xử lý</small>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card text-white bg-success shadow-sm">
                <div class="card-body">
                    <div class="fs-4 fw-semibold">{{ $stats['completed'] }}</div>
                    <div>Đã xử lý</div>
                    <div class="progress progress-white progress-thin my-2">
                        <div class="progress-bar" role="progressbar" style="width: {{ $stats['total'] > 0 ? ($stats['completed'] / $stats['total'] * 100) : 0 }}%"></div>
                    </div>
                    <small class="text-white">Liên hệ đã được xử lý</small>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card text-white bg-danger shadow-sm">
                <div class="card-body">
                    <div class="fs-4 fw-semibold">{{ $stats['high_priority'] }}</div>
                    <div>Ưu tiên cao</div>
                    <div class="progress progress-white progress-thin my-2">
                        <div class="progress-bar" role="progressbar" style="width: {{ $stats['total'] > 0 ? ($stats['high_priority'] / $stats['total'] * 100) : 0 }}%"></div>
                    </div>
                    <small class="text-white">Liên hệ cần ưu tiên xử lý</small>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
            <strong>Quản lý liên hệ</strong>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <!-- Bộ lọc -->
            <form action="{{ route('admin.lien-he.index') }}" method="GET" class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" class="form-control" placeholder="Tìm kiếm..." name="search" value="{{ $search ?? '' }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <select class="form-select" name="status">
                        <option value="">-- Trạng thái --</option>
                        <option value="1" {{ isset($status) && $status == '1' ? 'selected' : '' }}>Đã xử lý</option>
                        <option value="0" {{ isset($status) && $status == '0' ? 'selected' : '' }}>Chưa xử lý</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select" name="priority">
                        <option value="">-- Ưu tiên --</option>
                        <option value="cao" {{ isset($priority) && $priority == 'cao' ? 'selected' : '' }}>Cao</option>
                        <option value="binh_thuong" {{ isset($priority) && $priority == 'binh_thuong' ? 'selected' : '' }}>Bình thường</option>
                        <option value="thap" {{ isset($priority) && $priority == 'thap' ? 'selected' : '' }}>Thấp</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select" name="category">
                        <option value="">-- Phân loại --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ isset($category) && $category == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100"><i class="fas fa-filter me-1"></i> Lọc</button>
                </div>
            </form>

            <!-- Bảng dữ liệu -->
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Tên</th>
                            <th>Email</th>
                            <th>Số điện thoại</th>
                            <th>Trạng thái</th>
                            <th>Ưu tiên</th>
                            <th>Ngày tạo</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($lienHes as $lienHe)
                            <tr>
                                <td>{{ $lienHe->id }}</td>
                                <td>
                                    <a href="{{ route('admin.lien-he.show', $lienHe->id) }}">{{ $lienHe->ten }}</a>
                                </td>
                                <td>{{ $lienHe->email }}</td>
                                <td>{{ $lienHe->so_dien_thoai }}</td>
                                <td>
                                    <span class="badge {{ $lienHe->trang_thai ? 'bg-success' : 'bg-warning' }}">
                                        {{ $lienHe->trang_thai ? 'Đã xử lý' : 'Chưa xử lý' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ $lienHe->muc_do_uu_tien == 'cao' ? 'bg-danger' : ($lienHe->muc_do_uu_tien == 'thap' ? 'bg-info' : 'bg-secondary') }}">
                                        {{ $lienHe->muc_do_uu_tien == 'cao' ? 'Cao' : ($lienHe->muc_do_uu_tien == 'thap' ? 'Thấp' : 'Bình thường') }}
                                    </span>
                                </td>
                                <td>{{ $lienHe->formatted_create_at }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.lien-he.show', $lienHe->id) }}" class="btn btn-sm btn-outline-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <form action="{{ route('admin.lien-he.update', $lienHe->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="trang_thai" value="{{ $lienHe->trang_thai ? '0' : '1' }}">
                                            <input type="hidden" name="ghi_chu_noi_bo" value="{{ $lienHe->ghi_chu_noi_bo }}">
                                            <button type="submit" class="btn btn-sm btn-outline-warning">
                                                <i class="fas fa-check-circle"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.lien-he.destroy', $lienHe->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa liên hệ này?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-3">Không có dữ liệu</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-4">
                <div>
                    <small class="text-muted">Hiển thị {{ $lienHes->count() }} trong tổng số {{ $lienHes->total() }} liên hệ</small>
                </div>
                <div>
                    {{ $lienHes->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
