@extends('layouts.client')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<!-- Hero Section -->
<div class="demo-hero">
    <div class="hero-background">
        <div class="hero-overlay"></div>
        <div class="hero-particles"></div>
    </div>
    <div class="container">
        <div class="hero-content">
            <div class="hero-badge">
                <i class="fas fa-flask"></i>
                <span>Demo Tính Năng</span>
            </div>
            <h1 class="hero-title">
                Test Khuyến Mãi
                <span class="gradient-text">Trực Tiếp</span>
            </h1>
            <p class="hero-subtitle">
                Trải nghiệm cách thức hoạt động của hệ thống khuyến mãi PolyFlix
            </p>
        </div>
    </div>
</div>

<div class="demo-page">
    <div class="container">
        <div class="demo-grid">
            <div class="demo-main">
                <div class="demo-card">
                    <div class="demo-header">
                        <div class="header-icon">
                            <i class="fas fa-calculator"></i>
                        </div>
                        <div class="header-content">
                            <h2>Tính Toán Khuyến Mãi</h2>
                            <p>Nhập thông tin đặt vé để xem cách khuyến mãi được áp dụng</p>
                        </div>
                    </div>

                    <div class="demo-content">
                        <form id="demo-form" class="demo-form">
                            <div class="form-grid">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-chair"></i>
                                        Số ghế
                                    </label>
                                    <div class="input-wrapper">
                                        <input type="number" class="form-input" id="so-ghe" value="2" min="1" max="10">
                                        <span class="input-unit">ghế</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-ticket-alt"></i>
                                        Giá vé/ghế
                                    </label>
                                    <div class="input-wrapper">
                                        <input type="number" class="form-input" id="gia-ve" value="80000" step="1000">
                                        <span class="input-unit">đ</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-utensils"></i>
                                        Đồ ăn
                                    </label>
                                    <div class="input-wrapper">
                                        <input type="number" class="form-input" id="do-an" value="50000" step="1000">
                                        <span class="input-unit">đ</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-gift"></i>
                                        Combo
                                    </label>
                                    <div class="input-wrapper">
                                        <input type="number" class="form-input" id="combo" value="30000" step="1000">
                                        <span class="input-unit">đ</span>
                                    </div>
                                </div>
                            </div>

                            <div class="promotion-section">
                                <label class="form-label">
                                    <i class="fas fa-tags"></i>
                                    Mã Khuyến Mãi
                                </label>
                                <div class="promotion-input-group">
                                    <input type="text" class="promotion-input" id="ma-khuyen-mai"
                                           placeholder="Nhập mã khuyến mãi...">
                                    <button type="button" class="apply-btn" onclick="applyPromotion()">
                                        <i class="fas fa-check"></i>
                                        <span>Áp dụng</span>
                                    </button>
                                </div>
                                <div id="promotion-message" class="promotion-message"></div>
                            </div>

                            <div class="calculation-result">
                                <div class="result-header">
                                    <i class="fas fa-calculator"></i>
                                    <h3>Chi Tiết Thanh Toán</h3>
                                </div>
                                <div class="result-content">
                                    <div class="result-item">
                                        <span class="result-label">Tổng tiền gốc:</span>
                                        <span class="result-value" id="tong-tien-goc">0đ</span>
                                    </div>
                                    <div class="result-item discount">
                                        <span class="result-label">Giảm giá:</span>
                                        <span class="result-value" id="giam-gia">0đ</span>
                                    </div>
                                    <div class="result-divider"></div>
                                    <div class="result-item total">
                                        <span class="result-label">Tổng thanh toán:</span>
                                        <span class="result-value" id="tong-thanh-toan">0đ</span>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="demo-sidebar">
                <div class="sidebar-card">
                    <div class="sidebar-header">
                        <i class="fas fa-tags"></i>
                        <h3>Khuyến Mãi Có Sẵn</h3>
                    </div>
                    <div class="sidebar-content">
                        <div class="promotions-list">
                            @foreach($khuyenMais as $km)
                                <div class="promotion-item">
                                    <div class="promotion-badge">
                                        @if($km->loai_giam_gia == 'phan_tram')
                                            <span class="badge-value">{{ $km->gia_tri_giam }}%</span>
                                            <span class="badge-label">GIẢM</span>
                                        @else
                                            <span class="badge-value">{{ number_format($km->gia_tri_giam/1000) }}K</span>
                                            <span class="badge-label">GIẢM</span>
                                        @endif
                                    </div>
                                    <div class="promotion-content">
                                        <h4 class="promotion-name">{{ $km->ten }}</h4>
                                        <p class="promotion-desc">{{ Str::limit($km->mo_ta, 60) }}</p>
                                        <div class="promotion-code">
                                            <i class="fas fa-tag"></i>
                                            <span>{{ $km->ma_khuyen_mai }}</span>
                                        </div>
                                    </div>
                                    <button class="use-btn" onclick="usePromotion('{{ $km->ma_khuyen_mai }}')">
                                        <i class="fas fa-arrow-right"></i>
                                        <span>Sử dụng</span>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="sidebar-card">
                    <div class="sidebar-header">
                        <i class="fas fa-info-circle"></i>
                        <h3>Hướng Dẫn</h3>
                    </div>
                    <div class="sidebar-content">
                        <div class="guide-steps">
                            <div class="guide-step">
                                <div class="step-number">1</div>
                                <p>Điều chỉnh thông tin đặt vé (số ghế, giá vé, đồ ăn, combo)</p>
                            </div>
                            <div class="guide-step">
                                <div class="step-number">2</div>
                                <p>Chọn mã khuyến mãi từ danh sách hoặc nhập thủ công</p>
                            </div>
                            <div class="guide-step">
                                <div class="step-number">3</div>
                                <p>Nhấn "Áp dụng" để xem kết quả tính toán</p>
                            </div>
                            <div class="guide-step">
                                <div class="step-number">4</div>
                                <p>Xem chi tiết giảm giá và tổng thanh toán</p>
                            </div>
                        </div>
                    </div>
                </div>
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
.demo-hero {
    position: relative;
    min-height: 60vh;
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
    background: rgba(0,0,0,0.3);
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
    text-align: center;
    color: white;
}

.hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(255,255,255,0.2);
    backdrop-filter: blur(10px);
    padding: 12px 24px;
    border-radius: var(--border-radius-lg);
    font-weight: 600;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 24px;
    border: 1px solid rgba(255,255,255,0.3);
}

.hero-badge i {
    color: #4facfe;
    animation: pulse 2s infinite;
}

.hero-title {
    font-family: 'Inter', sans-serif;
    font-size: clamp(2.5rem, 5vw, 4rem);
    font-weight: 800;
    line-height: 1.2;
    margin-bottom: 24px;
}

.gradient-text {
    background: linear-gradient(45deg, #4facfe, #00f2fe, #fa709a, #fee140);
    background-size: 300% 300%;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    animation: gradientShift 3s ease-in-out infinite;
}

.hero-subtitle {
    font-size: 1.25rem;
    opacity: 0.9;
    max-width: 600px;
    margin: 0 auto;
    line-height: 1.6;
}

/* Demo Page */
.demo-page {
    padding: 0 0 80px 0;
}

.demo-grid {
    display: grid;
    grid-template-columns: 1fr 400px;
    gap: 40px;
}

.demo-main {
    min-width: 0;
}

.demo-card {
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-md);
    overflow: hidden;
}

.demo-header {
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

.header-content h2 {
    font-size: 2rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 8px;
}

.header-content p {
    color: var(--text-secondary);
    margin: 0;
    font-size: 1.1rem;
}

.demo-content {
    padding: 40px;
}

/* Form Styles */
.demo-form {
    display: flex;
    flex-direction: column;
    gap: 32px;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 24px;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.form-label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 600;
    color: var(--text-primary);
    font-size: 1rem;
}

.form-label i {
    color: #667eea;
    width: 16px;
}

.input-wrapper {
    position: relative;
    display: flex;
    align-items: center;
}

.form-input {
    flex: 1;
    padding: 16px 20px;
    border: 2px solid #e9ecef;
    border-radius: var(--border-radius-sm);
    font-size: 16px;
    font-weight: 500;
    color: var(--text-primary);
    background: white;
    transition: var(--transition);
}

.form-input:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.input-unit {
    position: absolute;
    right: 16px;
    color: var(--text-secondary);
    font-weight: 600;
    font-size: 14px;
    pointer-events: none;
}

/* Promotion Section */
.promotion-section {
    background: #f8f9fa;
    border-radius: var(--border-radius-sm);
    padding: 32px;
}

.promotion-input-group {
    display: flex;
    gap: 16px;
    margin-top: 12px;
}

.promotion-input {
    flex: 1;
    padding: 16px 20px;
    border: 2px solid #e9ecef;
    border-radius: var(--border-radius-sm);
    font-size: 16px;
    font-weight: 500;
    color: var(--text-primary);
    background: white;
    transition: var(--transition);
}

.promotion-input:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.apply-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 16px 24px;
    background: var(--success-gradient);
    color: white;
    border: none;
    border-radius: var(--border-radius-sm);
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition);
    white-space: nowrap;
}

