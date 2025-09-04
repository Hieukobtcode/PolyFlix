<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quick Test Khuyến Mãi</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <h3>Quick Test Validation</h3>
    
    <div>
        <button onclick="quickTest()">Test TESTHN với dat_ve_id 80 (HCM) - nên thất bại</button>
        <button onclick="quickTest2()">Test TESTHN với dat_ve_id 79 (HN) - nên thành công</button>
    </div>
    
    <div id="result" style="margin-top: 20px; padding: 10px; border: 1px solid #ccc;"></div>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        function quickTest() {
            $('#result').html('Testing...');
            $.ajax({
                url: '/khuyen-mai/check-code',
                method: 'POST',
                data: {
                    ma_khuyen_mai: 'TESTHN',
                    tong_tien: 75072,
                    dat_ve_id: 80,
                    loai_san_pham: 've'
                },
                success: function(response) {
                    $('#result').html(`
                        <h4>Test 1 (dat_ve_id: 80, HCM):</h4>
                        <p><strong>Kết quả:</strong> ${response.success ? 'THÀNH CÔNG' : 'THẤT BẠI'}</p>
                        <p><strong>Thông báo:</strong> ${response.message}</p>
                        ${response.success ? '<p><strong>Giảm giá:</strong> ' + response.discount + 'đ</p>' : ''}
                    `);
                },
                error: function(xhr) {
                    $('#result').html(`<p style="color: red;">Lỗi: ${xhr.responseJSON?.message || 'Unknown error'}</p>`);
                }
            });
        }
        
        function quickTest2() {
            $('#result').html('Testing...');
            $.ajax({
                url: '/khuyen-mai/check-code',
                method: 'POST',
                data: {
                    ma_khuyen_mai: 'TESTHN',
                    tong_tien: 128724,
                    dat_ve_id: 79,
                    loai_san_pham: 've'
                },
                success: function(response) {
                    $('#result').html(`
                        <h4>Test 2 (dat_ve_id: 79, HN):</h4>
                        <p><strong>Kết quả:</strong> ${response.success ? 'THÀNH CÔNG' : 'THẤT BẠI'}</p>
                        <p><strong>Thông báo:</strong> ${response.message}</p>
                        ${response.success ? '<p><strong>Giảm giá:</strong> ' + response.discount + 'đ</p>' : ''}
                    `);
                },
                error: function(xhr) {
                    $('#result').html(`<p style="color: red;">Lỗi: ${xhr.responseJSON?.message || 'Unknown error'}</p>`);
                }
            });
        }
    </script>
</body>
</html>
