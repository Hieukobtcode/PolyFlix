@extends('layouts.admin')

@section('title', 'Chi tiết bình luận')
@section('page-title', 'Chi tiết bình luận')
@section('breadcrumb', 'Chi tiết bình luận')

@section('styles')
<style>
    .comment-box {
        border: 1px solid #dee2e6;
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 1rem;
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
            @forelse ($comments as $comment)
                <div class="comment-box">
                    <div class="comment-header">
                        {{ $comment->user->name ?? 'Ẩn danh' }}
                        <span class="comment-meta"> - {{ $comment->created_at->format('d/m/Y H:i') }}</span>
                    </div>

                    {{-- Gắn sao nếu có đánh giá --}}
                    @php
                        $userRating = $comment->user->ratings->firstWhere('phim_id', $phim->id)->rating ?? null;
                    @endphp
                    @if ($userRating)
                        <div class="mt-1 text-warning" style="font-size: 1.1rem;">
                            {!! str_repeat('★', $userRating) !!}
                            {!! str_repeat('☆', 5 - $userRating) !!}
                        </div>
                    @endif

                    {{-- Nội dung bình luận --}}
                    <div class="mt-2">{{ $comment->content }}</div>

                    {{-- Phản hồi admin --}}
                    @if($comment->reply)
                        <div class="admin-reply">
                            <strong>Admin phản hồi:</strong> {{ $comment->reply }}
                        </div>
                    @else
                        <form action="{{ route('admin.comments.reply', $comment->id) }}" method="POST" class="mt-3">
                            @csrf
                            <div class="input-group">
                                <input type="text" name="reply" class="form-control" placeholder="Nhập phản hồi..." required>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-reply me-1"></i> Gửi
                                </button>
                            </div>
                        </form>
                    @endif

                    {{-- Ẩn bình luận --}}
                    <div class="mt-3">
                        @if($comment->visible)
                            <form action="{{ route('admin.comments.hide', $comment->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-eye-slash me-1"></i> Ẩn bình luận
                                </button>
                            </form>
                        @else
                            <span class="badge bg-secondary">Đã ẩn</span>
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-muted fst-italic">Không có bình luận nào cho phim này.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
