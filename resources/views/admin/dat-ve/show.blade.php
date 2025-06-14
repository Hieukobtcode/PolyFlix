@extends('layouts.admin')

@section('title', 'Chi tiết Đặt vé')
@section('page-title', 'Chi tiết Đặt vé')
@section('breadcrumb', 'Chi tiết Đặt vé')

@section('content')
<div class="container mt-5">
    <div class="card shadow-lg border-0 rounded-3">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">
                <i class="fas fa-ticket-alt me-2"></i>Chi tiết Đơn Đặt Vé #{{ $datVe->id }}
            </h4>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover mb-4">
                <tbody>
                    <tr>
                        <th class="bg-light" width="25%"><i class="fas fa-user me-1"></i> Người dùng</th>
                        <td>{{ $datVe->nguoiDung?->name ?? 'Không rõ' }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light"><i class="fas fa-film me-1"></i> Phim</th>
                        <td>{{ $datVe->phim->ten_phim}}</td>
                    </tr>
                    <tr>
                        <th class="bg-light"><i class="far fa-clock me-1"></i> Thời gian đặt</th>
                        <td>{{ \Carbon\Carbon::parse($datVe->thoi_gian_dat)->format('d/m/Y H:i') }}</td>
                    </tr>
                </tbody>
            </table>

            <div class="text-end">
                <a href="{{ route('admin.dat-ves.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Quay lại danh sách
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
