@extends('layouts.admin')

@section('title', 'Thùng rác banner')

@section('content')
    <div class="container-fluid">
        <h4>Danh sách banner đã xóa</h4>
        @forelse ($banners as $banner)
            <p>{{ $banner->ten_banner }}</p>
        @empty
            <p>Không có banner nào trong thùng rác.</p>
        @endforelse
    </div>
@endsection
