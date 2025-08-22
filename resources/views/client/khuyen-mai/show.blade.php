@extends('layouts.client')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<!-- Hero Section -->
<div class="promotion-hero">
    <div class="hero-background">
        <div class="hero-overlay"></div>
        <div class="hero-particles"></div>
    </div>
    <div class="container">
        <div class="hero-content">
            <nav class="breadcrumb-nav">
                <a href="{{ route('home') }}" class="breadcrumb-link">
                    <i class="fas fa-home"></i>
                    Trang chủ
                </a>
                <i class="fas fa-chevron-right breadcrumb-separator"></i>
                <a href="{{ url('/promotions') }}" class="breadcrumb-link">
                    <i class="fas fa-tags"></i>
                    Khuyến mãi
                </a>
                <i class="fas fa-chevron-right breadcrumb-separator"></i>
                <span class="breadcrumb-current">{{ Str::limit($khuyenMai->ten, 30) }}</span>
            </nav>

            <div class="promotion-showcase">
                <div class="discount-highlight">
                    @if($khuyenMai->loai_giam_gia == 'phan_tram')
                        <span class="discount-number">{{ $khuyenMai->gia_tri_giam }}%</span>
                        <span class="discount-text">GIẢM GIÁ</span>
                    @else
                        <span class="discount-number">{{ number_format($khuyenMai->gia_tri_giam/1000) }}K</span>
                        <span class="discount-text">GIẢM GIÁ</span>
                    @endif
                </div>

                <h1 class="promotion-title">{{ $khuyenMai->ten }}</h1>

                <div class="promotion-code-display">
                    <div class="code-container">
                        <span class="code-label">MÃ KHUYẾN MÃI</span>
                        <span class="code-value">{{ $khuyenMai->ma_khuyen_mai }}</span>
                    </div>
                    <button class="copy-code-btn" data-code="{{ $khuyenMai->ma_khuyen_mai }}">
                        <i class="fas fa-copy"></i>
                        <span>Sao chép</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="promotion-detail-page">
    <div class="container">

        <div class="content-grid">
            <!-- Main Content -->
            <div class="main-content">
                <div class="promotion-details-card">
                    <div class="card-header">
                        <div class="header-icon">
                            @if($khuyenMai->ap_dung_cho == 've')
                                <i class="fas fa-ticket-alt"></i>
                            @elseif($khuyenMai->ap_dung_cho == 'do_an')
                                <i class="fas fa-utensils"></i>
                            @else
                                <i class="fas fa-gift"></i>
                            @endif
                        </div>
                        <div class="header-content">
                            <span class="promotion-category">
                                {{ $khuyenMai->ap_dung_cho == 've' ? 'VÉ PHIM' : ($khuyenMai->ap_dung_cho == 'do_an' ? 'ĐỒ ĂN' : 'COMBO') }}
                            </span>
                            <h2 class="promotion-name">{{ $khuyenMai->ten }}</h2>
                        </div>
                    </div>

                    <div class="card-content">
                        <div class="description-section">
                            <h3 class="section-title">
                                <i class="fas fa-info-circle"></i>
                                Mô tả chi tiết
                            </h3>
                            <p class="description-text">{{ $khuyenMai->mo_ta }}</p>
                        </div>

                        <div class="details-grid">
                            <div class="detail-card">
                                <div class="detail-icon">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                                <div class="detail-content">
                                    <h4>Thời gian áp dụng</h4>
                                    <p>{{ \Carbon\Carbon::parse($khuyenMai->ngay_bat_dau)->format('d/m/Y H:i') }}</p>
                                    <span class="detail-separator">đến</span>
                                    <p>{{ \Carbon\Carbon::parse($khuyenMai->ngay_ket_thuc)->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>

                            <div class="detail-card">
                                <div class="detail-icon">
                                    <i class="fas fa-money-bill-wave"></i>
                                </div>
                                <div class="detail-content">
                                    <h4>Đơn tối thiểu</h4>
                                    <p class="highlight-value">{{ number_format($khuyenMai->don_toi_thieu) }}đ</p>
                                </div>
                            </div>

                            @if($khuyenMai->giam_toi_da > 0)
                            <div class="detail-card">
                                <div class="detail-icon">
                                    <i class="fas fa-hand-holding-usd"></i>
                                </div>
                                <div class="detail-content">
                                    <h4>Giảm tối đa</h4>
                                    <p class="highlight-value">{{ number_format($khuyenMai->giam_toi_da) }}đ</p>
                                </div>
                            </div>
                            @endif

                            <div class="detail-card">
                                <div class="detail-icon">
                                    <i class="fas fa-chart-bar"></i>
                                </div>
                                <div class="detail-content">
                                    <h4>Lượt sử dụng</h4>
                                    @php
                                        $percentage = $khuyenMai->so_lan_su_dung_toi_da > 0
                                            ? ($khuyenMai->so_lan_da_su_dung / $khuyenMai->so_lan_su_dung_toi_da) * 100
                                            : 0;
                                    @endphp
                                    <div class="usage-progress">
                                        <div class="progress-bar" style="width: {{ $percentage }}%"></div>
                                    </div>
                                    <p class="usage-text">{{ $khuyenMai->so_lan_da_su_dung }}/{{ $khuyenMai->so_lan_su_dung_toi_da }} lượt</p>
                                </div>
                            </div>
                        </div>

                        <div class="action-buttons">
                            <a href="{{ route('phim.dang-chieu') }}" class="primary-btn">
                                <i class="fas fa-ticket-alt"></i>
                                <span>Đặt vé ngay</span>
                            </a>
                            <a href="{{ url('/promotions') }}" class="secondary-btn">
                                <i class="fas fa-arrow-left"></i>
                                <span>Quay lại</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- How to use -->
                <div class="info-card">
                    <div class="info-header">
                        <i class="fas fa-question-circle"></i>
                        <h3>Cách sử dụng</h3>
                    </div>
                    <div class="info-content">
                        <div class="step-list">
                            <div class="step-item">
                                <div class="step-number">1</div>
                                <div class="step-content">
                                    <p>Sao chép mã khuyến mãi</p>
                                    <span class="step-code">{{ $khuyenMai->ma_khuyen_mai }}</span>
                                </div>
                            </div>
                            <div class="step-item">
                                <div class="step-number">2</div>
                                <div class="step-content">
                                    <p>Chọn phim và suất chiếu yêu thích</p>
                                </div>
                            </div>
                            <div class="step-item">
                                <div class="step-number">3</div>
                                <div class="step-content">
                                    <p>Nhập mã vào ô "Mã giảm giá" khi thanh toán</p>
                                </div>
                            </div>
                            <div class="step-item">
                                <div class="step-number">4</div>
                                <div class="step-content">
                                    <p>Hoàn tất thanh toán và nhận ưu đãi!</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Terms -->
                <div class="info-card">
                    <div class="info-header">
                        <i class="fas fa-exclamation-triangle"></i>
                        <h3>Điều kiện áp dụng</h3>
                    </div>
                    <div class="info-content">
                        <div class="terms-list">
                            <div class="term-item">
                                <i class="fas fa-check"></i>
                                <span>Áp dụng cho: {{ ucfirst(str_replace('_', ' ', $khuyenMai->ap_dung_cho)) }}</span>
                            </div>
                            <div class="term-item">
                                <i class="fas fa-check"></i>
                                <span>Đơn tối thiểu: {{ number_format($khuyenMai->don_toi_thieu) }}đ</span>
                            </div>
                            @if($khuyenMai->giam_toi_da > 0)
                            <div class="term-item">
                                <i class="fas fa-check"></i>
                                <span>Giảm tối đa: {{ number_format($khuyenMai->giam_toi_da) }}đ</span>
                            </div>
                            @endif
                            <div class="term-item">
                                <i class="fas fa-check"></i>
                                <span>Có hiệu lực đến: {{ \Carbon\Carbon::parse($khuyenMai->ngay_ket_thuc)->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="term-item">
                                <i class="fas fa-check"></i>
                                <span>Không áp dụng cùng khuyến mãi khác</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Related Promotions -->
                @if($khuyenMaisLienQuan->count() > 0)
                <div class="info-card">
                    <div class="info-header">
                        <i class="fas fa-tags"></i>
                        <h3>Khuyến mãi khác</h3>
                    </div>
                    <div class="info-content">
                        <div class="related-promotions">
                            @foreach($khuyenMaisLienQuan as $related)
                            <div class="related-item">
                                <div class="related-content">
                                    <h4>{{ Str::limit($related->ten, 30) }}</h4>
                                    <p class="related-discount">
                                        @if($related->loai_giam_gia == 'phan_tram')
                                            Giảm {{ $related->gia_tri_giam }}%
                                        @else
                                            Giảm {{ number_format($related->gia_tri_giam) }}đ
                                        @endif
                                    </p>
                                </div>
                                <a href="{{ route('client.khuyen-mai.show', $related->id) }}" class="related-btn">
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
/* Modern CSS Variables */
:root {
    --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    --warning-gradient: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
    --dark-gradient: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
    --light-gradient: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);

    --text-primary: #2c3e50;
    --text-secondary: #7f8c8d;
    --text-light: #bdc3c7;

    --border-radius: 20px;
    --border-radius-sm: 12px;
    --border-radius-lg: 30px;

    --shadow-sm: 0 2px 10px rgba(0,0,0,0.1);
    --shadow-md: 0 8px 30px rgba(0,0,0,0.12);
    --shadow-lg: 0 20px 60px rgba(0,0,0,0.15);
    --shadow-xl: 0 30px 80px rgba(0,0,0,0.2);

    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Hero Section */
.promotion-hero {
    position: relative;
    min-height: 70vh;
    display: flex;
    align-items: center;
    overflow: hidden;
    margin-bottom: 80px;
}

.hero-background {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: var(--primary-gradient);
    z-index: 1;
}

.hero-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.4);
    z-index: 2;
}

.hero-particles {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image:
        radial-gradient(circle at 20% 80%, rgba(255,255,255,0.1) 0%, transparent 50%),
        radial-gradient(circle at 80% 20%, rgba(255,255,255,0.1) 0%, transparent 50%),
        radial-gradient(circle at 40% 40%, rgba(255,255,255,0.05) 0%, transparent 50%);
    z-index: 2;
}

.hero-content {
    position: relative;
    z-index: 3;
    color: white;
    width: 100%;
}

/* Breadcrumb */
.breadcrumb-nav {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 40px;
    flex-wrap: wrap;
}

.breadcrumb-link {
    display: flex;
    align-items: center;
    gap: 8px;
    color: rgba(255,255,255,0.8);
    text-decoration: none;
    font-weight: 500;
    transition: var(--transition);
}

.breadcrumb-link:hover {
    color: white;
    transform: translateY(-1px);
}

.breadcrumb-separator {
    color: rgba(255,255,255,0.5);
    font-size: 12px;
}

.breadcrumb-current {
    color: white;
    font-weight: 600;
}

/* Promotion Showcase */
.promotion-showcase {
    text-align: center;
    max-width: 800px;
    margin: 0 auto;
}

.discount-highlight {
    display: inline-flex;
    flex-direction: column;
    align-items: center;
    background: rgba(255,255,255,0.15);
    backdrop-filter: blur(20px);
    border: 2px solid rgba(255,255,255,0.3);
    border-radius: 50%;
    width: 150px;
    height: 150px;
    justify-content: center;
    margin-bottom: 32px;
    position: relative;
    overflow: hidden;
}

.discount-highlight::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: conic-gradient(from 0deg, transparent, rgba(255,255,255,0.1), transparent);
    animation: rotate 3s linear infinite;
}

.discount-number {
    font-size: 2.5rem;
    font-weight: 900;
    line-height: 1;
    position: relative;
    z-index: 2;
}

.discount-text {
    font-size: 0.8rem;
    font-weight: 700;
    letter-spacing: 2px;
    opacity: 0.9;
    position: relative;
    z-index: 2;
}

.promotion-title {
    font-family: 'Inter', sans-serif;
    font-size: clamp(2rem, 4vw, 3.5rem);
    font-weight: 800;
    line-height: 1.2;
    margin-bottom: 40px;
    text-shadow: 0 4px 20px rgba(0,0,0,0.3);
}

.promotion-code-display {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 20px;
    flex-wrap: wrap;
}

.code-container {
    background: rgba(255,255,255,0.1);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255,255,255,0.3);
    border-radius: var(--border-radius);
    padding: 20px 32px;
    text-align: center;
}

.code-label {
    display: block;
    font-size: 0.8rem;
    font-weight: 600;
    letter-spacing: 2px;
    opacity: 0.8;
    margin-bottom: 8px;
}

.code-value {
    display: block;
    font-family: 'Courier New', monospace;
    font-size: 1.5rem;
    font-weight: 700;
    letter-spacing: 3px;
}

.copy-code-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    background: var(--warning-gradient);
    color: white;
    border: none;
    border-radius: var(--border-radius);
    padding: 20px 32px;
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition);
    box-shadow: var(--shadow-md);
}

.copy-code-btn:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-lg);
}

/* Main Content */
.promotion-detail-page {
    padding: 0 0 80px 0;
}

.content-grid {
    display: grid;
    grid-template-columns: 1fr 350px;
    gap: 40px;
}

.main-content {
    min-width: 0;
}

.promotion-details-card {
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-md);
    overflow: hidden;
}

.card-header {
    background: var(--light-gradient);
    padding: 40px;
    display: flex;
    align-items: center;
    gap: 24px;
}

.header-icon {
    width: 80px;
    height: 80px;
    background: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: var(--shadow-md);
    flex-shrink: 0;
}

.header-icon i {
    font-size: 32px;
    color: #667eea;
}

.header-content {
    flex: 1;
}

