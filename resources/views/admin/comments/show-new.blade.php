@extends('layouts.admin')

@section('title', 'Chi tiết bình luận')
@section('page-title', 'Chi tiết bình luận')
@section('breadcrumb')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('admin.comments.index') }}">Quản lý bình luận</a></li>
    <li class="breadcrumb-item active">{{ $phim->ten_phim }}</li>
</ol>
@endsection

@section('styles')
<style>
    .card {
        border-radius: 10px;
    }

    .comment-box {
        border: 1px solid #dee2e6;
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
    }

    .comment-box:hover {
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .comment-header {
        font-weight: bold;
    }

    .comment-meta {
        font-size: 0.85rem;
        color: #6c757d;
    }

    .admin-reply {
        background: #f8f9fa;
        padding: 0.75rem;
        border-left: 3px solid #0d6efd;
        border-radius: 5px;
        margin-top: 0.5rem;
    }

    .comment-actions {
        gap: 10px;
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
        font-size: 1.1rem;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">Bình luận phim: {{ $phim->ten_phim }}</h5>
            <a href="{{ route('admin.comments.index') }}" class="btn btn-light btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Quay lại
            </a>
        </div>
        
        <div class="px-4 pt-3">
            {{-- Thống kê tổng quan --}}
            <div class="row mb-3">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body text-center">
                            <h4 class="mb-0">{{ $totalComments }}</h4>
                            <small>Tổng bình luận</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body text-center">
                            <h4 class="mb-0">{{ $visibleComments }}</h4>
                            <small>Đang hiển thị</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body text-center">
                            <h4 class="mb-0">{{ $hiddenComments }}</h4>
                            <small>Đã ẩn</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body text-center">
                            <h4 class="mb-0">{{ $repliedComments }}</h4>
                            <small>Đã phản hồi</small>
                        </div>
                    </div>
                </div>
            </div>

            @if ($ratingCount > 0)
            <p class="mb-1">
                ⭐ <strong>{{ number_format($averageRating, 1) }}/5</strong>
                <span class="text-muted">({{ $ratingCount }} lượt đánh giá)</span>
            </p>
            @else
            <p class="text-muted">Chưa có đánh giá.</p>
            @endif
        </div>

        <div class="card-body p-4">
            {{-- Filter Form --}}
            <form method="GET" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Lọc theo trạng thái:</label>
                        <select name="status" class="form-select">
                            <option value="">-- Tất cả bình luận --</option>
                            <option value="visible" {{ request('status') === 'visible' ? 'selected' : '' }}>Đang hiển thị</option>
                            <option value="hidden" {{ request('status') === 'hidden' ? 'selected' : '' }}>Đã ẩn</option>
                            <option value="replied" {{ request('status') === 'replied' ? 'selected' : '' }}>Đã phản hồi</option>
                            <option value="unreplied" {{ request('status') === 'unreplied' ? 'selected' : '' }}>Chưa phản hồi</option>
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

            @forelse ($comments as $comment)
            <div class="comment-box {{ $comment->visible ? 'comment-visible' : 'comment-hidden' }}">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="comment-header">
                        <strong>{{ $comment->user->name ?? 'Ẩn danh' }}</strong>
                        <span class="comment-meta"> - {{ $comment->created_at->format('d/m/Y H:i') }}</span>
                        @if(!$comment->visible)
                        <span class="badge bg-danger ms-2">Đã ẩn</span>
                        @endif
                        @if($comment->reply)
                        <span class="badge bg-success ms-2">Đã phản hồi</span>
                        @endif
                    </div>
                </div>

                {{-- Gắn sao nếu có đánh giá --}}
                @php
                $userRating = $ratings->get($comment->user_id)->rating ?? null;
                @endphp
                @if ($userRating)
                <div class="mt-2 rating-stars">
                    {!! str_repeat('★', $userRating) !!}
                    {!! str_repeat('☆', 5 - $userRating) !!}
                    <small class="text-muted ms-1">({{ $userRating }}/5)</small>
                </div>
                @endif

                {{-- Nội dung hiển thị khác nhau tùy theo visible --}}
                <div class="mt-2 {{ $comment->visible ? '' : 'text-muted fst-italic' }}">
                    {{ $comment->visible ? $comment->content : 'Bình luận này đã bị ẩn.' }}
                </div>

                @if($comment->reply)
                <div class="admin-reply">
                    <strong><i class="fas fa-user-shield me-1"></i>Admin phản hồi:</strong> {{ $comment->reply }}
                </div>
                @else
                <form action="{{ route('admin.comments.reply', $comment->id) }}" method="POST" class="mt-3">
                    @csrf
                    <div class="input-group">
                        <input type="text" name="reply" class="form-control" placeholder="Nhập phản hồi cho khách hàng..." required>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-reply me-1"></i> Gửi phản hồi
                        </button>
                    </div>
                </form>
                @endif

                <div class="mt-3 d-flex comment-actions">
                    @if ($comment->visible)
                    {{-- Nếu đang hiện: chỉ hiển thị nút Ẩn --}}
                    <form action="{{ route('admin.comments.hide', $comment->id) }}" method="POST">
                        @csrf
                        <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Bạn có chắc muốn ẩn bình luận này?')">
                            <i class="fas fa-eye-slash me-1"></i> Ẩn bình luận
                        </button>
                    </form>
                    @else
                    {{-- Nếu đã ẩn: hiển thị nút "Hiện lại" --}}
                    <form action="{{ route('admin.comments.unhide', $comment->id) }}" method="POST">
                        @csrf
                        <button class="btn btn-sm btn-outline-success" onclick="return confirm('Bạn có chắc muốn hiện lại bình luận này?')">
                            <i class="fas fa-eye me-1"></i> Hiện lại bình luận
                        </button>
                    </form>
                    @endif
                </div>
            </div>
            @empty
            <div class="text-center py-5">
                <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Không có bình luận</h5>
                <p class="text-muted">Chưa có bình luận nào cho phim này.</p>
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
@endsection
