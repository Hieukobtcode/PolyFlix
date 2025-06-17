@extends('layouts.admin')

@section('title', 'Danh sách Bình luận')
@section('page-title', 'Danh sách Bình luận')
@section('breadcrumb', 'Danh sách Bình luận')

@section('styles')
<style>
    .card {
        border-radius: 10px;
    }
    .table th, .table td {
        vertical-align: middle;
    }
    .badge {
        font-size: 0.9em;
        padding: 0.5em 1em;
    }
    .btn, .form-control, .form-select {
        border-radius: 8px;
    }
    .table-dark {
        background-color: #343a40;
    }
    .poster-img {
        width: 60px;
        height: auto;
        border-radius: 6px;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">Danh sách Bình luận theo phim</h5>
        </div>

        <div class="card-body p-4">
            <form method="GET" action="{{ route('admin.comments.index') }}" class="row g-3 mb-4">
                <div class="col-md-4">
                    <label for="chi_nhanh_id" class="form-label fw-semibold">Chi nhánh</label>
                    <select name="chi_nhanh_id" id="chi_nhanh_id" class="form-select">
                        <option value="">-- Tất cả chi nhánh --</option>
                        @foreach ($chiNhanhs as $cn)
                            <option value="{{ $cn->id }}" {{ request('chi_nhanh_id') == $cn->id ? 'selected' : '' }}>
                                {{ $cn->ten_chi_nhanh }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="rap_phim_id" class="form-label fw-semibold">Rạp phim</label>
                    <select name="rap_phim_id" id="rap_phim_id" class="form-select">
                        <option value="">-- Tất cả rạp --</option>
                    </select>
                </div>
                 <div class="col-md-3 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary" title="Lọc">
                            <i class="fas fa-filter me-1"></i> Lọc
                        </button>
                        <a href="{{ route('admin.comments.index') }}" class="btn btn-outline-secondary"
                            title="Xóa bộ lọc">
                            <i class="fas fa-sync-alt me-1"></i> Xóa bộ lọc
                        </a>
                    </div>
            </form>
            <div><h4>Phim Đang chiếu</h4></div>

            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-center" style="width: 5%">#</th>
                            <th class="text-center" style="width: 10%">Poster</th>
                            <th>Tên phim</th>
                            <th class="text-center" style="width: 15%">Ngày khởi chiếu</th>
                            <th class="text-center" style="width: 15%">Bình luận</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($phims as $index => $phim)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td class="text-center">
                                @if($phim->poster)
                                    <img src="{{ $phim->poster }}" class="poster-img" alt="Poster">
                                @else
                                    <span class="badge bg-secondary">Không có ảnh</span>
                                @endif
                            </td>
                            <td>{{ $phim->ten_phim }}</td>
                            <td class="text-center">{{ optional($phim->ngay_phat_hanh)->format('d/m/Y') }}</td>
                            <td class="text-center">
                                <a href="{{ route('admin.comments.show', $phim->id) }}" class="btn btn-outline-primary">
                                    <i class="fas fa-comments me-1"></i> Xem bình luận
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-3">
                                <i class="fas fa-folder-open me-1"></i> Không có phim nào phù hợp
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
       // Tải danh sách rạp theo chi nhánh
            const chiNhanhSelect = document.getElementById('chi_nhanh_id');
            const rapSelect = document.getElementById('rap_phim_id');
            const chiNhanhs = @json($chiNhanhs);
            const selectedChiNhanh = '{{ request('chi_nhanh_id') }}';
            const selectedRap = '{{ request('rap_phim_id') }}';

            function renderRaps(chiNhanhId) {
                rapSelect.innerHTML = '<option value="">-- Tất cả rạp --</option>';
                if (!chiNhanhId) return;

                const found = chiNhanhs.find(cn => cn.id == chiNhanhId);
                if (found && found.rap_phims) {
                    found.rap_phims.forEach(rap => {
                        const opt = document.createElement('option');
                        opt.value = rap.id;
                        opt.textContent = rap.ten_rap;
                        rapSelect.appendChild(opt);
                    });

                    if (selectedRap) rapSelect.value = selectedRap;
                }
            }

            chiNhanhSelect.addEventListener('change', function() {
                renderRaps(this.value);
            });

            if (selectedChiNhanh) {
                chiNhanhSelect.value = selectedChiNhanh;
                renderRaps(selectedChiNhanh);
            }
        });
</script>
@endsection