.promotion-category {
    display: block;
    font-size: 0.8rem;
    font-weight: 700;
    letter-spacing: 2px;
    color: var(--text-secondary);
    text-transform: uppercase;
    margin-bottom: 8px;
}

.promotion-name {
    font-size: 2rem;
    font-weight: 700;
    color: var(--text-primary);
    line-height: 1.3;
    margin: 0;
}

.card-content {
    padding: 40px;
}

.section-title {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 24px;
}

.section-title i {
    color: #667eea;
}

.description-text {
    font-size: 1.1rem;
    line-height: 1.8;
    color: var(--text-secondary);
    margin-bottom: 40px;
}

/* Details Grid */
.details-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 24px;
    margin-bottom: 40px;
}

.detail-card {
    background: #f8f9fa;
    border-radius: var(--border-radius-sm);
    padding: 24px;
    display: flex;
    align-items: flex-start;
    gap: 16px;
    transition: var(--transition);
}

.detail-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-sm);
}

.detail-icon {
    width: 48px;
    height: 48px;
    background: var(--primary-gradient);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.detail-icon i {
    color: white;
    font-size: 18px;
}

.detail-content h4 {
    font-size: 1rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 8px;
}

.detail-content p {
    color: var(--text-secondary);
    margin: 0;
    font-weight: 500;
}

.highlight-value {
    color: #667eea !important;
    font-weight: 700 !important;
    font-size: 1.1rem !important;
}

.detail-separator {
    display: block;
    text-align: center;
    color: var(--text-light);
    font-size: 0.9rem;
    margin: 4px 0;
}

/* Usage Progress */
.usage-progress {
    width: 100%;
    height: 8px;
    background: #e9ecef;
    border-radius: 4px;
    overflow: hidden;
    margin: 8px 0;
}

.progress-bar {
    height: 100%;
    background: var(--success-gradient);
    border-radius: 4px;
    transition: width 0.3s ease;
}

.usage-text {
    font-size: 0.9rem;
    color: var(--text-secondary);
    margin-top: 4px;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 16px;
    flex-wrap: wrap;
}

.primary-btn, .secondary-btn {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px 32px;
    border-radius: var(--border-radius);
    font-weight: 600;
    text-decoration: none;
    transition: var(--transition);
    flex: 1;
    justify-content: center;
    min-width: 200px;
}

.primary-btn {
    background: var(--success-gradient);
    color: white;
    box-shadow: var(--shadow-md);
}

.primary-btn:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-lg);
    color: white;
}

.secondary-btn {
    background: var(--dark-gradient);
    color: white;
    box-shadow: var(--shadow-md);
}

.secondary-btn:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-lg);
    color: white;
}

/* Sidebar */
.sidebar {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.info-card {
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-md);
    overflow: hidden;
}

.info-header {
    background: var(--primary-gradient);
    color: white;
    padding: 24px;
    display: flex;
    align-items: center;
    gap: 12px;
}

.info-header i {
    font-size: 20px;
}

.info-header h3 {
    margin: 0;
    font-size: 1.2rem;
    font-weight: 600;
}

.info-content {
    padding: 24px;
}

/* Step List */
.step-list {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.step-item {
    display: flex;
    align-items: flex-start;
    gap: 16px;
}

.step-number {
    width: 32px;
    height: 32px;
    background: var(--primary-gradient);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.9rem;
    flex-shrink: 0;
}

.step-content p {
    margin: 0 0 8px 0;
    color: var(--text-primary);
    font-weight: 500;
}

.step-code {
    background: #f8f9fa;
    padding: 8px 12px;
    border-radius: 8px;
    font-family: 'Courier New', monospace;
    font-weight: 700;
    color: #667eea;
    font-size: 0.9rem;
}

/* Terms List */
.terms-list {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.term-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
}

.term-item i {
    color: #28a745;
    margin-top: 2px;
    flex-shrink: 0;
}

.term-item span {
    color: var(--text-secondary);
    line-height: 1.5;
}

/* Related Promotions */
.related-promotions {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.related-item {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px;
    background: #f8f9fa;
    border-radius: var(--border-radius-sm);
    transition: var(--transition);
}

.related-item:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-sm);
}

.related-content {
    flex: 1;
}

.related-content h4 {
    margin: 0 0 4px 0;
    font-size: 1rem;
    font-weight: 600;
    color: var(--text-primary);
}

.related-discount {
    margin: 0;
    color: var(--text-secondary);
    font-size: 0.9rem;
}

.related-btn {
    width: 40px;
    height: 40px;
    background: var(--primary-gradient);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    transition: var(--transition);
}

