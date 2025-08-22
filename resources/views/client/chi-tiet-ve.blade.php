@extends('layouts.client')

@section('title', 'Chi tiết vé - ' . $ve->ma_dat_ve)

@section('content')
<div class="ticket-page-wrapper">
    <div class="container">
        <!-- Main Ticket Card -->
        <div class="modern-ticket-container">
            <div class="ticket-wrapper">
                <!-- Movie Hero Section -->
                <div class="movie-hero">
                    <div class="movie-backdrop">
                        @if($ve->suatChieu->phim->poster)
                            <img src="{{ asset('storage/' . $ve->suatChieu->phim->poster) }}"
                                 alt="{{ $ve->suatChieu->phim->ten_phim }}" class="backdrop-image">
                        @endif
                        <div class="backdrop-overlay"></div>
                    </div>
                    <div class="movie-info">
                        <div class="movie-poster">
                            @if($ve->suatChieu->phim->poster)
                                <img src="{{ asset('storage/' . $ve->suatChieu->phim->poster) }}"
                                     alt="{{ $ve->suatChieu->phim->ten_phim }}">
                            @else
                                <div class="poster-placeholder">
                                    <i class="fas fa-film"></i>
                                </div>
                            @endif
                        </div>
                        <div class="movie-details">
                            <h2 class="movie-title">{{ $ve->suatChieu->phim->ten_phim }}</h2>
                            <div class="movie-meta">
                                <span class="format-badge">{{ $ve->suatChieu->phien_ban_phim }}</span>
                                <span class="age-badge">{{ $ve->suatChieu->phim->do_tuoi }}+</span>
                                <span class="duration">
                                    <i class="fas fa-clock"></i>
                                    {{ $ve->suatChieu->phim->thoi_luong }} phút
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ticket Details Grid -->
                <div class="ticket-details-grid">
                    <div class="detail-card">
                        <div class="detail-icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div class="detail-content">
                            <span class="detail-label">Ngày chiếu</span>
                            <span class="detail-value">{{ \Carbon\Carbon::parse($ve->suatChieu->ngay_bat_dau)->format('d/m/Y') }}</span>
                        </div>
                    </div>

                    <div class="detail-card">
                        <div class="detail-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="detail-content">
                            <span class="detail-label">Giờ chiếu</span>
                            <span class="detail-value">{{ \Carbon\Carbon::parse($ve->suatChieu->bat_dau)->format('H:i') }} - {{ \Carbon\Carbon::parse($ve->suatChieu->ket_thuc)->format('H:i') }}</span>
                        </div>
                    </div>

                    <div class="detail-card">
                        <div class="detail-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="detail-content">
                            <span class="detail-label">Chi nhánh</span>
                            <span class="detail-value">{{ $ve->suatChieu->phongChieu->rapPhim->chiNhanh->ten_chi_nhanh }}</span>
                            <span class="detail-sub">{{ $ve->suatChieu->phongChieu->rapPhim->chiNhanh->dia_chi }}</span>
                        </div>
                    </div>

                    <div class="detail-card">
                        <div class="detail-icon">
                            <i class="fas fa-video"></i>
                        </div>
                        <div class="detail-content">
                            <span class="detail-label">Rạp & Phòng</span>
                            <span class="detail-value">{{ $ve->suatChieu->phongChieu->rapPhim->ten_rap }}</span>
                            <span class="detail-sub">Phòng {{ $ve->suatChieu->phongChieu->ten_phong }}</span>
                        </div>
                    </div>
                </div>

                <!-- Seats Section -->
                <div class="section-card">
                    <div class="section-header">
                        <i class="fas fa-chair"></i>
                        <h3>Thông tin ghế</h3>
                    </div>
                    <div class="seats-container">
                        @foreach($ve->gheNgois as $ghe)
                            <div class="seat-card">
                                <div class="seat-visual">
                                    <i class="fas fa-chair"></i>
                                    <span class="seat-number">{{ $ghe->ma_ghe }}</span>
                                </div>
                                <div class="seat-details">
                                    <span class="seat-type {{ strtolower($ghe->loaiGhe->ten_loai_ghe) }}">
                                        {{ $ghe->loaiGhe->ten_loai_ghe }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Food & Combo Section -->
                @if($ve->combos->count() > 0 || $ve->doAns->count() > 0)
                <div class="section-card">
                    <div class="section-header">
                        <i class="fas fa-utensils"></i>
                        <h3>Combo & Đồ ăn</h3>
                    </div>
                    <div class="food-container">
                        @if($ve->combos->count() > 0)
                            <div class="food-category">
                                <h4>Combo</h4>
                                @foreach($ve->combos as $combo)
                                    <div class="food-item">
                                        <div class="food-icon">
                                            <i class="fas fa-box"></i>
                                        </div>
                                        <div class="food-info">
                                            <span class="food-name">{{ $combo->tieu_de }}</span>
                                            <span class="food-quantity">Số lượng: {{ $combo->pivot->so_luong }}</span>
                                        </div>
                                        <div class="food-price">
                                            {{ number_format($combo->gia * $combo->pivot->so_luong, 0, ',', '.') }}đ
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        @if($ve->doAns->count() > 0)
                            <div class="food-category">
                                <h4>Đồ ăn</h4>
                                @foreach($ve->doAns as $doAn)
                                    <div class="food-item">
                                        <div class="food-icon">
                                            <i class="fas fa-hamburger"></i>
                                        </div>
                                        <div class="food-info">
                                            <span class="food-name">{{ $doAn->tieu_de }}</span>
                                            <span class="food-quantity">Số lượng: {{ $doAn->pivot->so_luong }}</span>
                                        </div>
                                        <div class="food-price">
                                            {{ number_format($doAn->gia * $doAn->pivot->so_luong, 0, ',', '.') }}đ
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>
</div>

<style>
/* Modern Ticket Page Styles */
.ticket-page-wrapper {
    /* background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); */
    min-height: 100vh;
    padding: 2rem 0;
}

.status-alert {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem 1.5rem;
    border-radius: 12px;
    margin-bottom: 2rem;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.status-pending {
    background: rgba(255, 193, 7, 0.9);
    color: #856404;
}

.status-cancelled {
    background: rgba(220, 53, 69, 0.9);
    color: #721c24;
}

.status-success {
    background: rgba(40, 167, 69, 0.9);
    color: #155724;
}

.status-alert i {
    font-size: 1.5rem;
}

.status-alert strong {
    display: block;
    margin-bottom: 0.25rem;
}

.status-alert p {
    margin: 0;
    font-size: 0.9rem;
}

.page-header {
    text-align: center;
    margin-bottom: 2rem;
}

.header-content h1 {
    color: white;
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    text-shadow: 0 2px 4px rgba(0,0,0,0.3);
}

.header-content h1 i {
    margin-right: 0.5rem;
    color: #ffd700;
}

.ticket-code {
    background: rgba(255, 255, 255, 0.2);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 25px;
    font-family: 'Courier New', monospace;
    font-weight: bold;
    display: inline-block;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.3);
}

.modern-ticket-container {
    max-width: 1200px;
    margin: 0 auto;
}

.ticket-wrapper {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
    position: relative;
}

/* Movie Hero Section */
.movie-hero {
    position: relative;
    height: 300px;
    overflow: hidden;
}

.movie-backdrop {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}

.backdrop-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    filter: blur(8px);
    transform: scale(1.1);
}

