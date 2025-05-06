@extends('layouts.admin')

@section('title', 'Quản lý banner')
@section('page-title', 'Quản lý banner')
@section('breadcrumb', 'Danh sách banner')

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

        .text-break {
            word-break: break-word;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Danh sách banner</h5>
                <a href="{{ route('admin.banners.create') }}" class="btn btn-light btn-sm">
                    <i class="fas fa-plus me-1"></i> Thêm banner
                </a>
            </div>

            <div class="card-body p-4">
                <form method="GET" action="{{ route('admin.banners.index') }}" class="row mb-4">
                    <div class="col-md-4 mb-2">
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fas fa-search"></i></span>
                            <input type="text" name="keyword" class="form-control" value="{{ request('keyword') }}"
                                placeholder="Tìm theo vị trí hoặc đường dẫn...">
                        </div>
                    </div>
                    <div class="col-md-3 mb-2">
                        <select name="trang_thai" class="form-select">
                            <option value="">Tất cả trạng thái</option>
                            <option value="1" {{ request('trang_thai') == '1' ? 'selected' : '' }}>Hiển thị</option>
                            <option value="0" {{ request('trang_thai') == '0' ? 'selected' : '' }}>Ẩn</option>
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
                                <th class="text-center" style="width: 5%">#</th>
                                <th>Hình ảnh</th>
                                <th class="text-center">Trạng thái</th>
                                <th class="text-center" style="width: 15%">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($banners as $index => $banner)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-center">
                                        @if ($banner->hinh_anh)
                                            <img src="{{ asset('storage/' . $banner->hinh_anh) }}" alt="Banner"
                                                style="width: 100px;">
                                        @else
                                            <span class="text-muted">Chưa có</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($banner->trang_thai == 1)
                                            <span class="badge bg-success">Hiển thị</span>
                                        @else
                                            <span class="badge bg-secondary">Ẩn</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="{{ route('admin.banners.edit', $banner->id) }}"
                                                class="btn btn-sm btn-outline-primary" title="Chỉnh sửa">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.banners.destroy', $banner->id) }}" method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('Bạn có chắc chắn muốn xóa banner này?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-3">
                                        <i class="fas fa-folder-open me-1"></i> Không có dữ liệu
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div>
                        <small class="text-muted">
                            Hiển thị {{ $banners->count() }} trong tổng số {{ $banners->total() }} banner
                        </small>
                    </div>
                    <div>
                        {{ $banners->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection