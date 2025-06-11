@extends('layouts.admin')

@section('title', 'Quản lý Suất chiếu')
@section('page-title', 'Danh sách Suất chiếu')
@section('breadcrumb', 'Danh sách Suất chiếu')

@section('content')
<div class="container">
    <h2 class="mb-4">Danh sách suất chiếu</h2>

    {{-- Filter form --}}
    <form method="GET" action="{{ route('admin.suat-chieu.index') }}" class="row g-3 mb-4">
        <div class="col-md-3">
            <select name="chi_nhanh_id" class="form-control">
                <option value="">-- Chọn chi nhánh --</option>
                @foreach($chiNhanhs ?? [] as $cn)
                    <option value="{{ $cn->id }}" {{ request('chi_nhanh_id') == $cn->id ? 'selected' : '' }}>{{ $cn->ten_chi_nhanh }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <select name="rap_phim_id" class="form-control">
                <option value="">-- Chọn rạp phim --</option>
                @foreach($rapPhims ?? [] as $rap)
                    <option value="{{ $rap->id }}" {{ request('rap_phim_id') == $rap->id ? 'selected' : '' }}>{{ $rap->ten_rap }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <input type="date" name="ngay_chieu" value="{{ request('ngay_chieu') }}" class="form-control">
        </div>

        <div class="col-md-3">
            <button type="submit" class="btn btn-primary">Lọc</button>
            <a href="{{ route('admin.suat-chieu.index') }}" class="btn btn-secondary">Reset</a>
        </div>
    </form>

    <a href="{{ route('admin.suat-chieu.create') }}" class="btn btn-success mb-3">+ Tạo suất chiếu mới</a>

    {{-- Danh sách --}}
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Phim</th>
                <th>Chi nhánh</th>
                <th>Rạp</th>
                <th>Phòng</th>
                <th>Ngày chiếu</th>
                <th>Giờ bắt đầu</th>
                <th>Giờ kết thúc</th>
                <th>Trạng thái</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @forelse($suatChieus as $suat)
                <tr>
                    <td>{{ $suat->id }}</td>
                    <td>{{ $suat->phim->ten_phim ?? '-' }}</td>
                    <td>{{ $suat->chiNhanh->ten_chi_nhanh ?? '-' }}</td>
                    <td>{{ $suat->rapPhim->ten_rap ?? '-' }}</td>
                    <td>{{ $suat->phongChieu->ten_phong ?? '-' }}</td>
                    <td>{{ $suat->ngay_chieu }}</td>
                    <td>{{ \Carbon\Carbon::parse($suat->bat_dau)->format('H:i') }}</td>
                    <td>{{ \Carbon\Carbon::parse($suat->ket_thuc)->format('H:i') }}</td>
                    <td>
                        <span class="badge bg-{{ $suat->trang_thai === 'hoat_dong' ? 'success' : 'warning' }}">
                            {{ ucfirst(str_replace('_', ' ', $suat->trang_thai)) }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admin.suat-chieu.show', $suat->id) }}" class="btn btn-info btn-sm">Xem</a>
                        <a href="{{ route('admin.suat-chieu.edit', $suat->id) }}" class="btn btn-warning btn-sm">Sửa</a>
                        <form method="POST" action="{{ route('admin.suat-chieu.destroy', $suat->id) }}" style="display:inline-block;" onsubmit="return confirm('Xác nhận xoá?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm">Xoá</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10">Không có suất chiếu nào.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
