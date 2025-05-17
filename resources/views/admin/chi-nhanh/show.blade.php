@extends('layouts.admin')

@section('title', 'Chi tiết Chi nhánh')
@section('page-title', 'Chi tiết Chi nhánh')
@section('breadcrumb', 'Chi nhánh')

@section('styles')
    <style>
        .card { border-radius: 12px; }
        .detail-row { padding: 12px 0; border-bottom: 1px solid #ddd; }
        .detail-label { font-weight: bold; width: 200px; }
        .badge { font-size: 0.9em; }
        .back-btn { margin-top: 20px; }
        .table th, .table td { vertical-align: middle; }
    </style>
@endsection

@section('content')
<div class="container-fluid">
    {{-- Thông tin chi nhánh --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0 fw-bold"><i class="fas fa-building me-1"></i> Thông tin chi nhánh</h5>
        </div>
        <div class="card-body">
            <div class="row detail-row">
                <div class="col-md-3 detail-label">Tên chi nhánh:</div>
                <div class="col-md-9">{{ $chiNhanh->ten_chi_nhanh }}</div>
            </div>

            <div class="row detail-row">
                <div class="col-md-3 detail-label">Địa chỉ:</div>
                <div class="col-md-9">{{ $chiNhanh->dia_chi }}</div>
            </div>

            <div class="row detail-row">
                <div class="col-md-3 detail-label">Số điện thoại:</div>
                <div class="col-md-9">{{ $chiNhanh->so_dien_thoai }}</div>
            </div>

            <div class="row detail-row">
                <div class="col-md-3 detail-label">Email:</div>
                <div class="col-md-9">{{ $chiNhanh->email }}</div>
            </div>

            <div class="row detail-row">
                <div class="col-md-3 detail-label">Trạng thái:</div>
                <div class="col-md-9">
                    <span class="badge rounded-pill {{
                        $chiNhanh->trang_thai === 'đang hoạt động' ? 'bg-success' :
                        ($chiNhanh->trang_thai === 'tạm dừng' ? 'bg-warning' :
                        ($chiNhanh->trang_thai === 'ngừng hoạt động' ? 'bg-danger' : 'bg-dark'))
                    }}">
                        {{ ucfirst($chiNhanh->trang_thai) }}
                    </span>
                </div>
            </div>

            <div class="text-end back-btn">
                <a href="{{ route('admin.chi-nhanh.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Quay lại danh sách
                </a>
            </div>
        </div>
    </div>

    {{-- Danh sách rạp thuộc chi nhánh --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0 fw-bold"><i class="fas fa-film me-1"></i> Rạp thuộc chi nhánh</h5>
        </div>
        <div class="card-body">
            @if ($chiNhanh->RapPhim->isEmpty())
                <p class="text-muted mb-0"><i class="fas fa-info-circle me-1"></i> Không có rạp nào thuộc chi nhánh này.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th class="text-center" style="width: 5%">#</th>
                                <th>Tên rạp</th>
                                <th>Địa chỉ</th>
                                <th class="text-center" style="width: 15%">Trạng thái</th>
                                <th class="text-center" style="width: 15%">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($chiNhanh->RapPhim as $index => $rap)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>{{ $rap->ten_rap }}</td>
                                    <td>{{ $rap->dia_chi }}</td>
                                    <td class="text-center">
                                        <span class="badge rounded-pill {{
                                            $rap->trang_thai === 'đang hoạt động' ? 'bg-success' :
                                            ($rap->trang_thai === 'tạm dừng' ? 'bg-warning' :
                                            ($rap->trang_thai === 'ngừng hoạt động' ? 'bg-danger' : 'bg-dark'))
                                        }}">
                                            {{ ucfirst($rap->trang_thai) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.rap-phim.show', $rap->id) }}" class="btn btn-sm btn-outline-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.rap-phim.edit', $rap->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
