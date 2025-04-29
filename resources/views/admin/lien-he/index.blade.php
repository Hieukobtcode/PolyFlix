@extends('layouts.admin')

@section('content')
<div class="body flex-grow-1">
    <div class="container-lg px-4">
        <!-- Thống kê tổng quan -->
        <div class="row mb-4">
            <div class="col-sm-6 col-xl-3">
                <div class="card text-white bg-primary">
                    <div class="card-body">
                        <div class="fs-4 fw-semibold">{{ $stats['total'] }}</div>
                        <div>Tổng số liên hệ</div>
                        <div class="progress progress-white progress-thin my-2">
                            <div class="progress-bar" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <small class="text-white">Tất cả liên hệ trong hệ thống</small>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card text-white bg-warning">
                    <div class="card-body">
                        <div class="fs-4 fw-semibold">{{ $stats['pending'] }}</div>
                        <div>Chưa xử lý</div>
                        <div class="progress progress-white progress-thin my-2">
                            <div class="progress-bar" role="progressbar" style="width: {{ $stats['total'] > 0 ? ($stats['pending'] / $stats['total'] * 100) : 0 }}%" aria-valuenow="{{ $stats['total'] > 0 ? ($stats['pending'] / $stats['total'] * 100) : 0 }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <small class="text-white">Liên hệ đang chờ xử lý</small>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <div class="fs-4 fw-semibold">{{ $stats['completed'] }}</div>
                        <div>Đã xử lý</div>
                        <div class="progress progress-white progress-thin my-2">
                            <div class="progress-bar" role="progressbar" style="width: {{ $stats['total'] > 0 ? ($stats['completed'] / $stats['total'] * 100) : 0 }}%" aria-valuenow="{{ $stats['total'] > 0 ? ($stats['completed'] / $stats['total'] * 100) : 0 }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <small class="text-white">Liên hệ đã được xử lý</small>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card text-white bg-danger">
                    <div class="card-body">
                        <div class="fs-4 fw-semibold">{{ $stats['high_priority'] }}</div>
                        <div>Ưu tiên cao</div>
                        <div class="progress progress-white progress-thin my-2">
                            <div class="progress-bar" role="progressbar" style="width: {{ $stats['total'] > 0 ? ($stats['high_priority'] / $stats['total'] * 100) : 0 }}%" aria-valuenow="{{ $stats['total'] > 0 ? ($stats['high_priority'] / $stats['total'] * 100) : 0 }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <small class="text-white">Liên hệ cần ưu tiên xử lý</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <strong>Quản lý liên hệ</strong>
                    <div>
                        <!-- Nút xuất CSV đã bị xóa -->
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Bộ lọc và tìm kiếm -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header bg-light">
                                <strong>Tìm kiếm và lọc</strong>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('admin.lien-he.index') }}" method="GET" class="row g-3">
                                    <div class="col-md-4">
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <svg class="icon">
                                                    <use xlink:href="{{ asset('dist/vendors/@coreui/icons/svg/free.svg#cil-search') }}"></use>
                                                </svg>
                                            </span>
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
                                            <option value="">-- Mức độ ưu tiên --</option>
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
                                        <button type="submit" class="btn btn-primary w-100">
                                            <svg class="icon me-2">
                                                <use xlink:href="{{ asset('dist/vendors/@coreui/icons/svg/free.svg#cil-filter') }}"></use>
                                            </svg>Lọc
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>



                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead>
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
                                        <a href="{{ route('admin.lien-he.show', $lienHe->id) }}">
                                            {{ $lienHe->ten }}
                                        </a>
                                    </td>
                                    <td>{{ $lienHe->email }}</td>
                                    <td>{{ $lienHe->so_dien_thoai }}</td>
                                    <td>
                                        @if($lienHe->trang_thai)
                                            <span class="badge bg-success">Đã xử lý</span>
                                        @else
                                            <span class="badge bg-warning">Chưa xử lý</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge {{ $lienHe->muc_do_uu_tien == 'cao' ? 'bg-danger' : ($lienHe->muc_do_uu_tien == 'thap' ? 'bg-info' : 'bg-warning') }}">
                                            {{ $lienHe->muc_do_uu_tien == 'cao' ? 'Cao' : ($lienHe->muc_do_uu_tien == 'thap' ? 'Thấp' : 'Bình thường') }}
                                        </span>
                                    </td>
                                    <td>{{ $lienHe->formatted_create_at }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-transparent p-0" type="button" data-coreui-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <svg class="icon">
                                                    <use xlink:href="{{ asset('dist/vendors/@coreui/icons/svg/free.svg#cil-options') }}"></use>
                                                </svg>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item" href="{{ route('admin.lien-he.show', $lienHe->id) }}">
                                                    <svg class="icon me-2">
                                                        <use xlink:href="{{ asset('dist/vendors/@coreui/icons/svg/free.svg#cil-info') }}"></use>
                                                    </svg>Chi tiết
                                                </a>
                                                <form action="{{ route('admin.lien-he.update', $lienHe->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="dropdown-item {{ $lienHe->trang_thai ? 'text-warning' : 'text-success' }}">
                                                        <svg class="icon me-2">
                                                            <use xlink:href="{{ asset('dist/vendors/@coreui/icons/svg/free.svg#cil-check-circle') }}"></use>
                                                        </svg>{{ $lienHe->trang_thai ? 'Đánh dấu chưa xử lý' : 'Đánh dấu đã xử lý' }}
                                                        <input type="hidden" name="trang_thai" value="{{ $lienHe->trang_thai ? '0' : '1' }}">
                                                        <input type="hidden" name="ghi_chu_noi_bo" value="{{ $lienHe->ghi_chu_noi_bo }}">
                                                    </button>
                                                </form>
                                                <div class="dropdown-divider"></div>
                                                <form action="{{ route('admin.lien-he.destroy', $lienHe->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa liên hệ này?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger">
                                                        <svg class="icon me-2">
                                                            <use xlink:href="{{ asset('dist/vendors/@coreui/icons/svg/free.svg#cil-trash') }}"></use>
                                                        </svg>Xóa
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">Không có dữ liệu</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $lienHes->links() }}
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
