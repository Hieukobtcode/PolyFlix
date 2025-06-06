@extends('layouts.admin')

@section('title', 'Quản lý chi nhánh')
@section('page-title', 'Chi tiết chi nhánh')
@section('breadcrumb', 'Chi tiết chi nhánh')

@section('styles')
    <style>
        .card {
            border-radius: 12px;
        }

        .detail-row {
            padding: 12px 0;
            border-bottom: 1px solid #ddd;
        }

        .detail-label {
            font-weight: bold;
            width: 200px;
        }

        .badge {
            font-size: 0.9em;
        }

        .back-btn {
            margin-top: 20px;
        }

        .table th,
        .table td {
            vertical-align: middle;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row g-3">
            {{-- Cột trái: Thông tin chi nhánh --}}
            <div class="col-md-2">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-primary text-white d-flex align-items-center py-2 px-3">
                        <i class="fas fa-building fa-sm me-2"></i>
                        <span class="fw-semibold">Chi nhánh</span>
                    </div>
                    <div class="card-body px-3 py-2 small">
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2">
                                <i class="fas fa-store-alt text-primary me-1"></i>
                                <strong>Tên</strong><br>
                                <span class="text-muted">{{ $chiNhanh->ten_chi_nhanh }}</span>
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-map-marker-alt text-danger me-1"></i>
                                <strong>Địa chỉ</strong><br>
                                <span class="text-muted">{{ $chiNhanh->dia_chi }}</span>
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-user-tie text-secondary me-1"></i>
                                <strong>Quản lý</strong><br>
                                @if ($chiNhanh->quan_ly_id)
                                    <a href="#" class="text-decoration-none text-muted">
                                        {{ $chiNhanh->quanLy->ho_ten ?? 'ID: ' . $chiNhanh->quan_ly_id }}
                                    </a>
                                @else
                                    <span class="text-muted fst-italic">Chưa phân công</span>
                                @endif
                            </li>
                            <li class="mb-0">
                                <i class="fas fa-info-circle text-warning me-1"></i>
                                <strong>Trạng thái</strong><br>
                                @php
                                    $statusColors = [
                                        'hoat_dong' => '#198754',
                                        'tam_dung' => '#ffc107',
                                        'dong_cua' => '#6c757d',
                                    ];
                                    $statusLabels = [
                                        'hoat_dong' => 'Hoạt động',
                                        'tam_dung' => 'Tạm dừng',
                                        'dong_cua' => 'Đóng cửa',
                                    ];
                                @endphp
                                <span class="d-inline-block mt-1 px-3 py-1 text-white fw-bold rounded"
                                    style="background-color: {{ $statusColors[$chiNhanh->trang_thai] ?? '#6c757d' }}; font-size: 0.85rem;">
                                    {{ $statusLabels[$chiNhanh->trang_thai] ?? 'Không rõ' }}
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Cột phải: Danh sách rạp --}}
            <div class="col-md-10">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white py-2 d-flex justify-content-between align-items-center">
                        <strong><i class="fas fa-film me-1"></i> Rạp thuộc chi nhánh</strong>
                        <a href="{{ route('admin.rap-phim.create', ['chiNhanhId' => $chiNhanh->id]) }}"
                            class="btn btn-light btn-sm d-flex align-items-center" title="Thêm rạp chiếu">
                            <i class="fas fa-plus me-1"></i> Thêm rạp chiếu
                        </a>
                    </div>


                    <div class="card-body p-3">
                        @if ($chiNhanh->RapPhim->isEmpty())
                            <p class="text-muted mb-0"><i class="fas fa-info-circle me-1"></i> Không có rạp nào thuộc chi
                                nhánh này.</p>
                        @else
                            <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                                <table class="table table-sm table-bordered align-middle mb-0">
                                    <thead class="table-dark text-center small">
                                        <tr>
                                            <th style="width: 5%">#</th>
                                            <th>Tên rạp</th>
                                            <th>Địa chỉ</th>
                                            <th style="width: 15%">Trạng thái</th>
                                            <th>Quản lý</th>
                                            <th style="width: 20%">Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($chiNhanh->RapPhim as $index => $rap)
                                            <tr>
                                                <td class="text-center">{{ $index + 1 }}</td>
                                                <td>{{ $rap->ten_rap }}</td>
                                                <td>{{ $rap->dia_chi }}</td>
                                                <td class="text-center">
                                                    @php
                                                        $statusColors = [
                                                            'đang hoạt động' => '#198754',
                                                            'bảo trì' => '#ffc107',
                                                            'đã đóng' => '#dc3545',
                                                        ];

                                                        $statusTextColors = [
                                                            'đang hoạt động' => '#fff',
                                                            'bảo trì' => '#000',
                                                            'đã đóng' => '#fff',
                                                        ];

                                                        $statusLabels = [
                                                            'đang hoạt động' => 'Hoạt động',
                                                            'bảo trì' => 'Tạm dừng',
                                                            'đã đóng' => 'Đóng cửa',
                                                        ];

                                                        $bg = $statusColors[$rap->trang_thai] ?? '#6c757d';
                                                        $text = $statusTextColors[$rap->trang_thai] ?? '#fff';
                                                        $label = $statusLabels[$rap->trang_thai] ?? 'Không rõ';
                                                    @endphp
                                                    <span class="d-inline-block mt-1 px-3 py-1 fw-bold rounded"
                                                        style="background-color: {{ $bg }}; color: {{ $text }}; font-size: 0.85rem;">
                                                        {{ $label }}
                                                    </span>

                                                </td>
                                                <td class="text-center">
                                                    @if ($rap->quan_ly_id)
                                                        <a href="#" class="text-decoration-none">
                                                            {{ $rap->quanLy->ho_ten ?? 'ID: ' . $rap->quan_ly_id }}
                                                        </a>
                                                    @else
                                                        <span class="text-muted fst-italic">Chưa phân công</span>
                                                    @endif
                                                </td>

                                                <td class="text-center">
                                                    <a href="{{ route('admin.rap-phim.show', $rap->id) }}"
                                                        class="btn btn-sm btn-outline-info" title="Xem">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.rap-phim.edit', $rap->id) }}"
                                                        class="btn btn-sm btn-outline-primary" title="Sửa">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="{{ route('admin.phong-chieu.create', ['rap_phim_id' => $rap->id]) }}"
                                                        class="btn btn-sm btn-outline-success" title="Thêm phòng">
                                                        <i class="fas fa-plus-circle"></i>
                                                    </a>
                                                    @if ($rap->quan_ly_id)
                                                        <a href="#" class="btn btn-sm btn-outline-warning"
                                                            title="Xem thông tin">
                                                            <i class="fa-solid fa-user"></i>
                                                        </a>
                                                    @else
                                                        <a href="#" class="btn btn-sm btn-outline-warning"
                                                            title="Phân công quản lý">
                                                            <i class="fas fa-user-plus"></i>
                                                        </a>
                                                    @endif
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
        </div>
    </div>

@endsection

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.forEach(function(el) {
                new bootstrap.Tooltip(el);
            });
        });
    </script>
@endsection
