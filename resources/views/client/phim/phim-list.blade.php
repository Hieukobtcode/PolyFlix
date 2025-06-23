@extends('layouts.client')
@section('content')
  <link href="https://fonts.googleapis.com/css2?family=Anton&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
        integrity="sha512-dYkA5Kj8SGrWJQ2r7S4JblmQo2+3ZJfzv+y5eA6TeK4kD4i2yHMyhzTKoH9yKxKdRYg3C1f58TbzOdKJejO3dg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    @vite('resources/js/trang-chu.js')

 

<!-- Menu -->
<div class="menu">
    <button type="button"></button>
    <p class="movie">PHIM</p>
    <div class="list">
        <a href="{{ route('phim.dang-chieu') }}" class="tab-item {{ $tab == 'dang-chieu' ? 'active' : '' }}">Đang chiếu</a>
        <a href="{{ route('phim.sap-chieu') }}" class="tab-item {{ $tab == 'sap-chieu' ? 'active' : '' }}">Sắp chiếu</a>
    </div>
</div>

<!-- List movie -->
<div class="list-movie">
    @foreach ($phims as $phim)
        <div class="movie">
            <div class="img-wrapper">
                <img src="{{ asset('storage/' . $phim->poster) }}" alt="{{ $phim->ten_phim }}">
                <div class="overlay">
                    <button class="btn buy"><i class="fa-solid fa-ticket"></i> Mua vé</button>
                    <button class="btn trailer"><i class="fa-solid fa-video"></i> Trailer</button>
                </div>
            </div>
            <p>{{ $phim->ten_phim }}</p>
        </div>
    @endforeach
</div>

@endsection

    