.apply-btn:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.promotion-message {
    margin-top: 16px;
}

.promotion-message .alert {
    border: none;
    border-radius: var(--border-radius-sm);
    padding: 16px 20px;
    font-weight: 500;
}

.promotion-message .alert-success {
    background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
    color: #155724;
}

.promotion-message .alert-danger {
    background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
    color: #721c24;
}

/* Calculation Result */
.calculation-result {
    background: var(--light-gradient);
    border-radius: var(--border-radius-sm);
    overflow: hidden;
}

.result-header {
    background: var(--primary-gradient);
    color: white;
    padding: 24px;
    display: flex;
    align-items: center;
    gap: 12px;
}

.result-header i {
    font-size: 20px;
}

.result-header h3 {
    margin: 0;
    font-size: 1.3rem;
    font-weight: 600;
}

.result-content {
    padding: 24px;
}

.result-item {
    display: flex;
    justify-content: between;
    align-items: center;
    padding: 12px 0;
}

.result-label {
    font-weight: 500;
    color: var(--text-secondary);
}

.result-value {
    font-weight: 700;
    font-size: 1.1rem;
    color: var(--text-primary);
}

.result-item.discount .result-value {
    color: #28a745;
}

.result-item.total {
    border-top: 2px solid rgba(102, 126, 234, 0.2);
    padding-top: 16px;
    margin-top: 8px;
}

.result-item.total .result-label {
    font-weight: 700;
    color: var(--text-primary);
    font-size: 1.1rem;
}

.result-item.total .result-value {
    font-size: 1.5rem;
    color: #667eea;
}

.result-divider {
    height: 1px;
    background: rgba(102, 126, 234, 0.2);
    margin: 16px 0;
}

/* Sidebar */
.demo-sidebar {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.sidebar-card {
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-md);
    overflow: hidden;
}

.sidebar-header {
    background: var(--primary-gradient);
    color: white;
    padding: 24px;
    display: flex;
    align-items: center;
    gap: 12px;
}

.sidebar-header i {
    font-size: 20px;
}

.sidebar-header h3 {
    margin: 0;
    font-size: 1.2rem;
    font-weight: 600;
}

.sidebar-content {
    padding: 24px;
}

/* Promotions List */
.promotions-list {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.promotion-item {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 20px;
    background: #f8f9fa;
    border-radius: var(--border-radius-sm);
    transition: var(--transition);
}

.promotion-item:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-sm);
}

