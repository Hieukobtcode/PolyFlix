<!DOCTYPE html>
<html>
<head>
    <title>Debug TESTHN với vé 83</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <h3>Debug Validation Mã TESTHN với Đặt vé 83</h3>
    
    <button onclick="testWithDatVe83()">Test TESTHN với dat_ve_id=83 (HCM)</button>
    <button onclick="testWithoutDatVe()">Test TESTHN không có dat_ve_id</button>
    
    <div id="result" style="margin-top: 20px; padding: 10px; border: 1px solid #ccc; white-space: pre-wrap;"></div>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        function testWithDatVe83() {
            $('#result').html('Testing với dat_ve_id=83...');
            $.ajax({
                url: '/khuyen-mai/check-code',
                method: 'POST',
                data: {
                    ma_khuyen_mai: 'TESTHN',
                    tong_tien: 75072,
                    dat_ve_id: 83,
                    loai_san_pham: 've'
                },
                success: function(response) {
                    $('#result').html(`TEST VỚI DAT_VE_ID=83:
Success: ${response.success}
Message: ${response.message}
${response.success ? 'Discount: ' + response.discount + 'đ' : ''}

Raw Response:
${JSON.stringify(response, null, 2)}`);
                },
                error: function(xhr) {
                    $('#result').html(`Error: ${xhr.status} - ${xhr.responseJSON?.message || xhr.responseText}`);
                }
            });
        }
        
        function testWithoutDatVe() {
            $('#result').html('Testing không có dat_ve_id...');
            $.ajax({
                url: '/khuyen-mai/check-code',
                method: 'POST',
                data: {
                    ma_khuyen_mai: 'TESTHN',
                    tong_tien: 75072,
                    loai_san_pham: 've'
                },
                success: function(response) {
                    $('#result').html(`TEST KHÔNG CÓ DAT_VE_ID:
Success: ${response.success}
Message: ${response.message}
${response.success ? 'Discount: ' + response.discount + 'đ' : ''}

Raw Response:
${JSON.stringify(response, null, 2)}`);
                },
                error: function(xhr) {
                    $('#result').html(`Error: ${xhr.status} - ${xhr.responseJSON?.message || xhr.responseText}`);
                }
            });
        }
    </script>
</body>
</html>