.related-btn:hover {
    transform: scale(1.1);
    color: white;
}

/* Animations */
@keyframes rotate {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.promotion-details-card,
.info-card {
    animation: fadeInUp 0.6s ease-out;
}

.info-card:nth-child(1) { animation-delay: 0.1s; }
.info-card:nth-child(2) { animation-delay: 0.2s; }
.info-card:nth-child(3) { animation-delay: 0.3s; }

/* Responsive Design */
@media (max-width: 1024px) {
    .content-grid {
        grid-template-columns: 1fr;
        gap: 32px;
    }

    .sidebar {
        order: -1;
    }
}

@media (max-width: 768px) {
    .promotion-hero {
        min-height: 60vh;
    }

    .discount-highlight {
        width: 120px;
        height: 120px;
    }

    .discount-number {
        font-size: 2rem;
    }

    .promotion-code-display {
        flex-direction: column;
        gap: 16px;
    }

    .code-container,
    .copy-code-btn {
        width: 100%;
    }

    .details-grid {
        grid-template-columns: 1fr;
    }

    .action-buttons {
        flex-direction: column;
    }

    .primary-btn,
    .secondary-btn {
        min-width: auto;
    }

    .card-header {
        flex-direction: column;
        text-align: center;
        gap: 16px;
    }

    .header-icon {
        width: 60px;
        height: 60px;
    }

    .header-icon i {
        font-size: 24px;
    }

    .promotion-name {
        font-size: 1.5rem;
    }
}

@media (max-width: 480px) {
    .breadcrumb-nav {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }

    .card-content,
    .info-content {
        padding: 20px;
    }

    .card-header {
        padding: 24px 20px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Copy code functionality with enhanced UX
    document.querySelector('.copy-code-btn').addEventListener('click', function() {
        const code = this.dataset.code;
        const originalContent = this.innerHTML;

        // Disable button temporarily
        this.disabled = true;

        navigator.clipboard.writeText(code).then(() => {
            // Success feedback
            this.innerHTML = '<i class="fas fa-check"></i><span>Đã sao chép!</span>';
            this.style.background = 'linear-gradient(135deg, #00b894 0%, #00cec9 100%)';

            // Create floating notification
            showNotification('Đã sao chép mã: ' + code, 'success');

            setTimeout(() => {
                this.innerHTML = originalContent;
                this.style.background = '';
                this.disabled = false;
            }, 2000);
        }).catch(err => {
            // Error feedback
            this.innerHTML = '<i class="fas fa-times"></i><span>Lỗi!</span>';
            this.style.background = 'linear-gradient(135deg, #e17055 0%, #d63031 100%)';

            showNotification('Không thể sao chép mã', 'error');

            setTimeout(() => {
                this.innerHTML = originalContent;
                this.style.background = '';
                this.disabled = false;
            }, 2000);
        });
    });

    // Parallax effect for hero section
    window.addEventListener('scroll', () => {
        const scrolled = window.pageYOffset;
        const hero = document.querySelector('.promotion-hero');
        if (hero) {
            const rate = scrolled * -0.3;
            hero.style.transform = `translateY(${rate}px)`;
        }
    });

    // Smooth scroll for action buttons
    document.querySelectorAll('.primary-btn, .secondary-btn').forEach(btn => {
        btn.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-3px) scale(1.02)';
        });

        btn.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
});

// Notification system (reuse from index page)
function showNotification(message, type = 'info') {
    // Remove existing notifications
    document.querySelectorAll('.custom-notification').forEach(n => n.remove());

    const notification = document.createElement('div');
    notification.className = `custom-notification notification-${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
            <span>${message}</span>
        </div>
    `;

    // Add styles
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${type === 'success' ? 'linear-gradient(135deg, #00b894 0%, #00cec9 100%)' :
                     type === 'error' ? 'linear-gradient(135deg, #e17055 0%, #d63031 100%)' :
                     'linear-gradient(135deg, #667eea 0%, #764ba2 100%)'};
        color: white;
        padding: 16px 24px;
        border-radius: 12px;
        box-shadow: 0 8px 30px rgba(0,0,0,0.15);
        z-index: 10000;
        transform: translateX(400px);
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.2);
    `;

    notification.querySelector('.notification-content').style.cssText = `
        display: flex;
        align-items: center;
        gap: 12px;
        font-weight: 600;
        font-size: 14px;
    `;

    document.body.appendChild(notification);

    // Animate in
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 100);

    // Auto remove
    setTimeout(() => {
        notification.style.transform = 'translateX(400px)';
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 3000);
}
</script>

@endsection