.promotion-badge {
    display: flex;
    flex-direction: column;
    align-items: center;
    background: var(--secondary-gradient);
    color: white;
    border-radius: var(--border-radius-sm);
    padding: 12px;
    min-width: 60px;
    text-align: center;
}

.badge-value {
    font-size: 1.2rem;
    font-weight: 800;
    line-height: 1;
}

.badge-label {
    font-size: 0.7rem;
    font-weight: 600;
    letter-spacing: 1px;
    opacity: 0.9;
}

.promotion-content {
    flex: 1;
    min-width: 0;
}

.promotion-name {
    font-size: 1rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 4px;
}

.promotion-desc {
    font-size: 0.9rem;
    color: var(--text-secondary);
    margin-bottom: 8px;
    line-height: 1.4;
}

.promotion-code {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.8rem;
    color: #667eea;
    font-weight: 600;
}

.use-btn {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 10px 16px;
    background: var(--success-gradient);
    color: white;
    border: none;
    border-radius: var(--border-radius-sm);
    font-weight: 600;
    font-size: 0.9rem;
    cursor: pointer;
    transition: var(--transition);
    white-space: nowrap;
}

.use-btn:hover {
    transform: scale(1.05);
}

/* Guide Steps */
.guide-steps {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.guide-step {
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

.guide-step p {
    margin: 0;
    color: var(--text-secondary);
    line-height: 1.5;
    font-size: 0.95rem;
}

/* Animations */
@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); }
}