.backdrop-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.8), rgba(118, 75, 162, 0.8));
}

.movie-info {
    position: relative;
    z-index: 2;
    display: flex;
    align-items: center;
    height: 100%;
    padding: 2rem;
    gap: 2rem;
}

.movie-poster {
    width: 150px;
    height: 225px;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
    flex-shrink: 0;
}

.movie-poster img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.poster-placeholder {
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 3rem;
}

.movie-details {
    color: white;
}

.movie-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
}

.movie-meta {
    display: flex;
    gap: 1rem;
    align-items: center;
    flex-wrap: wrap;
}

.format-badge, .age-badge {
    background: rgba(255, 255, 255, 0.2);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-weight: 600;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.3);
}

.duration {
    color: rgba(255, 255, 255, 0.9);
    font-weight: 500;
}

.duration i {
    margin-right: 0.5rem;
}

/* Ticket Details Grid */
.ticket-details-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    padding: 2rem;
    background: #f8f9fa;
}

.detail-card {
    background: white;
    padding: 1.5rem;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.detail-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

.detail-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
    flex-shrink: 0;
}

.detail-content {
    flex: 1;
}

.detail-label {
    display: block;
    font-size: 0.875rem;
    color: #6b7280;
    margin-bottom: 0.25rem;
    font-weight: 500;
}

