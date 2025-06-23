@extends('layouts.client')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Anton&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-dYkA5Kj8SGrWJQ2r7S4JblmQo2+3ZJfzv+y5eA6TeK4kD4i2yHMyhzTKoH9yKxKdRYg3C1f58TbzOdKJejO3dg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

@vite('resources/js/trang-chu.js')

<div class="khuyen-mai-page">
    <h2 style="text-align: center; font-family: 'Anton', sans-serif; margin-bottom: 20px;">KHUYẾN MÃI</h2>

    <div class="row" style="display: flex; flex-wrap: wrap; justify-content: center; gap: 20px;">
        @foreach ($khuyenMais as $km)
           <div class="km-card" style="width: 500px; border: 1px solid #ddd; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 8px rgba(0,0,0,0.1); font-size: 18px;">
    {{-- <div class="km-img" style="width: 100%; height: 200px; background: #f5f5f5; display: flex; align-items: center; justify-content: center;">
                    <img src="{{ asset('khuyen-mai/c_student.png') }}" alt="{{ $km->ten }}" style="max-width: 100%; max-height: 100%;">
                </div> --}}
                <div class="km-img" style="width: 100%; height: 200px; background: #f5f5f5; display: flex; align-items: center; justify-content: center;">
    <img src="{{ asset($km->hinh_anh) }}" alt="{{ $km->ten }}" style="max-width: 100%; max-height: 100%;">
</div>

                <div class="km-content" style="padding: 15px;">
                    <h5 style="font-weight: bold;">{{ $km->ten }}</h5>
                    <p style="font-size: 14px; color: white;">{{ $km->mo_ta }}</p>
                    <p style="margin: 5px 0;">
                        Giảm: 
                        <span style="color: #d9534f; font-weight: bold;">
                            {{ $km->loai_giam_gia == 'phan_tram' ? $km->gia_tri_giam . '%' : number_format($km->gia_tri_giam) . 'đ' }}
                        </span>
                    </p>
                    <p style="margin: 5px 0;">Áp dụng: <strong>{{ strtoupper($km->ap_dung_cho) }}</strong></p>
                    <p style="margin: 5px 0;">Hết hạn: <strong>{{ \Carbon\Carbon::parse($km->ngay_ket_thuc)->format('d/m/Y') }}</strong></p>
                    <div style="margin-top: 10px;">
                        <button class="btn btn-danger w-100" style="background-color: #ff5757; border: none;">
                            <i class="fa-solid fa-ticket"></i> Sử dụng ngay
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div style="margin-top: 20px; display: flex; justify-content: center;">
        {{ $khuyenMais->links() }}
    </div>
</div>

@endsection
