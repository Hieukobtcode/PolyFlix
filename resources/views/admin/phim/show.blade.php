@extends('layouts.admin')

@section('title', 'Chi tiết phim')
@section('page-title', 'Chi tiết phim')

@section('styles')
    <style>
        .card {
            border-radius: 10px;
        }

        .img-thumbnail,
        iframe {
            border-radius: 8px;
        }

        .badge {
            font-size: 0.9em;
            padding: 0.5em 1em;
        }

        .list-group-item {
            padding: 0.75rem 1rem;
        }

        .btn {
            border-radius: 8px;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Thông tin chi tiết phim</h5>
                <div class="btn-group gap-2">
                    <a href="{{ route('admin.phim.edit', $phim->id) }}" class="btn btn-light btn-sm" title="Chỉnh sửa">
                        <i class="fas fa-edit me-1"></i> Chỉnh sửa
                    </a>
                    <a href="{{ route('admin.phim.index') }}" class="btn btn-outline-light btn-sm" title="Quay lại">
                        <i class="fas fa-arrow-left me-1"></i> Quay lại
                    </a>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="text-center mb-4">
                            @if($phim->poster)
                                <img src="{{ asset('storage/' . $phim->poster) }}" alt="{{ $phim->ten_phim }}"
                                    class="img-fluid img-thumbnail rounded" style="max-height: 400px;">
                            @else
                                <div class="border p-5 text-center bg-light rounded">
                                    <i class="fas fa-film fa-5x text-secondary"></i>
                                    <p class="mt-3 text-muted">Không có poster</p>
                                </div>
                            @endif
                        </div>

                        <div class="mb-4">
                            <h5 class="fw-bold">Thể loại</h5>
                            <div>
                                @forelse($phim->theLoais as $theLoai)
                                    <span class="badge bg-info rounded-pill me-1">{{ $theLoai->ten_the_loai }}</span>
                                @empty
                                    <span class="text-muted">Chưa có thể loại</span>
                                @endforelse
                            </div>
                        </div>

                        <div class="mb-4">
                            <h5 class="fw-bold">Thông tin cơ bản</h5>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span class="fw-semibold text-muted">Thời lượng:</span>
                                    <span>{{ $phim->thoi_luong ? $phim->thoi_luong . ' phút' : 'N/A' }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span class="fw-semibold text-muted">Ngày phát hành:</span>
                                    <span>{{ $phim->ngay_phat_hanh ? $phim->ngay_phat_hanh->format('d/m/Y') : 'N/A' }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span class="fw-semibold text-muted">Ngôn ngữ:</span>
                                    <span>{{ $phim->ngon_ngu ?? 'N/A' }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span class="fw-semibold text-muted">Quốc gia:</span>
                                    <span>{{ $phim->quoc_gia ?? 'N/A' }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span class="fw-semibold text-muted">Độ tuổi:</span>
                                    <span>{{ $phim->do_tuoi ?? 'N/A' }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span class="fw-semibold text-muted">Trạng thái:</span>
                                    <span
                                        class="badge rounded-pill {{
        $phim->trang_thai === 'đang chiếu' ? 'bg-success' :
        ($phim->trang_thai === 'sắp chiếu' ? 'bg-warning' :
            ($phim->trang_thai === 'đã kết thúc' ? 'bg-secondary' : 'bg-danger'))
                                                                                                                        }}">
                                        {{ ucfirst($phim->trang_thai) }}
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <h3 class="mb-3 fw-bold">{{ $phim->ten_phim }}</h3>

                        <div class="mb-4">
                            <h5 class="fw-bold">Mô tả</h5>
                            <p class="text-muted">{{ $phim->mo_ta ?? 'Không có mô tả' }}</p>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h5 class="fw-bold">Đạo diễn</h5>
                                <p class="text-muted">{{ $phim->dao_dien ?? 'Chưa cập nhật' }}</p>
                            </div>
                            <div class="col-md-6">
                                <h5 class="fw-bold">Diễn viên</h5>
                                <p class="text-muted">{{ $phim->dien_vien ?? 'Chưa cập nhật' }}</p>
                            </div>
                        </div>

                        @if($phim->trailer)
                                            <div class="mb-4">
                                                <h5 class="fw-bold">Trailer</h5>
                                                <div class="ratio ratio-16x9">
                                                    @php
                                                        // Chuyển đổi URL YouTube thành embed URL
                                                        $trailerUrl = $phim->trailer;
                                                        if (strpos($trailerUrl, 'youtube.com/watch?v=') !== false) {
                                                            $videoId = substr($trailerUrl, strpos($trailerUrl, 'v=') + 2);
                                                            if (strpos($videoId, '&') !== false) {
                                                                $videoId = substr($videoId, 0, strpos($videoId, '&'));
                                                            }
                                                            $embedUrl = "https://www.youtube.com/embed/$videoId";
                                                        } elseif (strpos($trailerUrl, 'youtu.be/') !== false) {
                                                            $videoId = substr($trailerUrl, strrpos($trailerUrl, '/') + 1);
                                                            $embedUrl = "https://www.youtube.com/embed/$videoId";
                                                        } else {
                                                            $embedUrl = $trailerUrl;
                                                        }
                                                    @endphp
                                                    <iframe src="{{ $embedUrl }}" title="Trailer {{ $phim->ten_phim }}" class="rounded"
                                                        allowfullscreen></iframe>
                                                </div>
                                            </div>
                        @endif

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h5 class="fw-bold">Ngày tạo</h5>
                                <p class="text-muted">{{ $phim->create_at->format('d/m/Y H:i:s') }}</p>
                            </div>
                            <div class="col-md-6">
                                <h5 class="fw-bold">Cập nhật lần cuối</h5>
                                <p class="text-muted">
                                    {{ $phim->updated_at ? $phim->updated_at->format('d/m/Y H:i:s') : 'Chưa cập nhật' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection