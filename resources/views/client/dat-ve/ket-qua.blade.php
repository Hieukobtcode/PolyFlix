@extends('layouts.client')

@section('title', 'Kết quả đặt vé')

@section('styles')
    <style>
        .success-container {
            max-width: 800px;
            margin: 40px auto;
            padding: 0 20px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .success-header {
            text-align: center;
            margin-bottom: 40px;
            padding: 40px 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            color: white;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        }

        .success-icon {
            font-size: 4rem;
            margin-bottom: 20px;
            animation: bounce 1s ease-in-out;
        }

        @keyframes bounce {

            0%,
            20%,
            50%,
            80%,
            100% {
                transform: translateY(0);
            }

            40% {
                transform: translateY(-10px);
            }

            60% {
                transform: translateY(-5px);
            }
        }

        .success-title {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 10px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .success-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
            margin: 0;
        }

        .ticket-container {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            border: 1px solid #e1e5e9;
        }

        .ticket-main {
            padding: 40px;
        }

        .movie-info {
            display: flex;
            gap: 30px;
            margin-bottom: 40px;
            padding-bottom: 30px;
            border-bottom: 2px dashed #e1e5e9;
        }

        .movie-poster {
            width: 120px;
            height: 180px;
            object-fit: cover;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .movie-details h3 {
            font-size: 1.8rem;
            font-weight: bold;
            color: #2d3748;
            margin-bottom: 20px;
        }

        .detail-item {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
            font-size: 1rem;
            color: #4a5568;
        }

        .detail-item i {
            width: 20px;
            color: #667eea;
            font-size: 1.1rem;
        }

        .ticket-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }

        .detail-section {
            background: #f8fafc;
            padding: 25px;
            border-radius: 15px;
            border-left: 4px solid #667eea;
        }

        .detail-section h4 {
            font-size: 1.2rem;
            font-weight: bold;
            color: #2d3748;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .detail-section h4 i {
            color: #667eea;
        }

        .seat-list {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 10px;
        }

        .seat-item {
            background: #667eea;
            color: white;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .food-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #e2e8f0;
        }

        .food-item:last-child {
            border-bottom: none;
        }

        .price-summary {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            padding: 30px;
            margin: -40px -40px 40px -40px;
        }

        .price-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
            font-size: 1rem;
        }

        .price-row.total {
            font-size: 1.3rem;
            font-weight: bold;
            padding-top: 15px;
            border-top: 2px solid rgba(255, 255, 255, 0.3);
            margin-top: 15px;
        }

        .ticket-code {
            text-align: center;
            padding: 25px;
            background: #2d3748;
            color: white;
            margin: -40px -40px 0 -40px;
        }

        .ticket-code h4 {
            margin-bottom: 15px;
            font-size: 1.1rem;
        }

        .code-display {
            font-family: 'Courier New', monospace;
            font-size: 1.5rem;
            font-weight: bold;
            letter-spacing: 3px;
            background: rgba(255, 255, 255, 0.1);
            padding: 15px;
            border-radius: 10px;
            border: 2px dashed rgba(255, 255, 255, 0.3);
        }

        .action-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
        }

        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-secondary {
            background: #e2e8f0;
            color: #4a5568;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        @media (max-width: 768px) {
            .movie-info {
                flex-direction: column;
                text-align: center;
            }

            .ticket-details {
                grid-template-columns: 1fr;
            }

            .action-buttons {
                flex-direction: column;
            }
        }
    </style>
@endsection

@section('content')
    <div class="success-container">
        <!-- Header thành công -->
        <div class="success-header">
            <div class="success-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h1 class="success-title">Đặt vé thành công!</h1>
            <p class="success-subtitle">Cảm ơn bạn đã sử dụng dịch vụ PolyFlix. Vé của bạn đã được xác nhận.</p>
        </div>

        <!-- Nút hành động -->
        <div class="action-buttons">
            <a href="{{ route('home') }}" class="btn btn-secondary">
                <i class="fas fa-home"></i> Về trang chủ
            </a>
        </div>
    </div>
@endsection
