@extends('layouts.admin')

@section('title', 'Quản lý Vai trò')
@section('page-title', 'Chi tiết vai trò')
@section('breadcrumb', 'Chi tiết vai trò')
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

        .btn {
            border-radius: 8px;
        }

        .table-dark {
            background-color: #343a40;
        }

        .permission-badge {
            background-color: #e9ecef;
            border-radius: 5rem;
            display: inline-block;
            padding: 0.4em 1em;
            margin: 0.3em 0.4em 0.3em 0;
            font-size: 0.85rem;
            font-weight: 500;
            color: #495057;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Thông tin vai trò</h5>
                <div class="btn-group gap-2">
                    <a href="{{ route('admin.vai-tro.edit', $vaiTro->id) }}" class="btn btn-light btn-sm" title="Chỉnh sửa">
                        <i class="fas fa-edit me-1"></i> Chỉnh sửa
                    </a>
                    <a href="{{ route('admin.vai-tro.index') }}" class="btn btn-outline-light btn-sm" title="Quay lại">
                        <i class="fas fa-arrow-left me-1"></i> Quay lại
                    </a>
                </div>
            </div>
            <div class="card-body p-4">
                <!-- Thông tin chi tiết -->
                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <div class="d-flex">
                            <div class="fw-semibold text-muted" style="width: 150px;">ID:</div>
                            <div>{{ $vaiTro->id }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex">
                            <div class="fw-semibold text-muted" style="width: 150px;">Tên vai trò:</div>
                            <div>{{ $vaiTro->ten }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex">
                            <div class="fw-semibold text-muted" style="width: 150px;">Ngày tạo:</div>
                            <div>
                                {{ $vaiTro->created_at ? $vaiTro->created_at->format('d/m/Y H:i:s') : 'Chưa có dữ liệu' }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex">
                            <div class="fw-semibold text-muted" style="width: 150px;">Cập nhật lần cuối:</div>
                            <div>
                                {{ $vaiTro->updated_at ? $vaiTro->updated_at->format('d/m/Y H:i') : 'Chưa cập nhật' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Danh sách phân quyền -->
            @if ($vaiTro->phanQuyens->count() > 0)
                @php
                    $prefixMap = [
                        'admin.phim' => 'Quản lý phim',
                        'admin.users' => 'Quản lý người dùng',
                        'admin.dat-ve' => 'Quản lý vé',
                        'admin.vai-tro' => 'Quản lý vai trò',
                        'admin.rap' => 'Quản lý rạp',
                        'admin.chi-nhanh' => 'Quản lý chi nhánh',
                    ];

                    $prefixOrder = [
                        'admin.phim',
                        'admin.users',
                        'admin.dat-ve',
                        'admin.rap',
                        'admin.chi-nhanh',
                        'admin.vai-tro',
                    ];

                    $groupedPermissions = $vaiTro->phanQuyens
                        ->sortBy('slug')
                        ->groupBy(function ($item) {
                            return explode('.', $item->slug)[0] . '.' . explode('.', $item->slug)[1];
                        })
                        ->sortBy(function ($_, $key) use ($prefixOrder) {
                            $index = array_search($key, $prefixOrder);
                            return $index !== false ? $index : 999;
                        });
                @endphp

                <div class="row">
                    @foreach ($groupedPermissions as $prefix => $permissions)
                        <div class="col-md-6 mb-4">
                            <div class="p-3 border rounded bg-light h-100">
                                <h6 class="fw-bold text-uppercase text-primary mb-3">
                                    {{ $prefixMap[$prefix] ?? ucfirst(str_replace('.', ' ', $prefix)) }}
                                </h6>
                                <div class="d-flex flex-wrap">
                                    @foreach ($permissions as $phanQuyen)
                                        <span class="permission-badge">
                                            <i class="fas fa-shield-alt me-1 text-primary"></i>
                                            {{ $phanQuyen->ten }}
                                            <small class="text-muted">({{ $phanQuyen->slug }})</small>
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-muted"><i class="fas fa-ban me-1"></i> Chưa có phân quyền nào được gán.</p>
            @endif


            <!-- Danh sách người dùng -->
            <div class="mt-5">
                <h5 class="fw-bold mb-3">Danh sách người dùng có vai trò này</h5>
                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th class="text-center" style="width: 5%">#</th>
                                <th>Họ tên</th>
                                <th>Email</th>
                                <th class="text-center" style="width: 15%">Trạng thái</th>
                                <th class="text-center" style="width: 10%">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($vaiTro->users as $index => $nguoiDung)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>{{ $nguoiDung->name }}</td>
                                    <td>{{ $nguoiDung->email }}</td>
                                    <td class="text-center">
                                        <span
                                            class="badge rounded-pill {{ $nguoiDung->trang_thai === 'hoạt động' ? 'bg-success' : 'bg-secondary' }}">
                                            {{ ucfirst($nguoiDung->trang_thai) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.users.show', $nguoiDung->id) }}"
                                            class="btn btn-sm btn-outline-info" title="Xem chi tiết">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-3">
                                        <i class="fas fa-users-slash me-1"></i> Không có người dùng nào thuộc vai trò này
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
