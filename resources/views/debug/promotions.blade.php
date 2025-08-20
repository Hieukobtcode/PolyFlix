<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Debug Promotions</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background: #f5f5f5;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .promotion-card {
            border: 1px solid #ddd;
            margin: 10px 0;
            padding: 15px;
            border-radius: 8px;
            background: #f9f9f9;
        }
        .promotion-title {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }
        .promotion-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 10px;
            margin-bottom: 10px;
        }
        .info-item {
            background: white;
            padding: 8px;
            border-radius: 4px;
            border-left: 3px solid #007bff;
        }
        .info-label {
            font-weight: bold;
            color: #666;
            font-size: 12px;
            text-transform: uppercase;
        }
        .info-value {
            color: #333;
            font-size: 14px;
        }
        .status-active {
            color: #28a745;
            font-weight: bold;
        }
        .status-inactive {
            color: #dc3545;
            font-weight: bold;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #007bff;
        }
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
        }
        .stat-number {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .stat-label {
            font-size: 12px;
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎯 Debug Promotions View</h1>
            <p>Kiểm tra dữ liệu khuyến mãi được truyền vào view</p>
        </div>

        <div class="stats">
            <div class="stat-card">
                <div class="stat-number">{{ $khuyenMais->total() }}</div>
                <div class="stat-label">Tổng số khuyến mãi</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $khuyenMais->count() }}</div>
                <div class="stat-label">Khuyến mãi hiện tại</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $khuyenMais->currentPage() }}</div>
                <div class="stat-label">Trang hiện tại</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $khuyenMais->lastPage() }}</div>
                <div class="stat-label">Tổng số trang</div>
            </div>
        </div>

        @if($khuyenMais->count() > 0)
            <h2>📋 Danh sách khuyến mãi:</h2>
            @foreach($khuyenMais as $khuyenMai)
                <div class="promotion-card">
                    <div class="promotion-title">{{ $khuyenMai->ten }}</div>
                    <div class="promotion-info">
                        <div class="info-item">
                            <div class="info-label">Mã khuyến mãi</div>
                            <div class="info-value">{{ $khuyenMai->ma_khuyen_mai }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Loại giảm giá</div>
                            <div class="info-value">{{ $khuyenMai->loai_giam_gia }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Giá trị giảm</div>
                            <div class="info-value">
                                @if($khuyenMai->loai_giam_gia === 'phan_tram')
                                    {{ $khuyenMai->gia_tri_giam }}%
                                @else
                                    {{ number_format($khuyenMai->gia_tri_giam) }}đ
                                @endif
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Áp dụng cho</div>
                            <div class="info-value">{{ $khuyenMai->ap_dung_cho }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Ngày bắt đầu</div>
                            <div class="info-value">{{ $khuyenMai->ngay_bat_dau->format('d/m/Y H:i') }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Ngày kết thúc</div>
                            <div class="info-value">{{ $khuyenMai->ngay_ket_thuc->format('d/m/Y H:i') }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Trạng thái</div>
                            <div class="info-value {{ $khuyenMai->trang_thai === 'hoat_dong' ? 'status-active' : 'status-inactive' }}">
                                {{ $khuyenMai->trang_thai }}
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Đã sử dụng / Tối đa</div>
                            <div class="info-value">{{ $khuyenMai->so_lan_da_su_dung }} / {{ $khuyenMai->so_lan_su_dung_toi_da }}</div>
                        </div>
                    </div>
                    <div style="margin-top: 10px; padding: 10px; background: #e9ecef; border-radius: 4px;">
                        <strong>Mô tả:</strong> {{ $khuyenMai->mo_ta }}
                    </div>
                </div>
            @endforeach

            @if($khuyenMais->hasPages())
                <div style="margin-top: 30px; text-align: center;">
                    {{ $khuyenMais->links() }}
                </div>
            @endif
        @else
            <div style="text-align: center; padding: 40px; background: #fff3cd; border: 1px solid #ffeaa7; border-radius: 8px;">
                <h3>⚠️ Không có khuyến mãi nào</h3>
                <p>Không tìm thấy khuyến mãi nào thỏa mãn điều kiện.</p>
            </div>
        @endif

        <div style="margin-top: 30px; padding: 20px; background: #d1ecf1; border-radius: 8px;">
            <h3>🔍 Debug Info:</h3>
            <ul>
                <li><strong>Current Time:</strong> {{ now()->format('d/m/Y H:i:s') }}</li>
                <li><strong>Request URL:</strong> {{ request()->fullUrl() }}</li>
                <li><strong>Query Parameters:</strong> {{ json_encode(request()->query()) }}</li>
                <li><strong>Pagination Info:</strong> 
                    Current: {{ $khuyenMais->currentPage() }}, 
                    Total: {{ $khuyenMais->total() }}, 
                    Per Page: {{ $khuyenMais->perPage() }}
                </li>
            </ul>
        </div>
    </div>
</body>
</html>
