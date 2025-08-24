@extends('layouts.client')

@section('title', 'Khuyến Mãi - PolyFlix')

@section('content')
<style>
/* CSS Variables */
:root {
    --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --secondary-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    --success-gradient: linear-gradient(135deg, #00b894 0%, #00cec9 100%);
    --warning-gradient: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
    --danger-gradient: linear-gradient(135deg, #e17055 0%, #d63031 100%);
    
    --text-primary: #2d3748;
    --text-secondary: #718096;
    --text-light: #a0aec0;
    
    --bg-primary: #ffffff;
    --bg-secondary: #f7fafc;
    --bg-accent: #edf2f7;
    
    --border-radius: 12px;
    --border-radius-lg: 20px;
    --border-radius-xl: 24px;
    
    --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.08);
    --shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
    --shadow-lg: 0 8px 30px rgba(0, 0, 0, 0.15);
    --shadow-xl: 0 20px 60px rgba(0, 0, 0, 0.2);
    
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Global Styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    line-height: 1.6;
    color: var(--text-primary);
    background: radial-gradient(
        circle at top left,
        #3f2b96 0%,
        #454578 40%,
        #3b3b96 100%
    );
}

/* Hero Section */
.hero-section {
    background: linear-gradient(135deg, #3f2b96 0%, #454578 50%, #3b3b96 100%);
    color: white;
    padding: 80px 0;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.hero-content {
    position: relative;
    z-index: 2;
    max-width: 800px;
    margin: 0 auto;
    padding: 0 20px;
}

.hero-title {
    font-size: 3.5rem;
    font-weight: 800;
    margin-bottom: 20px;
    background: linear-gradient(45deg, #ffffff, #f0f8ff);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    text-shadow: 0 4px 20px rgba(0,0,0,0.3);
}

.hero-subtitle {
    font-size: 1.25rem;
    margin-bottom: 40px;
    opacity: 0.9;
    font-weight: 400;
}

/* Search Section */
.search-section {
    margin-top: -40px;
    position: relative;
    z-index: 10;
    padding: 0 20px;
}

.search-container {
    max-width: 600px;
    margin: 0 auto;
}

.search-form {
    background: rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius-xl);
    box-shadow: var(--shadow-xl);
    overflow: hidden;
    transition: var(--transition);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.search-form:hover {
    transform: translateY(-2px);
    box-shadow: 0 25px 70px rgba(0,0,0,0.2);
}

.search-input-group {
    display: flex;
    align-items: center;
    position: relative;
}

.search-icon {
    position: absolute;
    left: 24px;
    color: #a0aec0;
    font-size: 1.2rem;
    z-index: 2;
    transition: var(--transition);
}

.search-input {
    flex: 1;
    padding: 20px 24px 20px 60px;
    border: none;
    font-size: 1.1rem;
    font-weight: 500;
    color: white;
    background: transparent;
    outline: none;
}

.search-input::placeholder {
    color: #a0aec0;
    font-weight: 400;
}

.search-btn {
    padding: 20px 32px;
    background: linear-gradient(to right, #ff6600, #ffcc00);
    color: white;
    border: none;
    font-weight: 600;
    font-size: 1.1rem;
    cursor: pointer;
    transition: var(--transition);
    border-radius: 0 var(--border-radius-xl) var(--border-radius-xl) 0;
}

.search-btn:hover {
    background: linear-gradient(to right, #ff6600, #ffcc00);
    transform: scale(1.02);
}

/* Filter Tabs */
.filter-section {
    padding: 60px 20px;
    background: linear-gradient(135deg, #3f2b96 0%, #454578 50%, #3b3b96 100%);
}

.filter-container {
    max-width: 1200px;
    margin: 0 auto;
}

.section-title {
    text-align: center;
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 50px;
    color: white;
    position: relative;
}

.section-title::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 50%;
    transform: translateX(-50%);
    width: 80px;
    height: 4px;
    background: linear-gradient(to right, #ff6600, #ffcc00);
    border-radius: 2px;
}

.filter-tabs {
    margin-bottom: 40px;
}

.tab-buttons {
    display: flex;
    justify-content: center;
    gap: 16px;
    flex-wrap: wrap;
}

.tab-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 16px 24px;
    background: rgba(255, 255, 255, 0.1);
    color: #b5b5b5;
    border: 2px solid transparent;
    border-radius: var(--border-radius);
    font-weight: 600;
    font-size: 1rem;
    cursor: pointer;
    transition: var(--transition);
    text-decoration: none;
}

.tab-btn:hover {
    background: rgba(255, 255, 255, 0.2);
    color: white;
    transform: translateY(-2px);
    box-shadow: var(--shadow);
}

.tab-btn.active {
    background: linear-gradient(to right, #ff6600, #ffcc00);
    color: white;
    border-color: transparent;
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

.tab-btn i {
    font-size: 1.1rem;
}

/* Promotions Grid */
.promotions-grid {
    margin-bottom: 60px;
}

.grid-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 32px;
    transition: opacity 0.3s ease, transform 0.3s ease;
}

.grid-container.loading {
    opacity: 0.6;
    pointer-events: none;
}

/* Promotion Cards */
.promotion-card-wrapper {
    opacity: 1;
    transform: translateY(0);
    transition: opacity 0.6s ease, transform 0.6s ease;
}

.promotion-card {
    background: rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius-lg);
    overflow: hidden;
    box-shadow: var(--shadow);
    transition: var(--transition);
    position: relative;
    height: 100%;
    display: flex;
    flex-direction: column;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.promotion-card:hover {
    transform: translateY(-8px);
    box-shadow: var(--shadow-xl);
}

.discount-badge {
    position: absolute;
    top: 20px;
    right: 20px;
    background: var(--danger-gradient);
    color: white;
    padding: 12px 16px;
    border-radius: var(--border-radius);
    text-align: center;
    z-index: 5;
    box-shadow: var(--shadow);
}

.discount-value {
    display: block;
    font-size: 1.5rem;
    font-weight: 800;
    line-height: 1;
}

.discount-label {
    display: block;
    font-size: 0.75rem;
    font-weight: 600;
    opacity: 0.9;
    margin-top: 2px;
}

.card-header {
    padding: 24px 24px 0;
    display: flex;
    align-items: center;
    gap: 12px;
}

.promotion-icon {
    width: 48px;
    height: 48px;
    background: var(--secondary-gradient);
    border-radius: var(--border-radius);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
}

.promotion-type {
    font-size: 0.875rem;
    font-weight: 700;
    color: #ffcc00;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.card-content {
    padding: 20px 24px;
    flex: 1;
}

.promotion-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: white;
    margin-bottom: 12px;
    line-height: 1.3;
}

.promotion-description {
    color: #b5b5b5;
    font-size: 0.95rem;
    line-height: 1.5;
    margin-bottom: 20px;
}

.promotion-details {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.detail-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.875rem;
    color: #b5b5b5;
}

.detail-item i {
    width: 16px;
    color: var(--text-light);
}

.card-actions {
    padding: 0 24px 24px;
    display: flex;
    gap: 12px;
    align-items: center;
}

.copy-btn {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 12px 20px;
    background: linear-gradient(135deg, #e37248 0%, #fb9440 100%);
    color: white;
    border: none;
    border-radius: var(--border-radius);
    font-weight: 600;
    font-size: 0.9rem;
    cursor: pointer;
    transition: var(--transition);
}

.copy-btn:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow);
}

.copy-btn:disabled {
    opacity: 0.7;
    cursor: not-allowed;
    transform: none;
}

.detail-btn {
    width: 44px;
    height: 44px;
    background: rgba(255, 255, 255, 0.1);
    color: #b5b5b5;
    border: none;
    border-radius: var(--border-radius);
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    transition: var(--transition);
}

.detail-btn:hover {
    background: linear-gradient(to right, #ff6600, #ffcc00);
    color: white;
    transform: translateY(-2px);
}

/* Enhanced promotion card hover effects */
.promotion-card:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
    background: rgba(255, 255, 255, 0.15) !important;
}
</style>

<div class="hero-section">
    <div class="hero-content">
        <h1 class="hero-title">Tìm Kiếm Khuyến Mãi</h1>
        <p class="hero-subtitle">Sử dụng bộ lọc để tìm ưu đãi phù hợp nhất cho bạn khi xem phim cùng PolyFlix</p>
    </div>
</div>

<div class="search-section">
    <div class="search-container">
        <form method="GET" action="{{ url('/promotions') }}" class="search-form">
            <div class="search-input-group">
                <i class="fas fa-search search-icon"></i>
                <input type="text"
                       name="search"
                       class="search-input"
                       placeholder="Tìm kiếm theo tên hoặc mã khuyến mãi..."
                       value="{{ request('search') }}">
                <button type="submit" class="search-btn">
                    <i class="fas fa-search"></i>
                    Tìm kiếm
                </button>
            </div>
        </form>
    </div>
</div>

<div class="filter-section">
    <div class="filter-container">
        <h2 class="section-title">Danh Sách Khuyến Mãi</h2>

        <div class="filter-tabs">
            <form method="GET" action="{{ url('/promotions') }}" id="filter-form">
                <input type="hidden" name="search" value="{{ request('search') }}">
                <div class="tab-buttons">
                    <button type="button" class="tab-btn {{ !request('ap_dung_cho') ? 'active' : '' }}"
                            onclick="setFilter('')">
                        <i class="fas fa-star"></i>
                        Tất cả
                    </button>
                    <button type="button" class="tab-btn {{ request('ap_dung_cho') == 've' ? 'active' : '' }}"
                            onclick="setFilter('ve')">
                        <i class="fas fa-ticket-alt"></i>
                        Vé phim
                    </button>
                    <button type="button" class="tab-btn {{ request('ap_dung_cho') == 'do_an' ? 'active' : '' }}"
                            onclick="setFilter('do_an')">
                        <i class="fas fa-utensils"></i>
                        Đồ ăn
                    </button>
                    <button type="button" class="tab-btn {{ request('ap_dung_cho') == 'tat_ca' ? 'active' : '' }}"
                            onclick="setFilter('tat_ca')">
                        <i class="fas fa-gift"></i>
                        Combo
                    </button>
                </div>
                <input type="hidden" name="ap_dung_cho" id="ap_dung_cho" value="{{ request('ap_dung_cho') }}">
            </form>
        </div>

        <div class="promotions-grid">
            <div class="grid-container">
                @forelse($khuyenMais as $khuyenMai)
                    <div class="promotion-card-wrapper">
                        <div class="promotion-card">
                            <div class="discount-badge">
                                <span class="discount-value">
                                    @if($khuyenMai->loai_giam_gia === 'phan_tram')
                                        {{ $khuyenMai->gia_tri_giam }}%
                                    @else
                                        {{ number_format($khuyenMai->gia_tri_giam/1000) }}K
                                    @endif
                                </span>
                                <span class="discount-label">GIẢM</span>
                            </div>

                            <div class="card-header">
                                <div class="promotion-icon">
                                    @if($khuyenMai->ap_dung_cho === 've')
                                        <i class="fas fa-ticket-alt"></i>
                                    @elseif($khuyenMai->ap_dung_cho === 'do_an')
                                        <i class="fas fa-utensils"></i>
                                    @else
                                        <i class="fas fa-gift"></i>
                                    @endif
                                </div>
                                <div class="promotion-type">
                                    @if($khuyenMai->ap_dung_cho === 've')
                                        VÉ PHIM
                                    @elseif($khuyenMai->ap_dung_cho === 'do_an')
                                        ĐỒ ĂN
                                    @else
                                        COMBO
                                    @endif
                                </div>
                            </div>

                            <div class="card-content">
                                <h3 class="promotion-title">{{ $khuyenMai->ten }}</h3>
                                <p class="promotion-description">
                                    {{ Str::limit($khuyenMai->mo_ta, 100) }}
                                </p>

                                <div class="promotion-details">
                                    <div class="detail-item">
                                        <i class="fas fa-tag"></i>
                                        <span>{{ $khuyenMai->ma_khuyen_mai }}</span>
                                    </div>
                                    <div class="detail-item">
                                        <i class="fas fa-calendar-alt"></i>
                                        <span>{{ \Carbon\Carbon::parse($khuyenMai->ngay_ket_thuc)->format('d/m/Y') }}</span>
                                    </div>
                                    @if($khuyenMai->don_toi_thieu > 0)
                                        <div class="detail-item">
                                            <i class="fas fa-money-bill-wave"></i>
                                            <span>Đơn tối thiểu {{ number_format($khuyenMai->don_toi_thieu) }}đ</span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="card-actions">
                                <button class="copy-btn" data-code="{{ $khuyenMai->ma_khuyen_mai }}">
                                    <i class="fas fa-copy"></i>
                                    <span>Sao chép mã</span>
                                </button>
                                <a href="{{ route('client.khuyen-mai.show', $khuyenMai->id) }}" class="detail-btn">
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="fas fa-search"></i>
                        </div>
                        <h3 class="empty-title">Không tìm thấy khuyến mãi</h3>
                        <p class="empty-description">Hãy thử tìm kiếm với từ khóa khác hoặc thay đổi bộ lọc</p>
                        <a href="{{ url('/promotions') }}" class="empty-action">
                            <i class="fas fa-refresh"></i>
                            Xem tất cả khuyến mãi
                        </a>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Pagination -->
        @if($khuyenMais->hasPages())
            <div class="pagination-wrapper" style="display: flex; justify-content: center; margin-top: 40px;">
                {{ $khuyenMais->links() }}
            </div>
        @endif
    </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('js/spa-promotions.js') }}"></script>
<script>
// Test script để đảm bảo JavaScript hoạt động
console.log('🚀 View script loaded successfully');

// Backup function nếu spa-promotions.js không load
if (typeof window.setFilter === 'undefined') {
    console.warn('⚠️ setFilter function not found, creating backup...');
    window.setFilter = function(value) {
        console.log('🎯 Backup setFilter called with value:', value);

        // Clear any existing notifications
        if (typeof clearAllNotifications === 'function') {
            clearAllNotifications();
        }

        // Update form and submit smoothly
        const form = document.getElementById('filter-form');
        const hiddenInput = document.getElementById('ap_dung_cho');

        if (form && hiddenInput) {
            hiddenInput.value = value;
            form.submit();
        }
    };
}

// Test function on page load
document.addEventListener('DOMContentLoaded', function() {
    console.log('🎯 DOM loaded, testing setFilter function...');
    if (typeof window.setFilter === 'function') {
        console.log('✅ setFilter function is available');
    } else {
        console.error('❌ setFilter function is NOT available');
    }
});
</script>
@endpush
