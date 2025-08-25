@extends('layouts.admin')

@section('title', 'Danh sách Bình luận')
@section('page-title', 'Danh sách Bình luận')
@section('breadcrumb', 'Danh sách Bình luận')

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

    .btn,
    .form-control,
    .form-select {
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
            <h5 class="mb-0 fw-bold">Quản lý Bình luận & Đánh giá</h5>
            <div class="btn-group" role="group">
                <a href="{{ route('admin.comments.index', ['view' => 'movies'] + request()->only(['chi_nhanh_id', 'rap_phim_id'])) }}" 
                   class="btn btn-light btn-sm {{ request('view', 'movies') === 'movies' ? 'active' : '' }}">
                    <i class="fas fa-film me-1"></i> Theo phim
                </a>
                <a href="{{ route('admin.comments.index', ['view' => 'comments'] + request()->only(['chi_nhanh_id', 'rap_phim_id'])) }}" 
                   class="btn btn-light btn-sm {{ request('view') === 'comments' ? 'active' : '' }}">
                    <i class="fas fa-comments me-1"></i> Tất cả bình luận
                </a>
            </div>
        </div>

        <div class="card-body p-4">
            {{-- Thống kê tổng quan --}}
            <div class="row mb-4">
                <div class="col-md-2">
                    <div class="card bg-primary text-white">
                        <div class="card-body text-center py-3">
                            <h5 class="mb-0">{{ $totalComments }}</h5>
                            <small>Tổng bình luận</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card bg-success text-white">
                        <div class="card-body text-center py-3">
                            <h5 class="mb-0">{{ $visibleComments }}</h5>
                            <small>Đang hiển thị</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card bg-warning text-white">
                        <div class="card-body text-center py-3">
                            <h5 class="mb-0">{{ $hiddenComments }}</h5>
                            <small>Đã ẩn</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card bg-info text-white">
                        <div class="card-body text-center py-3">
                            <h5 class="mb-0">{{ $repliedComments }}</h5>
                            <small>Đã phản hồi</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card bg-secondary text-white">
                        <div class="card-body text-center py-3">
                            <h5 class="mb-0">{{ $totalRatings }}</h5>
                            <small>Lượt đánh giá</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card bg-dark text-white">
                        <div class="card-body text-center py-3">
                            <h5 class="mb-0">{{ $averageRating ? number_format($averageRating, 1) : '0' }}</h5>
                            <small>Điểm TB</small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Filter Form --}}
            <form method="GET" action="{{ route('admin.comments.index') }}" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Lọc theo chi nhánh:</label>
                        <select name="chi_nhanh_id" class="form-select" id="chiNhanhSelect">
                            <option value="">-- Tất cả chi nhánh --</option>
                            @foreach($chiNhanhs as $chiNhanh)
                            <option value="{{ $chiNhanh->id }}" {{ request('chi_nhanh_id') == $chiNhanh->id ? 'selected' : '' }}>
                                {{ $chiNhanh->ten_chi_nhanh }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Lọc theo rạp:</label>
                        <select name="rap_phim_id" class="form-select" id="rapPhimSelect">
                            <option value="">-- Tất cả rạp --</option>
                            @if(request('chi_nhanh_id'))
                            @php
                            $selectedChiNhanh = $chiNhanhs->firstWhere('id', request('chi_nhanh_id'));
                            @endphp
                            @if($selectedChiNhanh)
                            @foreach($selectedChiNhanh->rapPhims as $rap)
                            <option value="{{ $rap->id }}" {{ request('rap_phim_id') == $rap->id ? 'selected' : '' }}>
                                {{ $rap->ten_rap }}
                            </option>
                            @endforeach
                            @endif
                            @endif
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter me-1"></i> Lọc
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            {{-- Movies List --}}
            @if($phims->count() > 0)
            <div class="row">
                @foreach($phims as $phim)
                @php
                $commentsCount = $phim->comments()->count();
                $averageRating = $phim->ratings()->avg('rating');
                $ratingCount = $phim->ratings()->count();
                @endphp
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="row g-0 h-100">
                            <div class="col-4">
                                @if($phim->poster)
                                <img src="{{ asset('storage/' . $phim->poster) }}"
                                    class="img-fluid rounded-start poster-img h-100"
                                    alt="{{ $phim->ten_phim }}" style="object-fit: cover;">
                                @else
                                <div class="d-flex align-items-center justify-content-center h-100 bg-light rounded-start">
                                    <i class="fas fa-film fa-2x text-muted"></i>
                                </div>
                                @endif
                            </div>
                            <div class="col-8">
                                <div class="card-body d-flex flex-column h-100">
                                    <h6 class="card-title fw-bold mb-2">{{ Str::limit($phim->ten_phim, 30) }}</h6>

                                    <div class="mb-2">
                                        @if($averageRating)
                                        <div class="text-warning mb-1">
                                            @for($i = 1; $i <= 5; $i++)
                                                {{ $i <= round($averageRating) ? '★' : '☆' }}
                                                @endfor
                                                <small class="text-muted ms-1">{{ number_format($averageRating, 1) }}/5</small>
                                        </div>
                                        @else
                                        <small class="text-muted">Chưa có đánh giá</small>
                                        @endif
                                    </div>

                                    <div class="mb-2">
                                        <small class="text-muted">
                                            <i class="fas fa-comments me-1"></i>{{ $commentsCount }} bình luận
                                        </small><br>
                                        <small class="text-muted">
                                            <i class="fas fa-star me-1"></i>{{ $ratingCount }} đánh giá
                                        </small>
                                    </div>

                                    <div class="mt-auto">
                                        <a href="{{ route('admin.comments.show', $phim->id) }}"
                                            class="btn btn-primary btn-sm">
                                            <i class="fas fa-eye me-1"></i>Xem chi tiết
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Không có phim nào</h5>
                <p class="text-muted">Chưa có phim nào có bình luận hoặc đánh giá.</p>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
    document.getElementById('chiNhanhSelect').addEventListener('change', function() {
        const chiNhanhId = this.value;
        const rapSelect = document.getElementById('rapPhimSelect');

        // Clear rap options
        rapSelect.innerHTML = '<option value="">-- Tất cả rạp --</option>';

        if (chiNhanhId) {
            // Find the selected chi nhanh and populate raps
            @foreach($chiNhanhs as $chiNhanh)
            if ({{ $chiNhanh->id }} == chiNhanhId) {
                @foreach($chiNhanh->rapPhims as $rap)
                rapSelect.innerHTML += '<option value="{{ $rap->id }}">{{ $rap->ten_rap }}</option>';
                @endforeach
            }
            @endforeach
        }
    });
</script>
@endsection