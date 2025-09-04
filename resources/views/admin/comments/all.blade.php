@extends('layouts.admin')

@section('title', 'Tất cả Bình luận')
@section('page-title', 'Tất cả Bình luận')
@section('breadcrumb', 'Tất cả Bình luận')

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

    .comment-item {
        border: 1px solid #dee2e6;
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
    }

    .comment-item:hover {
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .comment-visible {
        border-left: 3px solid #28a745;
    }

    .comment-hidden {
        border-left: 3px solid #dc3545;
        background-color: #f8f9fa;
    }

    .rating-stars {
        color: #ffc107;
    }

    .admin-reply {
        background: #e3f2fd;
        padding: 0.75rem;
        border-left: 3px solid #2196f3;
        border-radius: 5px;
        margin-top: 0.5rem;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">Quản lý Tất cả Bình luận</h5>
            <div class="btn-group" role="group">
                <a href="{{ route('admin.comments.index', ['view' => 'movies'] + request()->only(['chi_nhanh_id', 'rap_phim_id', 'status'])) }}" 
                   class="btn btn-light btn-sm">
                    <i class="fas fa-film me-1"></i> Theo phim
                </a>
                <a href="{{ route('admin.comments.index', ['view' => 'comments'] + request()->only(['chi_nhanh_id', 'rap_phim_id', 'status'])) }}" 
                   class="btn btn-light btn-sm active">
                    <i class="fas fa-comments me-1"></i> Tất cả bình luận
                </a>
            </div>
        </div>

        <div class="card-body p-4">
            {{-- Thống kê tổng quan --}}
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body text-center py-3">
                            <h5 class="mb-0">{{ $totalComments }}</h5>
                            <small>Tổng bình luận</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body text-center py-3">
                            <h5 class="mb-0">{{ $visibleComments }}</h5>
                            <small>Đang hiển thị</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body text-center py-3">
                            <h5 class="mb-0">{{ $hiddenComments }}</h5>
                            <small>Đã ẩn</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body text-center py-3">
                            <h5 class="mb-0">{{ $repliedComments }}</h5>
                            <small>Đã phản hồi</small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Filter Form --}}
            <form method="GET" class="mb-4">
                <input type="hidden" name="view" value="comments">
                <div class="row g-3">
                    <div class="col-md-3">
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
                    <div class="col-md-3">
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
                    <div class="col-md-3">
                        <label class="form-label">Lọc theo trạng thái:</label>
                        <select name="status" class="form-select">
                            <option value="">-- Tất cả bình luận --</option>
                            <option value="visible" {{ request('status') === 'visible' ? 'selected' : '' }}>Đang hiển thị</option>
                            <option value="hidden" {{ request('status') === 'hidden' ? 'selected' : '' }}>Đã ẩn</option>
                            <option value="replied" {{ request('status') === 'replied' ? 'selected' : '' }}>Đã phản hồi</option>
                            <option value="unreplied" {{ request('status') === 'unreplied' ? 'selected' : '' }}>Chưa phản hồi</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter me-1"></i> Lọc
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            {{-- Comments List --}}
            @forelse($comments as $comment)
            <div class="comment-item {{ $comment->visible ? 'comment-visible' : 'comment-hidden' }}">
                <div class="row">
                    <div class="col-md-2">
                        @if($comment->phim && $comment->phim->poster)
                        <img src="{{ asset('storage/' . $comment->phim->poster) }}" 
                             class="img-fluid rounded" 
                             alt="{{ $comment->phim->ten_phim }}"
                             style="width: 80px; height: 120px; object-fit: cover;">
                        @else
                        <div class="d-flex align-items-center justify-content-center bg-light rounded" 
                             style="width: 80px; height: 120px;">
                            <i class="fas fa-film fa-2x text-muted"></i>
                        </div>
                        @endif
                    </div>
                    <div class="col-md-10">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <h6 class="mb-1">
                                    <strong>{{ $comment->user->name ?? 'Ẩn danh' }}</strong>
                                    <small class="text-muted">- {{ $comment->created_at->format('d/m/Y H:i') }}</small>
                                </h6>
                                <p class="mb-1 text-muted">
                                    <strong>Phim:</strong> {{ $comment->phim->ten_phim ?? 'Không xác định' }}
                                </p>
                            </div>
                            <div class="text-end">
                                @if(!$comment->visible)
                                <span class="badge bg-danger">Đã ẩn</span>
                                @endif
                                @if($comment->reply)
                                <span class="badge bg-success">Đã phản hồi</span>
                                @endif
                            </div>
                        </div>

                        <div class="comment-content mb-2">
                            <p class="mb-1 {{ $comment->visible ? '' : 'text-muted fst-italic' }}">
                                {{ $comment->visible ? $comment->content : 'Bình luận này đã bị ẩn.' }}
                            </p>
                        </div>

                        @if($comment->reply)
                        <div class="admin-reply mb-3">
                            <strong><i class="fas fa-user-shield me-1"></i>Admin phản hồi:</strong>
                            <p class="mb-0 mt-1">{{ $comment->reply }}</p>
                        </div>
                        @else
                        <form action="{{ route('admin.comments.reply', $comment->id) }}" method="POST" class="mb-3">
                            @csrf
                            <div class="input-group">
                                <input type="text" name="reply" class="form-control" 
                                       placeholder="Nhập phản hồi cho khách hàng..." required>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-reply me-1"></i> Gửi phản hồi
                                </button>
                            </div>
                        </form>
                        @endif

                        <div class="d-flex gap-2">
                            @if ($comment->visible)
                            <form action="{{ route('admin.comments.hide', $comment->id) }}" method="POST">
                                @csrf
                                <button class="btn btn-sm btn-outline-danger" 
                                        onclick="return confirm('Bạn có chắc muốn ẩn bình luận này?')">
                                    <i class="fas fa-eye-slash me-1"></i> Ẩn bình luận
                                </button>
                            </form>
                            @else
                            <form action="{{ route('admin.comments.unhide', $comment->id) }}" method="POST">
                                @csrf
                                <button class="btn btn-sm btn-outline-success" 
                                        onclick="return confirm('Bạn có chắc muốn hiện lại bình luận này?')">
                                    <i class="fas fa-eye me-1"></i> Hiện lại bình luận
                                </button>
                            </form>
                            @endif
                            
                            <a href="{{ route('admin.comments.show', $comment->phim_id) }}" 
                               class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-film me-1"></i> Xem tất cả bình luận phim này
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-5">
                <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Không có bình luận</h5>
                <p class="text-muted">Không tìm thấy bình luận nào phù hợp với bộ lọc.</p>
            </div>
            @endforelse

            {{-- Phân trang --}}
            @if($comments->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $comments->appends(request()->query())->links() }}
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
