@extends('layouts.admin')

@section('title', 'Quản lý Chi Nhánh')
@section('page-title', 'Quản lý Chi Nhánh')

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
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Danh sách Chi Nhánh</h5>

                <a href="{{ route('admin.chi-nhanh.create') }}" class="btn btn-light btn-sm">
                    <i class="fas fa-plus me-1"></i> Thêm chi nhánh 
                </a>
                
            </div>
            <div class="card-body p-4">
                <form method="GET" action="{{ route('admin.chi-nhanh.index') }}" class="row mb-4">
                    <div class="col-md-4 mb-2">
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fas fa-search"></i></span>
                            <input type="text" name="keyword" class="form-control" value="{{ request('keyword') }}" placeholder="Tìm theo tên chi nhánh...">
                        </div>
                    </div>
                    <div class="col-md-3 mb-2">
                        <select name="status" class="form-select">
                            <option value="">Tất cả trạng thái</option>
                            <option value="hoat_dong" {{ request('status') == 'hoat_dong' ? 'selected' : '' }}>Hoạt động</option>
                            <option value="tam_dung" {{ request('status') == 'tam_dung' ? 'selected' : '' }}>Tạm dừng</option>
                            <option value="dong_cua" {{ request('status') == 'dong_cua' ? 'selected' : '' }}>Đóng cửa</option>
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
                                <th scope="col" class="text-center" style="width: 15%">Thao Tác</th>
                            </tr>
                        </thead>
                        <tbody id="chiNhanhTable">
                            @forelse($chiNhanhs as $index => $chiNhanh)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>{{ $chiNhanh->ten_chi_nhanh }}</td>
                                    <td>{{ $chiNhanh->dia_chi }}</td>
                                    <td class="text-center">{{ $chiNhanh->quan_ly_id }}</td>
                                    <td class="text-center">{{ \Carbon\Carbon::parse($chiNhanh->created_at)->format('d/m/Y H:i') }}</td>
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
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.chi-nhanh.edit', $chiNhanh->id) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.chi-nhanh.destroy', $chiNhanh->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa chi nhánh này?')">
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
                        <small class="text-muted">Hiển thị {{ $chiNhanhs->count() }} trong tổng số {{ $chiNhanhs->total() }} chi nhánh</small>
                    </div>
                    <div>
                        {{ $chiNhanhs->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
