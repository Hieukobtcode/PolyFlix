@extends('layouts.admin')

@section('content')
<div class="body flex-grow-1">
    <div class="container-lg px-4">
        <div class="row mb-4">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <strong>Thống kê liên hệ</strong>
                            <div>
                                <a href="{{ route('admin.lien-he.index') }}" class="btn btn-outline-primary btn-sm">
                                    <svg class="icon me-2">
                                        <use xlink:href="{{ asset('dist/vendors/@coreui/icons/svg/free.svg#cil-list') }}"></use>
                                    </svg>Danh sách liên hệ
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Thống kê tổng quan -->
        <div class="row mb-4">
            <div class="col-sm-6 col-xl-3">
                <div class="card mb-4 text-white bg-primary">
                    <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                        <div>
                            <div class="fs-4 fw-semibold">{{ $stats['total'] }} <span class="fs-6 fw-normal"></span></div>
                            <div>Tổng số liên hệ</div>
                        </div>
                        <div class="dropdown">
                            <svg class="icon icon-xl">
                                <use xlink:href="{{ asset('dist/vendors/@coreui/icons/svg/free.svg#cil-address-book') }}"></use>
                            </svg>
                        </div>
                    </div>
                    <div class="c-chart-wrapper mt-3 mx-3" style="height:70px;">
                        <a href="{{ route('admin.lien-he.index') }}" class="small-box-footer text-white">
                            Xem chi tiết <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-xl-3">
                <div class="card mb-4 text-white bg-warning">
                    <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                        <div>
                            <div class="fs-4 fw-semibold">{{ $stats['pending'] }} <span class="fs-6 fw-normal"></span></div>
                            <div>Chưa xử lý</div>
                        </div>
                        <div class="dropdown">
                            <svg class="icon icon-xl">
                                <use xlink:href="{{ asset('dist/vendors/@coreui/icons/svg/free.svg#cil-clock') }}"></use>
                            </svg>
                        </div>
                    </div>
                    <div class="c-chart-wrapper mt-3 mx-3" style="height:70px;">
                        <a href="{{ route('admin.lien-he.index', ['status' => 0]) }}" class="small-box-footer text-white">
                            Xem chi tiết <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-xl-3">
                <div class="card mb-4 text-white bg-success">
                    <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                        <div>
                            <div class="fs-4 fw-semibold">{{ $stats['completed'] }} <span class="fs-6 fw-normal"></span></div>
                            <div>Đã xử lý</div>
                        </div>
                        <div class="dropdown">
                            <svg class="icon icon-xl">
                                <use xlink:href="{{ asset('dist/vendors/@coreui/icons/svg/free.svg#cil-check-circle') }}"></use>
                            </svg>
                        </div>
                    </div>
                    <div class="c-chart-wrapper mt-3 mx-3" style="height:70px;">
                        <a href="{{ route('admin.lien-he.index', ['status' => 1]) }}" class="small-box-footer text-white">
                            Xem chi tiết <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-xl-3">
                <div class="card mb-4 text-white bg-danger">
                    <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                        <div>
                            <div class="fs-4 fw-semibold">{{ $stats['high_priority'] }} <span class="fs-6 fw-normal"></span></div>
                            <div>Ưu tiên cao</div>
                        </div>
                        <div class="dropdown">
                            <svg class="icon icon-xl">
                                <use xlink:href="{{ asset('dist/vendors/@coreui/icons/svg/free.svg#cil-flag-alt') }}"></use>
                            </svg>
                        </div>
                    </div>
                    <div class="c-chart-wrapper mt-3 mx-3" style="height:70px;">
                        <a href="{{ route('admin.lien-he.index', ['priority' => 'cao']) }}" class="small-box-footer text-white">
                            Xem chi tiết <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Liên hệ mới nhất -->
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <strong>Liên hệ mới nhất</strong>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>Tên</th>
                                        <th>Email</th>
                                        <th>Trạng thái</th>
                                        <th>Ngày tạo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($latestContacts as $lienHe)
                                        <tr>
                                            <td>
                                                <a href="{{ route('admin.lien-he.show', $lienHe->id) }}">
                                                    {{ $lienHe->ten }}
                                                </a>
                                            </td>
                                            <td>{{ $lienHe->email }}</td>
                                            <td>
                                                @if($lienHe->trang_thai)
                                                    <span class="badge bg-success">Đã xử lý</span>
                                                @else
                                                    <span class="badge bg-warning">Chưa xử lý</span>
                                                @endif
                                            </td>
                                            <td>{{ $lienHe->formatted_create_at }}</td>
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

            <!-- Liên hệ ưu tiên cao chưa xử lý -->
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <strong>Liên hệ ưu tiên cao chưa xử lý</strong>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>Tên</th>
                                        <th>Email</th>
                                        <th>Ngày tạo</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($highPriorityContacts as $lienHe)
                                        <tr>
                                            <td>
                                                <a href="{{ route('admin.lien-he.show', $lienHe->id) }}">
                                                    {{ $lienHe->ten }}
                                                </a>
                                            </td>
                                            <td>{{ $lienHe->email }}</td>
                                            <td>{{ $lienHe->formatted_create_at }}</td>
                                            <td>
                                                <form action="{{ route('admin.lien-he.update', $lienHe->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="btn btn-sm btn-{{ $lienHe->trang_thai ? 'warning' : 'success' }}">
                                                        <svg class="icon">
                                                            <use xlink:href="{{ asset('dist/vendors/@coreui/icons/svg/free.svg#cil-check-circle') }}"></use>
                                                        </svg>
                                                    </button>
                                                    <input type="hidden" name="trang_thai" value="{{ $lienHe->trang_thai ? '0' : '1' }}">
                                                    <input type="hidden" name="ghi_chu_noi_bo" value="{{ $lienHe->ghi_chu_noi_bo }}">
                                                </form>
                                            </td>
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
        </div>

        <div class="row">
            <!-- Thống kê theo phân loại -->
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <strong>Thống kê theo phân loại</strong>
                    </div>
                    <div class="card-body">
                        @if($categoryStats->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th>Phân loại</th>
                                            <th>Số lượng</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($categoryStats as $category)
                                            <tr>
                                                <td>{{ $category->phan_loai }}</td>
                                                <td>{{ $category->total }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info">
                                Chưa có dữ liệu phân loại
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Thống kê theo nguồn gốc -->
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <strong>Thống kê theo nguồn gốc</strong>
                    </div>
                    <div class="card-body">
                        @if($sourceStats->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th>Nguồn gốc</th>
                                            <th>Số lượng</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($sourceStats as $source)
                                            <tr>
                                                <td>{{ $source->nguon_goc }}</td>
                                                <td>{{ $source->total }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info">
                                Chưa có dữ liệu nguồn gốc
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Thống kê theo thời gian -->
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <strong>Thống kê theo thời gian (7 ngày gần đây)</strong>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>Ngày</th>
                                        <th>Số lượng liên hệ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($dateStats as $date => $count)
                                        <tr>
                                            <td>{{ $date }}</td>
                                            <td>{{ $count }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