.detail-value {
    display: block;
    font-size: 1rem;
    color: #1f2937;
    font-weight: 600;
    margin-bottom: 0.125rem;
}

.detail-sub {
    display: block;
    font-size: 0.75rem;
    color: #9ca3af;
}

/* Section Cards */
.section-card {
    background: white;
    margin: 1.5rem 0;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

.section-header {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    padding: 1.5rem 2rem;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.section-header i {
    font-size: 1.5rem;
}

.section-header h3 {
    margin: 0;
    font-size: 1.25rem;
    font-weight: 600;
}

/* Seats Container */
.seats-container {
    padding: 2rem;
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 1rem;
}

.seat-card {
    background: #f8f9fa;
    border: 2px solid #e9ecef;
    border-radius: 12px;
    padding: 1rem;
    text-align: center;
    transition: all 0.2s ease;
}

.seat-card:hover {
    border-color: #667eea;
    transform: translateY(-2px);
}

.seat-visual {
    margin-bottom: 0.75rem;
}

.seat-visual i {
    font-size: 2rem;
    color: #667eea;
    margin-bottom: 0.5rem;
}

.seat-number {
    display: block;
    font-weight: 700;
    font-size: 1.1rem;
    color: #1f2937;
}

.seat-details {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.seat-type {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    color: black;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
}

.seat-type.vip {
    background: #fbbf24;
    color: #92400e;
}

.seat-type.couple {
    background: #f87171;
    color: #991b1b;
}

.seat-type.standard {
    background: #9ca3af;
    color: #374151;
}

.seat-price {
    font-weight: 700;
    color: #059669;
    font-size: 0.9rem;
}

/* Food Container */
.food-container {
    padding: 2rem;
}

.food-category {
    margin-bottom: 2rem;
}

.food-category:last-child {
    margin-bottom: 0;
}

.food-category h4 {
    color: #1f2937;
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #e5e7eb;
}

.food-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: #f9fafb;
    border-radius: 8px;
    margin-bottom: 0.75rem;
    transition: background-color 0.2s ease;
}

.food-item:hover {
    background: #f3f4f6;
}

.food-icon {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1rem;
    flex-shrink: 0;
}

.food-info {
    flex: 1;
}

.food-name {
    display: block;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 0.25rem;
}

.food-quantity {
    font-size: 0.875rem;
    color: #6b7280;
}

.food-price {
    font-weight: 700;
    color: #059669;
    font-size: 1rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .ticket-page-wrapper {
        padding: 1rem 0;
    }

    .movie-info {
        flex-direction: column;
        text-align: center;
        padding: 1.5rem;
    }

    .movie-poster {
        width: 120px;
        height: 180px;
    }

    .movie-title {
        font-size: 1.75rem;
    }

    .ticket-details-grid {
        grid-template-columns: 1fr;
        padding: 1rem;
        gap: 1rem;
    }

    .seats-container {
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        padding: 1rem;
    }

    .food-container {
        padding: 1rem;
    }

    .section-header {
        padding: 1rem 1.5rem;
    }
}

@media (max-width: 480px) {
    .header-content h1 {
        font-size: 1.75rem;
    }

    .movie-title {
        font-size: 1.5rem;
    }

    .movie-meta {
        justify-content: center;
    }

    .seats-container {
        grid-template-columns: 1fr;
    }
}

/* Payment & Summary Section */
.payment-summary-section {
    padding: 2rem;
    background: #f8f9fa;
}

.payment-content {
    padding: 2rem;
}

.payment-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.payment-item {
    background: #f9fafb;
    padding: 1.5rem;
    border-radius: 12px;
    border-left: 4px solid #667eea;
}

.payment-label {
    display: block;
    font-size: 0.875rem;
    color: #6b7280;
    margin-bottom: 0.5rem;
    font-weight: 500;
}

.payment-value {
    display: block;
    font-size: 1.125rem;
    color: #1f2937;
    font-weight: 600;
}

.payment-status {
    display: inline-block;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 600;
    text-transform: uppercase;
}

.payment-status.đã-thanh-toán {
    background: #d1fae5;
    color: #065f46;
}

.payment-status.chờ-thanh-toán {
    background: #fef3c7;
    color: #92400e;
}

.payment-status.đã-hủy {
    background: #fee2e2;
    color: #991b1b;
}

.transaction-details {
    background: white;
    padding: 1.5rem;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
}

.transaction-details h4 {
    color: #1f2937;
    font-size: 1rem;
    font-weight: 600;
    margin-bottom: 1rem;
}

.transaction-grid {
    display: grid;
    gap: 1rem;
}

.transaction-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid #f3f4f6;
}

.transaction-item:last-child {
    border-bottom: none;
}

.transaction-label {
    color: #6b7280;
    font-size: 0.875rem;
}

.transaction-value {
    color: #1f2937;
    font-weight: 600;
    font-family: 'Courier New', monospace;
}

/* QR & Actions Card */
.qr-actions-card {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    height: fit-content;
}

.qr-section {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    padding: 2rem;
    text-align: center;
}

.qr-section h3 {
    margin: 0 0 1.5rem 0;
    font-size: 1.25rem;
    font-weight: 600;
}

.barcode-container {
    background: white;
    padding: 1.5rem;
    border-radius: 12px;
    margin-bottom: 1rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.qr-code-text {
    font-family: 'Courier New', monospace;
    font-weight: bold;
    font-size: 1.1rem;
    margin-bottom: 0.5rem;
    background: rgba(255, 255, 255, 0.2);
    padding: 0.5rem 1rem;
    border-radius: 20px;
    display: inline-block;
}

.qr-note {
    font-size: 0.875rem;
    opacity: 0.9;
    margin: 0;
}

.booking-details {
    padding: 1.5rem;
    border-bottom: 1px solid #e5e7eb;
}

.booking-details h4 {
    color: #1f2937;
    font-size: 1rem;
    font-weight: 600;
    margin-bottom: 1rem;
}

.booking-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid #f3f4f6;
}

.booking-item:last-child {
    border-bottom: none;
}

.booking-label {
    color: #6b7280;
    font-size: 0.875rem;
}

.booking-value {
    color: #1f2937;
    font-weight: 600;
    text-align: right;
    max-width: 60%;
    word-break: break-word;
}

/* Action Buttons */
.action-buttons {
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.action-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    padding: 0.875rem 1.5rem;
    border-radius: 10px;
    font-weight: 600;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: all 0.2s ease;
    font-size: 0.875rem;
}

.action-btn.primary {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
}

.action-btn.primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

.action-btn.secondary {
    background: #f3f4f6;
    color: #374151;
}

.action-btn.secondary:hover {
    background: #e5e7eb;
}

.action-btn.info {
    background: #dbeafe;
    color: #1e40af;
}

.action-btn.info:hover {
    background: #bfdbfe;
}

.action-btn.outline {
    background: transparent;
    color: #6b7280;
    border: 2px solid #e5e7eb;
}

.action-btn.outline:hover {
    border-color: #667eea;
    color: #667eea;
}

/* Total Section */
.total-section {
    padding: 2rem;
    background: white;
}

.total-card {
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 16px;
    padding: 2rem;
    text-align: center;
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
}

.total-content {
    color: white;
}

.total-label {
    display: block;
    font-size: 1.125rem;
    margin-bottom: 0.5rem;
    opacity: 0.9;
}

.total-amount {
    display: block;
    font-size: 2.5rem;
    font-weight: 700;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
}

/* Enhanced Responsive Styles */
@media (max-width: 992px) {
    .payment-summary-section {
        padding: 1rem;
    }

    .payment-grid {
        grid-template-columns: 1fr;
    }

    .qr-actions-card {
        margin-top: 1rem;
    }
}

@media (max-width: 768px) {
    .payment-content {
        padding: 1rem;
    }

    .qr-section {
        padding: 1.5rem;
    }

    .booking-details, .action-buttons {
        padding: 1rem;
    }

    .total-card {
        padding: 1.5rem;
    }

    .total-amount {
        font-size: 2rem;
    }

    .action-btn {
        padding: 1rem;
        font-size: 1rem;
    }
}

@media (max-width: 480px) {
    .payment-item {
        padding: 1rem;
    }

    .transaction-details {
        padding: 1rem;
    }

    .booking-value {
        max-width: 50%;
        font-size: 0.875rem;
    }

    .total-amount {
        font-size: 1.75rem;
    }

    .qr-code-text {
        font-size: 1rem;
        padding: 0.375rem 0.75rem;
    }
}

/* Print Styles */
@media print {
    .ticket-page-wrapper {
        background: white !important;
    }

    .status-alert,
    .action-buttons {
        display: none !important;
    }

    .modern-ticket-container {
        box-shadow: none !important;
    }

    .section-header {
        background: #f8f9fa !important;
        color: #1f2937 !important;
    }

    .qr-section {
        background: #f8f9fa !important;
        color: #1f2937 !important;
    }

    .total-card {
        background: #f8f9fa !important;
        color: #1f2937 !important;
    }
}
</style>

<script>
function shareTicket() {
    const ticketInfo = {
        title: 'Vé xem phim {{ $ve->suatChieu->phim->ten_phim }}',
        text: 'Tôi vừa đặt vé xem phim "{{ $ve->suatChieu->phim->ten_phim }}" tại {{ $ve->suatChieu->phongChieu->rapPhim->chiNhanh->ten_chi_nhanh }}',
        url: window.location.href
    };

    if (navigator.share) {
        navigator.share(ticketInfo)
            .then(() => console.log('Chia sẻ thành công'))
            .catch((error) => console.log('Lỗi chia sẻ:', error));
    } else {
        // Fallback cho trình duyệt không hỗ trợ Web Share API
        const shareText = `${ticketInfo.text}\n\nXem chi tiết: ${ticketInfo.url}`;

        if (navigator.clipboard) {
            navigator.clipboard.writeText(shareText).then(() => {
                alert('Đã sao chép thông tin vé vào clipboard!');
            });
        } else {
            // Fallback cho clipboard
            const textArea = document.createElement('textarea');
            textArea.value = shareText;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand('copy');
            document.body.removeChild(textArea);
            alert('Đã sao chép thông tin vé vào clipboard!');
        }
    }
}

// Thêm hiệu ứng loading cho nút tải PDF
document.addEventListener('DOMContentLoaded', function() {
    const pdfButton = document.querySelector('a[href*="print"]');
    if (pdfButton) {
        pdfButton.addEventListener('click', function() {
            const originalText = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang tạo PDF...';
            this.style.pointerEvents = 'none';

            setTimeout(() => {
                this.innerHTML = originalText;
                this.style.pointerEvents = 'auto';
            }, 3000);
        });
    }
});
</script>
@endsection
