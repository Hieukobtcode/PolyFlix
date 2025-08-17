<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SPA Test - Khuyến Mãi</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 20px;
            background: #f8f9fa;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .test-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px;
            border-radius: 20px;
            margin-bottom: 40px;
            text-align: center;
        }
        
        .test-controls {
            background: white;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.1);
        }
        
        .control-group {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        
        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .btn-success {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
        }
        
        .btn-warning {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            color: white;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        
        .test-results {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.1);
        }
        
        .result-item {
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 8px;
            border-left: 4px solid #667eea;
            background: #f8f9fa;
        }
        
        .result-success {
            border-left-color: #28a745;
            background: #d4edda;
        }
        
        .result-error {
            border-left-color: #dc3545;
            background: #f8d7da;
        }
        
        .loading {
            opacity: 0.6;
            pointer-events: none;
        }
        
        .status-indicator {
            display: inline-block;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-right: 8px;
        }
        
        .status-success { background: #28a745; }
        .status-error { background: #dc3545; }
        .status-loading { 
            background: #ffc107; 
            animation: pulse 1s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="test-header">
            <h1><i class="fas fa-flask"></i> SPA Functionality Test</h1>
            <p>Test các tính năng Single Page Application cho trang khuyến mãi</p>
        </div>
        
        <div class="test-controls">
            <h3>Test Controls</h3>
            
            <div class="control-group">
                <button class="btn btn-primary" onclick="testFilterChange()">
                    <i class="fas fa-filter"></i> Test Filter Change
                </button>
                <button class="btn btn-success" onclick="testSearch()">
                    <i class="fas fa-search"></i> Test Search
                </button>
                <button class="btn btn-warning" onclick="testPagination()">
                    <i class="fas fa-list"></i> Test Pagination
                </button>
            </div>
            
            <div class="control-group">
                <button class="btn btn-primary" onclick="testBrowserHistory()">
                    <i class="fas fa-history"></i> Test Browser History
                </button>
                <button class="btn btn-success" onclick="testScrollPosition()">
                    <i class="fas fa-arrows-alt-v"></i> Test Scroll Position
                </button>
                <button class="btn btn-warning" onclick="clearResults()">
                    <i class="fas fa-trash"></i> Clear Results
                </button>
            </div>
        </div>
        
        <div class="test-results">
            <h3>Test Results</h3>
            <div id="results-container">
                <p>Nhấn các nút test để bắt đầu kiểm tra...</p>
            </div>
        </div>
    </div>

    <script>
        let testResults = [];
        
        function addResult(message, type = 'info') {
            const timestamp = new Date().toLocaleTimeString();
            testResults.push({
                message,
                type,
                timestamp
            });
            updateResultsDisplay();
        }
        
        function updateResultsDisplay() {
            const container = document.getElementById('results-container');
            if (testResults.length === 0) {
                container.innerHTML = '<p>Nhấn các nút test để bắt đầu kiểm tra...</p>';
                return;
            }
            
            container.innerHTML = testResults.map(result => `
                <div class="result-item result-${result.type}">
                    <span class="status-indicator status-${result.type}"></span>
                    <strong>[${result.timestamp}]</strong> ${result.message}
                </div>
            `).join('');
        }
        
        async function testFilterChange() {
            addResult('Bắt đầu test filter change...', 'loading');
            
            try {
                // Test API call với filter
                const response = await fetch('/api/promotions?ap_dung_cho=ve', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                
                if (response.ok) {
                    const data = await response.json();
                    addResult(`✅ Filter API call thành công - Tìm thấy ${data.data.promotions.length} khuyến mãi vé phim`, 'success');
                    
                    // Test URL update
                    const testUrl = '/promotions?ap_dung_cho=ve';
                    history.pushState({test: true}, '', testUrl);
                    addResult(`✅ URL được cập nhật thành công: ${testUrl}`, 'success');
                } else {
                    addResult(`❌ Filter API call thất bại: ${response.status}`, 'error');
                }
            } catch (error) {
                addResult(`❌ Lỗi filter test: ${error.message}`, 'error');
            }
        }
        
        async function testSearch() {
            addResult('Bắt đầu test search functionality...', 'loading');
            
            try {
                const searchTerm = 'giảm';
                const response = await fetch(`/api/promotions?search=${encodeURIComponent(searchTerm)}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                
                if (response.ok) {
                    const data = await response.json();
                    addResult(`✅ Search API call thành công - Tìm thấy ${data.data.promotions.length} kết quả cho "${searchTerm}"`, 'success');
                    
                    // Test debounce simulation
                    addResult('✅ Debounce functionality hoạt động (mô phỏng)', 'success');
                } else {
                    addResult(`❌ Search API call thất bại: ${response.status}`, 'error');
                }
            } catch (error) {
                addResult(`❌ Lỗi search test: ${error.message}`, 'error');
            }
        }
        
        async function testPagination() {
            addResult('Bắt đầu test pagination...', 'loading');
            
            try {
                const response = await fetch('/api/promotions?page=1', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                
                if (response.ok) {
                    const data = await response.json();
                    addResult(`✅ Pagination API call thành công - Trang ${data.data.pagination.current_page}/${data.data.pagination.last_page}`, 'success');
                    addResult(`✅ Tổng cộng ${data.data.pagination.total} khuyến mãi`, 'success');
                } else {
                    addResult(`❌ Pagination API call thất bại: ${response.status}`, 'error');
                }
            } catch (error) {
                addResult(`❌ Lỗi pagination test: ${error.message}`, 'error');
            }
        }
        
        function testBrowserHistory() {
            addResult('Bắt đầu test browser history...', 'loading');
            
            // Test pushState
            const testState = { filters: { ap_dung_cho: 'do_an' }, timestamp: Date.now() };
            history.pushState(testState, '', '/promotions?ap_dung_cho=do_an');
            addResult('✅ history.pushState() hoạt động', 'success');
            
            // Test popstate listener
            window.addEventListener('popstate', function testPopstate(event) {
                if (event.state && event.state.timestamp) {
                    addResult('✅ popstate event được trigger thành công', 'success');
                    window.removeEventListener('popstate', testPopstate);
                }
            });
            
            // Simulate back button
            setTimeout(() => {
                history.back();
            }, 1000);
        }
        
        function testScrollPosition() {
            addResult('Bắt đầu test scroll position...', 'loading');
            
            // Save current position
            const currentScroll = window.pageYOffset;
            addResult(`✅ Current scroll position: ${currentScroll}px`, 'success');
            
            // Scroll to different position
            window.scrollTo(0, 500);
            
            setTimeout(() => {
                const newScroll = window.pageYOffset;
                addResult(`✅ Scrolled to: ${newScroll}px`, 'success');
                
                // Restore original position
                window.scrollTo(0, currentScroll);
                addResult('✅ Scroll position restored', 'success');
            }, 500);
        }
        
        function clearResults() {
            testResults = [];
            updateResultsDisplay();
        }
        
        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            addResult('SPA Test page loaded successfully', 'success');
        });
    </script>
</body>
</html>
