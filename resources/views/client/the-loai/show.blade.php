@extends('layouts.client')

@section('styles')
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #18132a 0%, #362155 100%);
            --accent-yellow: #ffeb3b;
            --accent-orange: #ff9800;
            --shadow: 0 4px 12px rgba(0, 0, 0, 0.24);
            --max-width: 1240px;
        }

        .category-bg {
            background: var(--primary-gradient);
            min-height: 100vh;
            padding: 40px 0 60px 0;
        }

        .category-title {
            color: var(--accent-yellow);
            font-size: 2.4rem;
            text-transform: uppercase;
            font-family: 'Anton', Arial, sans-serif;
            margin-bottom: 28px;
            text-align: center;
            text-shadow: 0 4px 16px #31275370;
        }

        .movie-list-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 34px 28px;
            max-width: var(--max-width);
            margin: 0 auto;
            padding: 0 16px;
        }

        .movie-card-style {
            background: #211d3a;
            border-radius: 14px;
            box-shadow: var(--shadow);
            overflow: hidden;
            transition: transform 0.22s, box-shadow 0.22s;
            display: flex;
            flex-direction: column;
            min-height: 420px;
            position: relative;
        }

        .movie-card-style:hover {
            transform: translateY(-7px) scale(1.025);
            box-shadow: 0 8px 36px #0006;
        }

        .movie-poster-wrapper {
            width: 100%;
            aspect-ratio: 2/3;
            overflow: hidden;
            background: #120e1e;
        }

        .movie-poster-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            border-radius: 10px 10px 0 0;
            transition: transform 0.22s;
        }

        .movie-card-style:hover .movie-poster-wrapper img {
            transform: scale(1.05);
        }

        .movie-info-box {
            flex: 1;
            padding: 16px 17px 19px 17px;
            display: flex;
            flex-direction: column;
        }

        .movie-title {
            color: #ffe45b;
            font-size: 1.18rem;
            font-weight: 700;
            margin-bottom: 10px;
            min-height: 46px;
            text-shadow: 0 2px 12px #2e194866;
        }

        .movie-meta {
            font-size: 0.99rem;
            color: #efefef;
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .badge-info {
            display: inline-block;
            background: #392b53;
            color: #ffeb3b;
            font-size: 0.85rem;
            border-radius: 6px;
            padding: 3px 9px;
            font-weight: 600;
            margin-right: 6px;
            margin-top: 2px;
        }

        .badge-age {
            background: #f44336;
            color: #fff;
            margin-left: 6px;
            padding: 3px 10px;
        }

        .btn-detail {
            background: linear-gradient(90deg, var(--accent-yellow) 60%, var(--accent-orange));
            color: #321f4d;
            border: none;
            font-weight: 600;
            border-radius: 20px;
            padding: 8px 22px;
            font-size: 1.03rem;
            margin-top: 12px;
            align-self: flex-end;
            transition: background 0.18s, color 0.18s;
            box-shadow: 0 2px 10px #ffe45b44;
        }

        .btn-detail:hover {
            background: #ff9800;
            color: #fff;
        }

        .pagination {
            margin: 38px auto 0 auto;
            display: flex;
            justify-content: center;
        }

        .pagination .page-link {
            background: #251c36;
            color: #ffe45b;
            border: none;
            border-radius: 6px;
            margin: 0 3px;
            font-weight: 500;
            transition: background 0.16s, color 0.16s;
        }

        .pagination .page-item.active .page-link,
        .pagination .page-link:hover {
            background: #ff9800;
            color: #fff;
        }

        .no-movie-notice {
            color: #ffeb3b;
            background: #1a1836;
            padding: 54px 10px;
            border-radius: 13px;
            text-align: center;
            font-size: 1.25rem;
            margin: 45px auto 0 auto;
            box-shadow: 0 2px 16px #231c3260;
        }
    </style>
@endsection

@section('content')
    <div class="category-bg">
        <div class="category-title">
            Thể loại: {{ $theLoai->ten_the_loai }}
        </div>
        @if ($phims->isEmpty())
            <div class="no-movie-notice">
                <i class="fa fa-film me-2"></i>Hiện chưa có phim nào thuộc thể loại này.
            </div>
        @else
            <div class="movie-list-grid">
                @foreach ($phims as $phim)
                    <div class="movie-card-style">
                        <div class="movie-poster-wrapper">
                            <img src="{{ asset('storage/' . $phim->poster) }}" alt="{{ $phim->ten_phim }}">
                        </div>
                        <div class="movie-info-box">
                            <div class="movie-title">
                                {{ $phim->ten_phim }}
                                @if ($phim->do_tuoi)
                                    <span class="badge-age badge-info">{{ $phim->do_tuoi }}</span>
                                @endif
                            </div>
                            <div class="movie-meta">
                                <i class="fa fa-clock"></i> {{ $phim->thoi_luong ?? '--' }} phút
                            </div>
                            <div class="movie-meta">
                                <i class="fa fa-tags"></i>
                                @foreach ($phim->theLoais as $tl)
                                    <span class="badge-info">{{ $tl->ten_the_loai }}</span>
                                @endforeach
                            </div>
                            <div class="movie-meta">
                                <i class="fa fa-globe"></i> {{ $phim->quoc_gia ?? '---' }}
                            </div>
                            <a href="{{ route('phim.chi-tiet', $phim->id) }}" class="btn btn-detail mt-auto">
                                Xem chi tiết
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-4">
                {{ $phims->links() }}
            </div>
        @endif
    </div>
@endsection
