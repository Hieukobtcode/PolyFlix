@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0">
            <!-- Header -->
            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">
                    <i class="ti ti-lock me-2"></i>Thông tin phân quyền
                </h5>
                <div class="btn-group gap-2">
                    <a href="{{ route('admin.phan-quyen.index') }}" class="btn btn-outline-light btn-sm">
                        <i class="ti ti-arrow-left me-1"></i> Quay lại
                    </a>
                </div>
            </div>

            <!-- Body -->
            <div class="card-body p-4">
                <!-- Thông tin chi tiết -->
                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <div class="d-flex">
                            <div class="text-muted fw-semibold" style="width: 150px;">Tên quyền:</div>
                            <div>{{ $phanQuyen->ten }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex">
                            <div class="text-muted fw-semibold" style="width: 150px;">Slug:</div>
                            <div>{{ $phanQuyen->slug }}</div>
                        </div>
                    </div>
                    
                    
                </div>

                <!-- Vai trò có quyền này -->
                <div class="mt-5">
                    <h5 class="fw-bold mb-3">Các vai trò có quyền này</h5>
                    <div class="table-responsive">
                        <table class="table align-middle text-nowrap mb-0">
                            <thead class="bg-gradient-dark text-white">
                                <tr>
                                    <th class="text-center" style="width: 5%">#</th>
                                    <th>Tên vai trò</th>
                                    <th class="text-center" style="width: 10%">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($phanQuyen->vaiTros as $index => $vaiTro)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td>{{ $vaiTro->ten }}</td>
                                        {{-- <td class="text-center">{{ $vaiTro->created_at->format('d/m/Y H:i') }}</td> --}}
                                        <td class="text-center">
                                            <a href="{{ route('admin.vai-tro.show', $vaiTro->id) }}"
                                                class="btn btn-sm btn-outline-info">
                                                <i class="ti ti-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">
                                            <div class="py-3">
                                                <i class="ti ti-user-lock fs-3 mb-2"></i>
                                                <p class="mb-0">Không có vai trò nào sử dụng quyền này</p>
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
