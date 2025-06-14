@extends('admin.layouts.app')

@section('title', 'Thống kê phim')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="fw-bold text-primary">
                    <i class="fas fa-film me-2"></i>Thống kê phim
                </h2>
                <a href="{{ route('admin.thong-ke.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Quay lại tổng quan
                </a>
            </div>
        </div>
    </div>

    <!-- Bộ lọc -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.thong-ke.phim') }}" class="row">
                        <div class="col-md-3 mb-2">
                            <label for="start_date" class="form-label">Từ ngày</label>
                            <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
                        </div>
                        <div class="col-md-3 mb-2">
                            <label for="end_date" class="form-label">Đến ngày</label>
                            <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
                        </div>
                        <div class="col-md-3 mb-2">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-filter me-1"></i>Lọc
                                </button>
                                <a href="{{ route('admin.thong-ke.phim') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-refresh me-1"></i>Reset
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Thống kê tổng hợp -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <div class="h2 mb-0 fw-bold text-primary">{{ number_format($thongKe['tong_phim']) }}</div>
                    <div class="text-muted">Tổng số phim</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <div class="h2 mb-0 fw-bold text-success">{{ number_format($thongKe['dang_chieu']) }}</div>
                    <div class="text-muted">Đang chiếu</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <div class="h2 mb-0 fw-bold text-warning">{{ number_format($thongKe['sap_chieu']) }}</div>
                    <div class="text-muted">Sắp chiếu</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <div class="h2 mb-0 fw-bold text-secondary">{{ number_format($thongKe['ngung_chieu']) }}</div>
                    <div class="text-muted">Ngừng chiếu</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Danh sách phim -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0 fw-bold">Danh sách phim chi tiết</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>STT</th>
                                    <th>Tên phim</th>
                                    <th>Thể loại</th>
                                    <th>Trạng thái</th>
                                    <th>Số suất chiếu</th>
                                    <th>Ngày tạo</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($phims as $index => $phim)
                                <tr>
                                    <td>{{ $phims->firstItem() + $index }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($phim->hinh_anh)
                                            <img src="{{ asset('storage/' . $phim->hinh_anh) }}" alt="{{ $phim->tieu_de }}" 
                                                 class="rounded me-2" style="width: 40px; height: 60px; object-fit: cover;">
                                            @endif
                                            <div>
                                                <div class="fw-semibold">{{ $phim->tieu_de }}</div>
                                                <div class="small text-muted">{{ Str::limit($phim->mo_ta, 50) }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($phim->theLoais && $phim->theLoais->count() > 0)
                                            @foreach($phim->theLoais->take(2) as $theLoai)
                                                <span class="badge bg-light text-dark me-1">{{ $theLoai->ten }}</span>
                                            @endforeach
                                            @if($phim->theLoais->count() > 2)
                                                <span class="badge bg-secondary">+{{ $phim->theLoais->count() - 2 }}</span>
                                            @endif
                                        @else
                                            <span class="text-muted">Chưa phân loại</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $statusClass = match($phim->trang_thai) {
                                                'dang_chieu' => 'success',
                                                'sap_chieu' => 'warning',
                                                'ngung_chieu' => 'secondary',
                                                default => 'secondary'
                                            };
                                            $statusText = match($phim->trang_thai) {
                                                'dang_chieu' => 'Đang chiếu',
                                                'sap_chieu' => 'Sắp chiếu',
                                                'ngung_chieu' => 'Ngừng chiếu',
                                                default => ucfirst($phim->trang_thai)
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $statusClass }}">{{ $statusText }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-info">{{ $phim->suat_chieus_count ?? 0 }}</span>
                                    </td>
                                    <td>{{ $phim->created_at ? $phim->created_at->format('d/m/Y H:i') : '---' }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.phim.show', $phim->id) }}" class="btn btn-sm btn-outline-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.phim.edit', $phim->id) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="text-muted">Không có dữ liệu phim</div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($phims->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $phims->appends(request()->query())->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
