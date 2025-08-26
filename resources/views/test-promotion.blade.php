<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Validation Khuyến Mãi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <div class="container mt-4">
        <h2>Test Validation Khuyến Mãi</h2>
        
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Test Case 1: Chi nhánh Hồ Chí Minh (dat_ve_id: 80)</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Mã khuyến mãi:</label>
                            <input type="text" class="form-control" id="promo1" value="TESTHN">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tổng tiền:</label>
                            <input type="number" class="form-control" id="total1" value="75072">
                        </div>
                        <button class="btn btn-primary" onclick="testPromo(1, 80)">Test HCM</button>
                        <div id="result1" class="mt-3"></div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Test Case 2: Chi nhánh Hà Nội (dat_ve_id: 79)</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Mã khuyến mãi:</label>
                            <input type="text" class="form-control" id="promo2" value="TESTHN">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tổng tiền:</label>
                            <input type="number" class="form-control" id="total2" value="128724">
                        </div>
                        <button class="btn btn-primary" onclick="testPromo(2, 79)">Test HN</button>
                        <div id="result2" class="mt-3"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Test Case 3: Không có dat_ve_id</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Mã khuyến mãi:</label>
                            <input type="text" class="form-control" id="promo3" value="test00">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tổng tiền:</label>
                            <input type="number" class="form-control" id="total3" value="100000">
                        </div>
                        <button class="btn btn-primary" onclick="testPromo(3, null)">Test General</button>
                        <div id="result3" class="mt-3"></div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Test Case 4: Loại sản phẩm sai</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Mã khuyến mãi:</label>
                            <input type="text" class="form-control" id="promo4" value="test00">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Loại sản phẩm:</label>
                            <select class="form-control" id="type4">
                                <option value="ve">Vé phim</option>
                                <option value="do_an">Đồ ăn</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tổng tiền:</label>
                            <input type="number" class="form-control" id="total4" value="100000">
                        </div>
                        <button class="btn btn-primary" onclick="testPromoType(4)">Test Product Type</button>
                        <div id="result4" class="mt-3"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-4">
            <h5>Log Output:</h5>
            <pre id="log-output" class="bg-light p-3" style="height: 300px; overflow-y: auto;"></pre>
        </div>
    </div>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        function testPromo(testCase, datVeId) {
            const promoCode = $(`#promo${testCase}`).val();
            const total = $(`#total${testCase}`).val();
            
            const data = {
                ma_khuyen_mai: promoCode,
                tong_tien: total,
                loai_san_pham: 've'
            };
            
            if (datVeId) {
                data.dat_ve_id = datVeId;
            }
            
            $(`#result${testCase}`).html('<div class="spinner-border spinner-border-sm"></div> Đang kiểm tra...');
            
            $.ajax({
                url: '/khuyen-mai/check-code',
                method: 'POST',
                data: data,
                success: function(response) {
                    let alertClass = response.success ? 'alert-success' : 'alert-danger';
                    $(`#result${testCase}`).html(`
                        <div class="alert ${alertClass}">
                            <strong>${response.success ? 'Thành công' : 'Thất bại'}:</strong> ${response.message}
                            ${response.success ? '<br><strong>Giảm giá:</strong> ' + number_format(response.discount) + 'đ' : ''}
                        </div>
                    `);
                    
                    appendLog(`Test Case ${testCase}: ${response.success ? 'SUCCESS' : 'FAILED'} - ${response.message}`);
                },
                error: function(xhr) {
                    $(`#result${testCase}`).html(`
                        <div class="alert alert-danger">
                            <strong>Lỗi:</strong> ${xhr.responseJSON?.message || 'Có lỗi xảy ra'}
                        </div>
                    `);
                    
                    appendLog(`Test Case ${testCase}: ERROR - ${xhr.responseJSON?.message || 'Unknown error'}`);
                }
            });
        }
        
        function testPromoType(testCase) {
            const promoCode = $(`#promo${testCase}`).val();
            const total = $(`#total${testCase}`).val();
            const type = $(`#type${testCase}`).val();
            
            const data = {
                ma_khuyen_mai: promoCode,
                tong_tien: total,
                loai_san_pham: type
            };
            
            $(`#result${testCase}`).html('<div class="spinner-border spinner-border-sm"></div> Đang kiểm tra...');
            
            $.ajax({
                url: '/khuyen-mai/check-code',
                method: 'POST',
                data: data,
                success: function(response) {
                    let alertClass = response.success ? 'alert-success' : 'alert-danger';
                    $(`#result${testCase}`).html(`
                        <div class="alert ${alertClass}">
                            <strong>${response.success ? 'Thành công' : 'Thất bại'}:</strong> ${response.message}
                            ${response.success ? '<br><strong>Giảm giá:</strong> ' + number_format(response.discount) + 'đ' : ''}
                        </div>
                    `);
                    
                    appendLog(`Test Case ${testCase} (${type}): ${response.success ? 'SUCCESS' : 'FAILED'} - ${response.message}`);
                },
                error: function(xhr) {
                    $(`#result${testCase}`).html(`
                        <div class="alert alert-danger">
                            <strong>Lỗi:</strong> ${xhr.responseJSON?.message || 'Có lỗi xảy ra'}
                        </div>
                    `);
                    
                    appendLog(`Test Case ${testCase} (${type}): ERROR - ${xhr.responseJSON?.message || 'Unknown error'}`);
                }
            });
        }
        
        function appendLog(message) {
            const now = new Date().toLocaleTimeString();
            const logOutput = $('#log-output');
            logOutput.append(`[${now}] ${message}\n`);
            logOutput.scrollTop(logOutput[0].scrollHeight);
        }
        
        function number_format(number) {
            return new Intl.NumberFormat('vi-VN').format(number);
        }
        
        // Auto test after 1 second
        setTimeout(() => {
            appendLog('=== Bắt đầu test tự động ===');
            testPromo(1, 80);
            setTimeout(() => testPromo(2, 79), 1000);
            setTimeout(() => testPromo(3, null), 2000);
            setTimeout(() => {
                $('#type4').val('do_an');
                testPromoType(4);
            }, 3000);
        }, 1000);
    </script>
</body>
</html>