@keyframes gradientShift {
    0%, 100% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
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

.demo-card,
.sidebar-card {
    animation: fadeInUp 0.6s ease-out;
}

.sidebar-card:nth-child(1) { animation-delay: 0.1s; }
.sidebar-card:nth-child(2) { animation-delay: 0.2s; }

/* Responsive Design */
@media (max-width: 1024px) {
    .demo-grid {
        grid-template-columns: 1fr;
        gap: 32px;
    }

    .demo-sidebar {
        order: -1;
    }
}

@media (max-width: 768px) {
    .demo-hero {
        min-height: 50vh;
    }

    .hero-title {
        font-size: 2.5rem;
    }

    .form-grid {
        grid-template-columns: 1fr;
    }

    .promotion-input-group {
        flex-direction: column;
    }

    .demo-header {
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

    .header-content h2 {
        font-size: 1.5rem;
    }
}

@media (max-width: 480px) {
    .demo-content,
    .sidebar-content {
        padding: 20px;
    }

    .demo-header {
        padding: 24px 20px;
    }

    .promotion-section,
    .calculation-result {
        margin: 0 -20px;
        border-radius: 0;
    }
}
</style>

<script>
let currentPromotion = null;

// Tính toán tổng tiền với hiệu ứng
function calculateTotal() {
    const soGhe = parseInt(document.getElementById('so-ghe').value) || 0;
    const giaVe = parseInt(document.getElementById('gia-ve').value) || 0;
    const doAn = parseInt(document.getElementById('do-an').value) || 0;
    const combo = parseInt(document.getElementById('combo').value) || 0;

    const tongTienGoc = (soGhe * giaVe) + doAn + combo;

    let giamGia = 0;
    if (currentPromotion) {
        if (currentPromotion.loai_giam_gia === 'phan_tram') {
            giamGia = (tongTienGoc * currentPromotion.gia_tri_giam) / 100;
            if (currentPromotion.giam_toi_da > 0 && giamGia > currentPromotion.giam_toi_da) {
                giamGia = currentPromotion.giam_toi_da;
            }
        } else {
            giamGia = currentPromotion.gia_tri_giam;
        }
    }

    const tongThanhToan = tongTienGoc - giamGia;

    // Animate numbers
    animateValue('tong-tien-goc', parseInt(document.getElementById('tong-tien-goc').textContent.replace(/[^\d]/g, '')) || 0, tongTienGoc, 500);
    animateValue('giam-gia', parseInt(document.getElementById('giam-gia').textContent.replace(/[^\d]/g, '')) || 0, giamGia, 500);
    animateValue('tong-thanh-toan', parseInt(document.getElementById('tong-thanh-toan').textContent.replace(/[^\d]/g, '')) || 0, tongThanhToan, 500);
}

// Animate number changes
function animateValue(elementId, start, end, duration) {
    const element = document.getElementById(elementId);
    const startTime = performance.now();

    function update(currentTime) {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);

        // Easing function
        const easeOutCubic = 1 - Math.pow(1 - progress, 3);
        const current = Math.round(start + (end - start) * easeOutCubic);

        element.textContent = formatMoney(current);

        if (progress < 1) {
            requestAnimationFrame(update);
        }
    }

    requestAnimationFrame(update);
}

// Format tiền
function formatMoney(amount) {
    return new Intl.NumberFormat('vi-VN').format(amount) + 'đ';
}

// Sử dụng mã khuyến mãi với hiệu ứng
function usePromotion(code) {
    const input = document.getElementById('ma-khuyen-mai');
    input.value = code;

    // Highlight effect
    input.style.background = 'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)';
    input.style.color = 'white';
    input.style.transform = 'scale(1.02)';

    setTimeout(() => {
        input.style.background = '';
        input.style.color = '';
        input.style.transform = '';
        applyPromotion();
    }, 300);
}

// Áp dụng khuyến mãi với loading state
function applyPromotion() {
    const code = document.getElementById('ma-khuyen-mai').value.trim();
    const messageDiv = document.getElementById('promotion-message');
    const applyBtn = document.querySelector('.apply-btn');

    if (!code) {
        currentPromotion = null;
        messageDiv.innerHTML = '';
        calculateTotal();
        return;
    }

    // Loading state
    const originalContent = applyBtn.innerHTML;
    applyBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Đang kiểm tra...</span>';
    applyBtn.disabled = true;

    const soGhe = parseInt(document.getElementById('so-ghe').value) || 0;
    const giaVe = parseInt(document.getElementById('gia-ve').value) || 0;
    const doAn = parseInt(document.getElementById('do-an').value) || 0;
    const combo = parseInt(document.getElementById('combo').value) || 0;
    const tongTien = (soGhe * giaVe) + doAn + combo;

    fetch('/api/check-promotion', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            ma_khuyen_mai: code,
            tong_tien: tongTien
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            currentPromotion = data.data;
            messageDiv.innerHTML = `<div class="alert alert-success"><i class="fas fa-check-circle"></i> ${data.message}</div>`;
            showNotification('Áp dụng khuyến mãi thành công!', 'success');
        } else {
            currentPromotion = null;
            messageDiv.innerHTML = `<div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> ${data.message}</div>`;
            showNotification('Không thể áp dụng khuyến mãi', 'error');
        }
        calculateTotal();
    })
    .catch(error => {
        console.error('Error:', error);
        messageDiv.innerHTML = '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> Có lỗi xảy ra khi kiểm tra mã khuyến mãi</div>';
        showNotification('Có lỗi xảy ra', 'error');
    })
    .finally(() => {
        applyBtn.innerHTML = originalContent;
        applyBtn.disabled = false;
    });
}

// Notification system
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

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Input change listeners with debounce
    let debounceTimer;
    ['so-ghe', 'gia-ve', 'do-an', 'combo'].forEach(id => {
        document.getElementById(id).addEventListener('input', function() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(calculateTotal, 300);
        });
    });

    // Initial calculation
    calculateTotal();

    // Add hover effects to promotion items
    document.querySelectorAll('.promotion-item').forEach(item => {
        item.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-4px) scale(1.02)';
        });

        item.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });

    // Parallax effect for hero section
    window.addEventListener('scroll', () => {
        const scrolled = window.pageYOffset;
        const hero = document.querySelector('.demo-hero');
        if (hero) {
            const rate = scrolled * -0.3;
            hero.style.transform = `translateY(${rate}px)`;
        }
    });
});
</script>
@endsection
