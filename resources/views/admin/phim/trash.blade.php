@extends('layouts.admin')

@section('title', 'Quản lý Phim')
@section('page-title', 'Danh sách phim đã xóa')
@section('breadcrumb', 'Danh sách phim đã xóa')

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

        .btn-group .btn {
            border-radius: 5px;
        }

        .pagination {
            justify-content: end;
        }

        .table-dark {
            background-color: #343a40;
        }

        .form-control {
            border-radius: 8px;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Danh sách phim đã xóa</h5>
                <a href="{{ route('admin.phim.index') }}" class="btn btn-light btn-sm" title="Quay lại danh sách phim">
                    <i class="fas fa-arrow-left me-1"></i> Quay lại
                </a>
            </div>
            <div class="card-body p-4">
                <!-- Thanh tìm kiếm -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="input-group">
                            <input type="text" class="form-control rounded" placeholder="Tìm kiếm phim đã xóa..."
                                id="searchInput">
                            <button class="btn btn-outline-secondary" type="button">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Bảng dữ liệu -->
                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th scope="col" class="text-center" style="width: 5%">#</th>
                                <th scope="col" class="text-center" style="width: 10%">ID</th>
                                <th scope="col">Tên phim</th>
                                <th scope="col" class="text-center" style="width: 15%">Trạng thái</th>
                                <th scope="col" class="text-center" style="width: 20%">Ngày xóa</th>
                                <th scope="col" class="text-center" style="width: 20%">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody id="trashedMovieTable">
                            @forelse($trashedPhims as $index => $phim)
                                                <tr>
                                                    <td class="text-center">{{ $index + 1 }}</td>
                                                    <td class="text-center">{{ $phim->id }}</td>
                                                    <td>{{ $phim->ten_phim }}</td>
                                                    <td class="text-center">
                                                        <span
                                                            class="badge rounded-pill {{
                                $phim->trang_thai === 'đang chiếu' ? 'bg-success' :
                                ($phim->trang_thai === 'sắp chiếu' ? 'bg-warning' :
                                    ($phim->trang_thai === 'đã kết thúc' ? 'bg-secondary' : 'bg-danger'))
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        }}">
                                                            {{ ucfirst($phim->trang_thai) }}
                                                        </span>
                                                    </td>
                                                    <td class="text-center">{{ $phim->deleted_at->format('d/m/Y H:i') }}</td>
                                                    <td class="text-center">
                                                        <div class="btn-group" role="group">
                                                            <form action="{{ route('admin.phim.restore', $phim->id) }}" method="POST"
                                                                class="d-inline">
                                                                @csrf
                                                                @method('PATCH')
                                                                <button type="submit" class="btn btn-sm btn-outline-success" title="Khôi phục">
                                                                    <i class="fas fa-undo"></i>
                                                                </button>
                                                            </form>
                                                            <form action="{{ route('admin.phim.force-delete', $phim->id) }}" method="POST"
                                                                class="d-inline"
                                                                onsubmit="return confirm('Bạn có chắc chắn muốn xóa vĩnh viễn phim này?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                                                    title="Xóa vĩnh viễn">
                                                                    <i class="fas fa-trash-alt"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-3">
                                        <i class="fas fa-folder-open me-1"></i> Không có phim nào trong thùng rác
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Phân trang -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div>
                        <small class="text-muted">Hiển thị {{ $trashedPhims->count() }} trong tổng số
                            {{ $trashedPhims->total() }} phim đã xóa</small>
                    </div>
                    <div>
                        {{ $trashedPhims->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        // Tìm kiếm phía client
        document.getElementById('searchInput').addEventListener('input', function () {
            let searchValue = this.value.toLowerCase();
            let rows = document.querySelectorAll('#trashedMovieTable tr');

            rows.forEach(row => {
                let name = row.cells[2].textContent.toLowerCase();
                row.style.display = name.includes(searchValue) ? '' : 'none';
            });
        });
    </script>
@endsection