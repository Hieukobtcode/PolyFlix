@extends('layouts.admin')

@section('title', 'Chi tiết cấp bậc thẻ')
@section('page-title', 'Chi tiết cấp bậc thẻ')
@section('breadcrumb', 'Chi tiết cấp bậc thẻ')

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

        .progress {
            border-radius: 8px;
            height: 25px;
        }

        .progress-bar {
            font-size: 0.9em;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Chi tiết cấp bậc thẻ: {{ $capBacThe->ten }}</h5>
                <div class="btn-group gap-2">
                    <a href="{{ route('admin.cap-bac-the.edit', $capBacThe->id) }}" class="btn btn-light btn-sm"
                        title="Chỉnh sửa">
                        <i class="fas fa-edit me-1"></i> Chỉnh sửa
                    </a>
                    <a href="{{ route('admin.cap-bac-the.index') }}" class="btn btn-outline-light btn-sm" title="Quay lại">
                        <i class="fas fa-arrow-left me-1"></i> Quay lại
                    </a>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="row g-4">
                    <div class="col-md-6">
                        <h6 class="fw-bold mb-3">Thông tin cơ bản</h6>
                        <table class="table table-bordered table-hover align-middle">
                            <tr>
                                <th style="width: 150px;" class="fw-semibold text-muted">ID:</th>
                                <td>{{ $capBacThe->id }}</td>
                            </tr>
                            <tr>
                                <th class="fw-semibold text-muted">Tên:</th>
                                <td>{{ $capBacThe->ten }}</td>
                            </tr>
                            <tr>
                                <th class="fw-semibold text-muted">Mô tả:</th>
                                <td>{{ $capBacThe->mo_ta ?? 'Không có mô tả' }}</td>
                            </tr>
                            <tr>
                                <th class="fw-semibold text-muted">Tổng chi tiêu:</th>
                                <td>{{ number_format($capBacThe->tong_chi_tieu) }} đ</td>
                            </tr>
                            <tr>
                                <th class="fw-semibold text-muted">Phần trăm hoàn tiền:</th>
                                <td>{{ $capBacThe->phan_tram_ve }}%</td>
                            </tr>
                            <tr>
                                <th class="fw-semibold text-muted">Phần trăm ưu đãi DV:</th>
                                <td>{{ $capBacThe->phan_tram_dich_vu }}%</td>
                            </tr>
                            <tr>
                                <th class="fw-semibold text-muted">Trạng thái:</th>
                                <td>
                                    <span
                                        class="badge rounded-pill {{ $capBacThe->is_default ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $capBacThe->is_default ? 'Mặc định' : 'Thường' }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th class="fw-semibold text-muted">Ngày tạo:</th>
                                <td>{{ $capBacThe->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th class="fw-semibold text-muted">Cập nhật lần cuối:</th>
                                <td>{{ $capBacThe->updated_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold mb-3">Thông tin ưu đãi</h6>
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body">
                                <div class="mb-4">
                                    <h6 class="fw-semibold">Hoàn tiền</h6>
                                    <div class="progress rounded">
                                        <div class="progress-bar bg-success" role="progressbar"
                                            style="width: {{ $capBacThe->phan_tram_ve }}%;"
                                            aria-valuenow="{{ $capBacThe->phan_tram_ve }}" aria-valuemin="0"
                                            aria-valuemax="100">{{ $capBacThe->phan_tram_ve }}%</div>
                                    </div>
                                </div>
                                <div>
                                    <h6 class="fw-semibold">Ưu đãi dịch vụ</h6>
                                    <div class="progress rounded">
                                        <div class="progress-bar bg-primary" role="progressbar"
                                            style="width: {{ $capBacThe->phan_tram_dich_vu }}%;"
                                            aria-valuenow="{{ $capBacThe->phan_tram_dich_vu }}" aria-valuemin="0"
                                            aria-valuemax="100">{{ $capBacThe->phan_tram_dich_vu }}%</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-light">
                <div class="d-flex justify-content-between align-items-center">
                    @if(!$capBacThe->is_default)
                        <form action="{{ route('admin.cap-bac-the.destroy', $capBacThe->id) }}" method="POST"
                            onsubmit="return confirm('Bạn có chắc chắn muốn xóa cấp bậc thẻ này?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm" title="Xóa">
                                <i class="fas fa-trash me-1"></i> Xóa
                            </button>
                        </form>
                    @else
                        <div></div>
                        <small class="text-muted">Không thể xóa cấp bậc mặc định</small>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Xác nhận trước khi xóa
            document.querySelectorAll('.btn-outline-danger').forEach(button => {
                button.addEventListener('click', function (e) {
                    if (!confirm('Bạn có chắc chắn muốn xóa cấp bậc thẻ này?')) {
                        e.preventDefault();
                    }
                });
            });
        });
    </script>
@endsection