@extends('layouts.admin')

@section('title', 'Test Comments')
@section('page-title', 'Test Comments')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h5>Test Comments Page</h5>
        </div>
        <div class="card-body">
            <p>Trang test hoạt động bình thường!</p>
            <a href="{{ route('admin.comments.index') }}" class="btn btn-primary">
                Về trang chính
            </a>
        </div>
    </div>
</div>
@endsection
