<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Khuyến Mãi</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h1>Test Hệ Thống Khuyến Mãi</h1>
        
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Test Khuyến Mãi Vé Phim</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Mã khuyến mãi:</label>
                            <input type="text" id="promo-ve" class="form-control" placeholder="Nhập mã khuyến mãi vé phim">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tổng tiền vé:</label>
                            <input type="number" id="tong-tien-ve" class="form-control" value="150000">
                        </div>
                        <button type="button" class="btn btn-primary" onclick="testKhuyenMai('ve')">Test Vé Phim</button>
                        <div id="result-ve" class="mt-3"></div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Test Khuyến Mãi Đồ Ăn</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Mã khuyến mãi:</label>
                            <input type="text" id="promo-do-an" class="form-control" placeholder="Nhập mã khuyến mãi đồ ăn">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tổng tiền đồ ăn:</label>
                            <input type="number" id="tong-tien-do-an" class="form-control" value="200000">
                        </div>
                        <button type="button" class="btn btn-success" onclick="testKhuyenMai('do_an')">Test Đồ Ăn</button>
                        <div id="result-do-an" class="mt-3"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Danh Sách Khuyến Mãi Hiện Có</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Khuyến mãi vé phim:</h6>
                                <div id="list-ve"></div>
                            </div>
                            <div class="col-md-6">
                                <h6>Khuyến mãi đồ ăn:</h6>
                                <div id="list-do-an"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            loadPromotions();
        });

        function testKhuyenMai(loai) {
            const promoCode = $('#promo-' + (loai === 've' ? 've' : 'do-an')).val();
            const tongTien = $('#tong-tien-' + (loai === 've' ? 've' : 'do-an')).val();
            const resultDiv = $('#result-' + (loai === 've' ? 've' : 'do-an'));
            
            if (!promoCode) {
                resultDiv.html('<div class="alert alert-warning">Vui lòng nhập mã khuyến mãi</div>');
                return;
            }
            
            $.ajax({
                url: '/khuyen-mai/check-code',
                method: 'POST',
                data: {
                    ma_khuyen_mai: promoCode,
                    tong_tien: tongTien,
                    loai_san_pham: loai,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        resultDiv.html(`
                            <div class="alert alert-success">
                                <strong>✅ ${response.message}</strong><br>
                                Mã: ${response.data.ma_khuyen_mai}<br>
                                Tên: ${response.data.ten}<br>
                                Giảm giá: ${formatNumber(response.data.giam_gia)}đ<br>
                                Tổng sau giảm: ${formatNumber(response.data.tong_sau_giam)}đ
                            </div>
                        `);
                    } else {
                        resultDiv.html(`<div class="alert alert-danger"><strong>❌ ${response.message}</strong></div>`);
                    }
                },
                error: function() {
                    resultDiv.html('<div class="alert alert-danger">Có lỗi xảy ra</div>');
                }
            });
        }

        function loadPromotions() {
            // Load khuyến mãi vé phim
            $.get('/api/khuyen-mai/by-type?loai=ve', function(response) {
                if (response.success) {
                    let html = '';
                    response.data.forEach(function(km) {
                        const discount = km.loai_giam_gia === 'phan_tram' ? km.gia_tri_giam + '%' : formatNumber(km.gia_tri_giam) + 'đ';
                        html += `
                            <div class="border p-2 mb-2 rounded">
                                <strong>${km.ma_khuyen_mai}</strong> - ${km.ten}<br>
                                <small>Giảm: ${discount} | Đơn tối thiểu: ${formatNumber(km.don_toi_thieu)}đ</small>
                            </div>
                        `;
                    });
                    $('#list-ve').html(html);
                }
            });

            // Load khuyến mãi đồ ăn
            $.get('/api/khuyen-mai/by-type?loai=do_an', function(response) {
                if (response.success) {
                    let html = '';
                    response.data.forEach(function(km) {
                        const discount = km.loai_giam_gia === 'phan_tram' ? km.gia_tri_giam + '%' : formatNumber(km.gia_tri_giam) + 'đ';
                        html += `
                            <div class="border p-2 mb-2 rounded">
                                <strong>${km.ma_khuyen_mai}</strong> - ${km.ten}<br>
                                <small>Giảm: ${discount} | Đơn tối thiểu: ${formatNumber(km.don_toi_thieu)}đ</small>
                            </div>
                        `;
                    });
                    $('#list-do-an').html(html);
                }
            });
        }

        function formatNumber(num) {
            return new Intl.NumberFormat('vi-VN').format(num);
        }
    </script>
</body>
</html>
