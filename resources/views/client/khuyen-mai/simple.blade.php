@extends('layouts.client')

@section('title', 'Khuyến Mãi - PolyFlix')

@section('content')
<div style="padding: 20px; background: #f5f5f5; min-height: 100vh;">
    <div style="max-width: 1200px; margin: 0 auto;">
        <h1 style="text-align: center; margin-bottom: 30px; color: #333;">🎯 Danh Sách Khuyến Mãi</h1>
        
        <!-- Debug Info -->
        <div style="background: #d1ecf1; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            <h3>🔍 Debug Info:</h3>
            <p><strong>Tổng số khuyến mãi:</strong> {{ $khuyenMais->total() }}</p>
            <p><strong>Khuyến mãi hiện tại:</strong> {{ $khuyenMais->count() }}</p>
            <p><strong>Trang hiện tại:</strong> {{ $khuyenMais->currentPage() }} / {{ $khuyenMais->lastPage() }}</p>
        </div>

        <!-- Search Form -->
        <div style="background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <form method="GET" action="{{ route('client.khuyen-mai.index') }}" style="display: flex; gap: 15px; align-items: center; flex-wrap: wrap;">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm kiếm khuyến mãi..." 
                       style="flex: 1; min-width: 200px; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                
                <select name="ap_dung_cho" style="padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                    <option value="">Tất cả</option>
                    <option value="ve" {{ request('ap_dung_cho') === 've' ? 'selected' : '' }}>Vé phim</option>
                    <option value="do_an" {{ request('ap_dung_cho') === 'do_an' ? 'selected' : '' }}>Đồ ăn</option>
                    <option value="tat_ca" {{ request('ap_dung_cho') === 'tat_ca' ? 'selected' : '' }}>Tất cả</option>
                </select>
                
                <button type="submit" style="padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer;">
                    Tìm kiếm
                </button>
            </form>
        </div>

        <!-- Filter Buttons -->
        <div style="background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <h3 style="margin-bottom: 15px;">Lọc theo loại:</h3>
            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                <a href="{{ route('client.khuyen-mai.index') }}" 
                   style="padding: 10px 20px; background: {{ !request('ap_dung_cho') ? '#007bff' : '#6c757d' }}; color: white; text-decoration: none; border-radius: 4px;">
                    Tất cả
                </a>
                <a href="{{ route('client.khuyen-mai.index', ['ap_dung_cho' => 've']) }}" 
                   style="padding: 10px 20px; background: {{ request('ap_dung_cho') === 've' ? '#007bff' : '#6c757d' }}; color: white; text-decoration: none; border-radius: 4px;">
                    Vé phim
                </a>
                <a href="{{ route('client.khuyen-mai.index', ['ap_dung_cho' => 'do_an']) }}" 
                   style="padding: 10px 20px; background: {{ request('ap_dung_cho') === 'do_an' ? '#007bff' : '#6c757d' }}; color: white; text-decoration: none; border-radius: 4px;">
                    Đồ ăn
                </a>
                <a href="{{ route('client.khuyen-mai.index', ['ap_dung_cho' => 'tat_ca']) }}" 
                   style="padding: 10px 20px; background: {{ request('ap_dung_cho') === 'tat_ca' ? '#007bff' : '#6c757d' }}; color: white; text-decoration: none; border-radius: 4px;">
                    Combo
                </a>
            </div>
        </div>

        <!-- Promotions List -->
        @if($khuyenMais->count() > 0)
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; margin-bottom: 30px;">
                @foreach($khuyenMais as $khuyenMai)
                    <div style="background: white; border-radius: 8px; padding: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); border-left: 4px solid #007bff;">
                        <div style="display: flex; justify-content: between; align-items: start; margin-bottom: 15px;">
                            <h3 style="margin: 0; color: #333; flex: 1;">{{ $khuyenMai->ten }}</h3>
                            <span style="background: #007bff; color: white; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold;">
                                {{ strtoupper($khuyenMai->ma_khuyen_mai) }}
                            </span>
                        </div>
                        
                        <div style="margin-bottom: 15px;">
                            <div style="font-size: 24px; font-weight: bold; color: #28a745; margin-bottom: 5px;">
                                @if($khuyenMai->loai_giam_gia === 'phan_tram')
                                    Giảm {{ $khuyenMai->gia_tri_giam }}%
                                    @if($khuyenMai->giam_toi_da > 0)
                                        <span style="font-size: 14px; color: #666;">(tối đa {{ number_format($khuyenMai->giam_toi_da) }}đ)</span>
                                    @endif
                                @else
                                    Giảm {{ number_format($khuyenMai->gia_tri_giam) }}đ
                                @endif
                            </div>
                            <div style="color: #666; font-size: 14px;">
                                Áp dụng cho: 
                                @switch($khuyenMai->ap_dung_cho)
                                    @case('ve')
                                        <span style="color: #007bff;">🎬 Vé phim</span>
                                        @break
                                    @case('do_an')
                                        <span style="color: #fd7e14;">🍿 Đồ ăn</span>
                                        @break
                                    @case('tat_ca')
                                        <span style="color: #28a745;">🎯 Tất cả</span>
                                        @break
                                    @default
                                        <span>{{ $khuyenMai->ap_dung_cho }}</span>
                                @endswitch
                            </div>
                        </div>
                        
                        <div style="background: #f8f9fa; padding: 10px; border-radius: 4px; margin-bottom: 15px;">
                            <p style="margin: 0; color: #666; font-size: 14px;">{{ $khuyenMai->mo_ta }}</p>
                        </div>
                        
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 15px; font-size: 12px;">
                            <div>
                                <strong>Đơn tối thiểu:</strong><br>
                                <span style="color: #007bff;">{{ number_format($khuyenMai->don_toi_thieu) }}đ</span>
                            </div>
                            <div>
                                <strong>Còn lại:</strong><br>
                                <span style="color: #28a745;">{{ $khuyenMai->so_lan_su_dung_toi_da - $khuyenMai->so_lan_da_su_dung }} lượt</span>
                            </div>
                        </div>
                        
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 15px; font-size: 12px;">
                            <div>
                                <strong>Bắt đầu:</strong><br>
                                <span>{{ $khuyenMai->ngay_bat_dau->format('d/m/Y') }}</span>
                            </div>
                            <div>
                                <strong>Kết thúc:</strong><br>
                                <span>{{ $khuyenMai->ngay_ket_thuc->format('d/m/Y') }}</span>
                            </div>
                        </div>
                        
                        <div style="text-align: center;">
                            <button onclick="copyCode('{{ $khuyenMai->ma_khuyen_mai }}')" 
                                    style="width: 100%; padding: 10px; background: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">
                                📋 Sao chép mã: {{ $khuyenMai->ma_khuyen_mai }}
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div style="text-align: center; padding: 60px 20px; background: white; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                <div style="font-size: 48px; margin-bottom: 20px;">😔</div>
                <h3 style="color: #666; margin-bottom: 10px;">Không có khuyến mãi nào</h3>
                <p style="color: #999;">Hiện tại không có khuyến mãi nào thỏa mãn điều kiện tìm kiếm.</p>
                <a href="{{ route('client.khuyen-mai.index') }}" 
                   style="display: inline-block; margin-top: 20px; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 4px;">
                    Xem tất cả khuyến mãi
                </a>
            </div>
        @endif

        <!-- Pagination -->
        @if($khuyenMais->hasPages())
            <div style="display: flex; justify-content: center; margin-top: 30px;">
                {{ $khuyenMais->links() }}
            </div>
        @endif
    </div>
</div>

<script>
function copyCode(code) {
    navigator.clipboard.writeText(code).then(function() {
        alert('Đã sao chép mã: ' + code);
    }).catch(function() {
        // Fallback for older browsers
        const textArea = document.createElement('textarea');
        textArea.value = code;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        alert('Đã sao chép mã: ' + code);
    });
}
</script>
@endsection
