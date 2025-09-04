@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Thông tin vai trò</h5>
                <div class="btn-group gap-2">
                    <a href="{{ route('admin.vai-tro.edit', $vaiTro->id) }}" class="btn btn-light btn-sm">
                        <i class="ti ti-edit me-1"></i> Chỉnh sửa
                    </a>
                    <a href="{{ route('admin.vai-tro.index') }}" class="btn btn-outline-light btn-sm">
                        <i class="ti ti-arrow-left me-1"></i> Quay lại
                    </a>
                </div>
            </div>
            <div class="card-body p-4">
                <!-- Thông tin vai trò -->
                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <div class="d-flex">
                            <div class="text-muted fw-semibold me-2" style="width: 150px;">ID:</div>
                            <div>{{ $vaiTro->id }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex">
                            <div class="text-muted fw-semibold me-2" style="width: 150px;">Tên vai trò:</div>
                            <div>{{ $vaiTro->ten }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex">
                            <div class="text-muted fw-semibold me-2" style="width: 150px;">Ngày tạo:</div>
                            <div>{{ $vaiTro->created_at ? $vaiTro->created_at->format('d/m/Y H:i') : 'Chưa có' }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex">
                            <div class="text-muted fw-semibold me-2" style="width: 150px;">Cập nhật lần cuối:</div>
                            <div>{{ $vaiTro->updated_at ? $vaiTro->updated_at->format('d/m/Y H:i') : 'Chưa có' }}</div>
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
                            ->groupBy(fn($item) => implode('.', array_slice(explode('.', $item->slug), 0, 2)))
                            ->sortBy(fn($_, $key) => array_search($key, $prefixOrder) ?? 999);
                    @endphp

                    <div class="row g-4 mt-3">
                        @foreach ($groupedPermissions as $prefix => $permissions)
                            <div class="col-md-6">
                                <div class="border rounded p-3 bg-light h-100">
                                    <h6 class="fw-bold text-primary mb-3">
                                        {{ $prefixMap[$prefix] ?? ucfirst(str_replace('.', ' ', $prefix)) }}
                                    </h6>
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach ($permissions as $phanQuyen)
                                            <span
                                                class="badge bg-white border text-dark d-inline-flex align-items-center gap-2">
                                                <i class="ti ti-shield-check text-primary"></i>
                                                {{ $phanQuyen->ten }}
                                                <small class="text-muted ms-1">({{ $phanQuyen->slug }})</small>
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-warning mt-3">
                        <i class="ti ti-ban me-2"></i> Chưa có phân quyền nào được gán.
                    </div>
                @endif

                <!-- Danh sách người dùng -->
                <div class="mt-5">
                    <h5 class="fw-bold mb-3">Người dùng có vai trò này</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
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
                                                class="badge {{ $nguoiDung->trang_thai === 'hoạt động' ? 'bg-success' : 'bg-secondary' }}">
                                                {{ ucfirst($nguoiDung->trang_thai) }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.users.show', $nguoiDung->id) }}"
                                                class="btn btn-sm btn-outline-info" title="Xem chi tiết">
                                                <i class="ti ti-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">
                                            <div>
                                                <i class="ti ti-users-off fs-3 mb-2"></i>
                                                <p class="mb-0">Không có người dùng nào thuộc vai trò này</p>
                                            </div>
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
