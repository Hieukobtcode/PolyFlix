@extends('layouts.client')

@section('title', 'Góc điện ảnh')

@section('content')
    <style>
        .blog-section {
            max-width: 1000px;
            margin: 40px auto;
            padding: 0 15px;
        }

        .blog-section h2 {
            position: relative;
            padding-left: 10px;
            color: #fbfbfb;
            margin-bottom: 20px;
        }

        .blog-section h2::before {
            content: "";
            position: absolute;
            left: 0;
            top: 50%;
            width: 4px;
            height: 18px;
            background: #e9e9eb;
            transform: translateY(-50%);
        }

        .blog-item {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
            align-items: flex-start;
            margin-top: 70px;
        }

        .blog-thumb {
            flex-shrink: 0;
            width: 380px;
            height: 180px;
        }

        .blog-thumb img {
            width: 100%;
            height: 100%;
            border-radius: 8px;
            object-fit: cover;
        }

        .blog-content {
            flex: 1;
        }

        .blog-content h3 {
            margin: 0 0 8px;
            font-size: 20px;
            color: #efeeee;
        }

        .blog-meta {
            display: flex;
            gap: 10px;
            margin-bottom: 8px;
        }

        .btn-like {
            background: #6b3eff;
            color: #fff;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
        }

        .badge-views {
            display: inline-flex;
            align-items: center;
            background: #e0e0e0;
            color: #555;
            padding: 6px 10px;
            border-radius: 4px;
            font-size: 14px;
        }

        .badge-views::before {
            content: "👁";
            margin-right: 6px;
        }

        .blog-summary {
            color: #777;
            font-style: italic;
            line-height: 1.4;
            word-break: break-word;
            width: 500px;
        }

        @media (max-width: 768px) {
            .blog-item {
                flex-direction: column;
            }

            .blog-thumb {
                width: 100%;
            }
        }
    </style>

    <div class="blog-section">
        <h2>BLOG ĐIỆN ẢNH</h2>

        @forelse($baiViet as $bv)
            <a href="{{ route('show-bai-viet', $bv->id) }}" class="btn-chi-tiet">
                <div class="blog-item">
                    {{-- Ảnh bên trái --}}
                    <div class="blog-thumb">
                        <img src="{{ asset('storage/' .$bv->hinh_anh) }}" alt="{{ $bv->tieu_de }}">
                    </div>

                    {{-- Nội dung bên phải --}}
                    <div class="blog-content">
                        <h3>{{ $bv->tieu_de }}</h3>

                        <p class="blog-summary">
                            {!! \Illuminate\Support\Str::limit(strip_tags($bv->noi_dung), 50) !!}
                        </p>

                    </div>
                </div>
            </a>


        @empty
            <p>Chưa có bài viết nào.</p>
        @endforelse
    </div>


@endsection
