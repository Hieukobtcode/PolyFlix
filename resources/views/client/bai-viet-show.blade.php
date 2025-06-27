@extends('layouts.client')

@section('title', $baiViet->tieu_de)

@section('content')
    <style>
        button {
            position: relative;
            width: 110px;
            height: 50px;
            border-radius: 10px;
            background-color: #663399;
            font-family: "Poppins", sans-serif;
            color: white;
            font-weight: bold;
            overflow: hidden;
            border: none;
            cursor: pointer;
            z-index: 1;
            transition: color 0.4s ease;
            font-size: 18px;
        }

        button::before {
            content: "";
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(to right, #ff6600, #ffcc00);
            z-index: -1;
            transition: left 0.4s ease;
        }

        button:hover::before {
            left: 0;
        }

        button:hover {
            color: #fff;
        }

        .bai-viet-wrapper {
            width: 80%;
            margin: 70px auto;
            padding: 0 15px;
        }

        .bai-viet-wrapper h1 {
            font-size: 28px;
            font-weight: bold;
            color: #fffefe;
            margin-bottom: 15px;
        }

        .bai-viet-noi-dung {
            line-height: 1.8;
            font-size: 16px;
            color: #ffffff;
            word-wrap: break-word;
            white-space: normal;
        }


        .bai-viet-buttons {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .bai-viet-buttons button {
            background-color: #5c50ff;
            color: white;
            padding: 6px 14px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }

        .bai-viet-buttons .views {
            background: #eaeaea;
            color: #ffffff;
        }

        .bai-viet-wrapper img.main-image {
            width: 100%;
            height: auto;
            border-radius: 8px;
            margin: 20px 0;
            object-fit: cover;
        }

        .bai-viet-noi-dung {
            line-height: 1.8;
            font-size: 16px;
            color: #ffffff;
        }

        .bai-viet-noi-dung em {
            color: #ffffff;
            font-style: italic;
        }

        .bai-viet-noi-dung strong {
            color: #fffdfd;
        }

        .img-main {
            display: block;
            margin: 10px auto;
            width: 900px;
            margin-top: 50px;
            margin-bottom: 50px;
        }

        .bai-viet-noi-dung img:not(.img-main) {
            max-width: 800px;
            width: 100%;
            height: auto;
            display: block;
            margin: 10px auto;
        }
    </style>

    <div class="bai-viet-wrapper">
        {{-- Tiêu đề --}}
        <h1>{{ $baiViet->tieu_de }}</h1>


        {{-- Hình ảnh chính --}}
        @if ($baiViet->hinh_anh)
            <img class="img-main" src="{{ asset('storage/' . $baiViet->hinh_anh) }}" alt="{{ $baiViet->tieu_de }}"
                class="main-image">
        @endif

        {{-- Nội dung bài viết --}}
        <div class="bai-viet-noi-dung">
            {!! $baiViet->noi_dung !!}
        </div>
        <a href="{{ route('client.bai-viet') }}">
            <button>Quay lại</button>
        </a>
    </div>
@endsection
