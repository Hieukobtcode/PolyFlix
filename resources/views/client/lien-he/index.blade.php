@extends('layouts.client')

@section('title', 'Liên hệ - PolyFlix')

@section('content')
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        line-height: 1.6;
        color: #333;
    }

    /* Hero Section */
    .hero-section {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        padding: 120px 0 80px;
        position: relative;
        overflow: hidden;
    }

    .hero-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" fill="rgba(255,255,255,0.1)"><polygon points="1000,100 1000,0 0,100"/></svg>');
        background-size: cover;
    }

    .hero-content {
        position: relative;
        z-index: 2;
        text-align: center;
        color: white;
    }

    .hero-title {
        font-size: 3.5rem;
        font-weight: 800;
        margin-bottom: 1.5rem;
        background: linear-gradient(45deg, #fff, #e3f2fd);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .hero-subtitle {
        font-size: 1.25rem;
        opacity: 0.9;
        max-width: 600px;
        margin: 0 auto;
        line-height: 1.8;
    }

    /* Main Content */
    .contact-main {
        padding: 100px 0;
        background: #f8fafc;
        position: relative;
    }

    .contact-main::before {
        content: '';
        position: absolute;
        top: -50px;
        left: 0;
        right: 0;
        height: 100px;
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        clip-path: polygon(0 0, 100% 0, 100% 50px, 0 100%);
    }

    /* Form Card */
    .form-card {
        background: white;
        border-radius: 24px;
        padding: 50px;
        box-shadow: 0 25px 50px rgba(0,0,0,0.1);
        border: 1px solid rgba(255,255,255,0.2);
        backdrop-filter: blur(10px);
        position: relative;
        overflow: hidden;
    }

    .form-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #667eea, #764ba2, #f093fb);
    }

    .form-title {
        font-size: 2rem;
        font-weight: 700;
        color: #1a202c;
        margin-bottom: 0.5rem;
    }

    .form-subtitle {
        color: #64748b;
        margin-bottom: 2rem;
        font-size: 1.1rem;
    }

    /* Form Styling */
    .form-group {
        margin-bottom: 1.5rem;
        position: relative;
    }

    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: #374151;
        font-size: 0.95rem;
    }

    .form-input {
        width: 100%;
        padding: 16px 20px;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        font-size: 16px;
        transition: all 0.3s ease;
        background: #fafafa;
        font-family: inherit;
    }

    .form-input:focus {
        outline: none;
        border-color: #667eea;
        background: white;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        transform: translateY(-1px);
    }

    .form-input.is-invalid {
        border-color: #ef4444;
        background: #fef2f2;
    }

    .invalid-feedback {
        color: #ef4444;
        font-size: 0.875rem;
        margin-top: 0.5rem;
        display: block;
    }

    .required {
        color: #ef4444;
    }

    /* Submit Button */
    .submit-btn {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 18px 40px;
        border-radius: 12px;
        font-size: 1.1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        min-width: 200px;
    }

    .submit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
    }

    .submit-btn:active {
        transform: translateY(0);
    }

    .submit-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s;
    }

    .submit-btn:hover::before {
        left: 100%;
    }

    /* Info Card */
    .info-card {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        border-radius: 24px;
        padding: 50px;
        color: white;
        position: relative;
        overflow: hidden;
        height: fit-content;
    }

    .info-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        animation: float 6s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(180deg); }
    }

    .info-content {
        position: relative;
        z-index: 2;
    }

    .info-title {
        font-size: 1.75rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
    }

    .contact-item {
        display: flex;
        align-items: flex-start;
        margin-bottom: 2rem;
        padding: 1.5rem;
        background: rgba(255,255,255,0.1);
        border-radius: 16px;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.2);
        transition: all 0.3s ease;
    }

    .contact-item:hover {
        transform: translateY(-5px);
        background: rgba(255,255,255,0.15);
    }

    .contact-icon {
        width: 50px;
        height: 50px;
        background: rgba(255,255,255,0.2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    .contact-details h5 {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .contact-details p {
        opacity: 0.9;
        margin: 0;
        line-height: 1.5;
    }

    /* Social Links */
    .social-links {
        display: flex;
        gap: 1rem;
        margin-top: 1.5rem;
    }

    .social-link {
        width: 50px;
        height: 50px;
        background: rgba(255,255,255,0.2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        text-decoration: none;
        transition: all 0.3s ease;
        font-size: 1.25rem;
    }

    .social-link:hover {
        background: rgba(255,255,255,0.3);
        transform: translateY(-3px);
        color: white;
    }

    /* Alerts */
    .alert {
        border-radius: 16px;
        border: none;
        padding: 1.5rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }

    .alert-success {
        background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
        color: white;
    }

    .alert-danger {
        background: linear-gradient(135deg, #ef4444 0%, #f87171 100%);
        color: white;
    }

    .alert::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: rgba(255,255,255,0.3);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .hero-title {
            font-size: 2.5rem;
        }

        .hero-subtitle {
            font-size: 1.1rem;
        }

        .hero-section {
            padding: 80px 0 60px;
        }

        .contact-main {
            padding: 60px 0;
        }

        .form-card, .info-card {
            padding: 30px;
            margin-bottom: 2rem;
        }

        .form-title {
            font-size: 1.75rem;
        }

        .contact-item {
            flex-direction: column;
            text-align: center;
        }

        .contact-icon {
            margin: 0 auto 1rem;
        }
    }

    /* Loading Animation */
    .loading {
        display: inline-block;
        width: 20px;
        height: 20px;
        border: 3px solid rgba(255,255,255,.3);
        border-radius: 50%;
        border-top-color: #fff;
        animation: spin 1s ease-in-out infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    /* Smooth Animations */
    .fade-in {
        animation: fadeIn 0.8s ease-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<!-- Hero Section -->
<div class="hero-section">
    <div class="container">
        <div class="hero-content fade-in">
            <h1 class="hero-title">Liên hệ với chúng tôi</h1>
            <p class="hero-subtitle">
                Chúng tôi luôn sẵn sàng lắng nghe và hỗ trợ bạn. Hãy để lại thông tin và chúng tôi sẽ phản hồi trong thời gian sớm nhất.
            </p>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="contact-main">
    <div class="container">
        <div class="row g-5">
            <!-- Contact Form -->
            <div class="col-lg-8">
                <div class="form-card fade-in">
                    <h2 class="form-title">Gửi tin nhắn</h2>
                    <p class="form-subtitle">Điền thông tin bên dưới và chúng tôi sẽ liên hệ lại với bạn sớm nhất có thể.</p>
                    
                    @if(session('success'))
                        <div class="alert alert-success">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-check-circle me-2" style="font-size: 24px;"></i>
                                <h5 class="mb-0">{{ session('success') }}</h5>
                            </div>
                            @if(session('success_detail'))
                                <p class="mb-0 ms-4">{{ session('success_detail') }}</p>
                            @endif
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    <form action="{{ route('client.lien-he.store') }}" method="POST" id="contactForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="ten" class="form-label">Họ và tên <span class="required">*</span></label>
                                    <input type="text" class="form-input @error('ten') is-invalid @enderror"
                                           id="ten" name="ten" value="{{ old('ten') }}"
                                           placeholder="Nhập họ và tên của bạn">
                                    @error('ten')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email" class="form-label">Email <span class="required">*</span></label>
                                    <input type="email" class="form-input @error('email') is-invalid @enderror"
                                           id="email" name="email" value="{{ old('email') }}"
                                           placeholder="example@email.com">
                                    @error('email')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="so_dien_thoai" class="form-label">Số điện thoại <span class="required">*</span></label>
                            <input type="tel" class="form-input @error('so_dien_thoai') is-invalid @enderror"
                                   id="so_dien_thoai" name="so_dien_thoai" value="{{ old('so_dien_thoai') }}"
                                   placeholder="0123 456 789">
                            @error('so_dien_thoai')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="noi_dung" class="form-label">Nội dung <span class="required">*</span></label>
                            <textarea class="form-input @error('noi_dung') is-invalid @enderror"
                                      id="noi_dung" name="noi_dung" rows="6"
                                      placeholder="Nhập nội dung bạn muốn gửi...">{{ old('noi_dung') }}</textarea>
                            @error('noi_dung')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <button type="submit" class="submit-btn" id="submitBtn">
                            <i class="fas fa-paper-plane me-2"></i>
                            <span class="btn-text">Gửi tin nhắn</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="col-lg-4">
                <div class="info-card fade-in">
                    <div class="info-content">
                        <h3 class="info-title">Thông tin liên hệ</h3>

                        @if($cauHinh)
                            <div class="contact-item">
                                <div class="contact-icon">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div class="contact-details">
                                    <h5>Địa chỉ</h5>
                                    <p>{{ $cauHinh->dia_chi ?? '123 Đường Công Nghệ, Q1, TP.HCM' }}</p>
                                </div>
                            </div>

                            <div class="contact-item">
                                <div class="contact-icon">
                                    <i class="fas fa-phone"></i>
                                </div>
                                <div class="contact-details">
                                    <h5>Số điện thoại</h5>
                                    <p>{{ $cauHinh->so_dien_thoai ?? '0123 456 789' }}</p>
                                </div>
                            </div>

                            <div class="contact-item">
                                <div class="contact-icon">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div class="contact-details">
                                    <h5>Email</h5>
                                    <p>{{ $cauHinh->email ?? 'lienhe@polytech.vn' }}</p>
                                </div>
                            </div>

                            <div class="contact-item">
                                <div class="contact-icon">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div class="contact-details">
                                    <h5>Thời gian làm việc</h5>
                                    <p>{{ $cauHinh->thoi_gian_lam_viec ?? 'T2 - T7: 08:00 - 17:00' }}</p>
                                </div>
                            </div>
                        @else
                            <div class="contact-item">
                                <div class="contact-icon">
                                    <i class="fas fa-info-circle"></i>
                                </div>
                                <div class="contact-details">
                                    <h5>Thông tin</h5>
                                    <p>Thông tin liên hệ đang được cập nhật...</p>
                                </div>
                            </div>
                        @endif

                        <!-- Social Media -->
                        <div style="margin-top: 2rem;">
                            <h5 style="margin-bottom: 1rem;">Theo dõi chúng tôi</h5>
                            <div class="social-links">
                                @if($cauHinh && $cauHinh->link_facebook)
                                    <a href="{{ $cauHinh->link_facebook }}" class="social-link" target="_blank">
                                        <i class="fab fa-facebook-f"></i>
                                    </a>
                                @endif
                                @if($cauHinh && $cauHinh->link_youtube)
                                    <a href="{{ $cauHinh->link_youtube }}" class="social-link" target="_blank">
                                        <i class="fab fa-youtube"></i>
                                    </a>
                                @endif
                                <a href="#" class="social-link">
                                    <i class="fab fa-instagram"></i>
                                </a>
                                <a href="#" class="social-link">
                                    <i class="fab fa-tiktok"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Smooth scroll and fade in animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    // Observe fade-in elements
    document.querySelectorAll('.fade-in').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        el.style.transition = 'opacity 0.8s ease, transform 0.8s ease';
        observer.observe(el);
    });

    // Auto-hide alerts
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            alert.style.transition = 'all 0.5s ease';
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-20px)';
            setTimeout(function() {
                alert.remove();
            }, 500);
        }, 6000);
    });

    // Enhanced form handling
    const form = document.getElementById('contactForm');
    const submitBtn = document.getElementById('submitBtn');
    const btnText = submitBtn.querySelector('.btn-text');

    if (form && submitBtn) {
        form.addEventListener('submit', function(e) {
            submitBtn.disabled = true;
            submitBtn.style.opacity = '0.8';
            btnText.innerHTML = '<span class="loading"></span> Đang gửi...';
        });
    }

    // Enhanced phone number formatting
    const phoneInput = document.getElementById('so_dien_thoai');
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 10) {
                value = value.substring(0, 10);
            }
            // Format: 0123 456 789
            if (value.length > 6) {
                value = value.replace(/(\d{4})(\d{3})(\d{3})/, '$1 $2 $3');
            } else if (value.length > 3) {
                value = value.replace(/(\d{4})(\d{3})/, '$1 $2');
            }
            e.target.value = value;
        });
    }

    // Form input animations
    const inputs = document.querySelectorAll('.form-input');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.style.transform = 'translateY(-2px)';
        });

        input.addEventListener('blur', function() {
            this.parentElement.style.transform = 'translateY(0)';
        });
    });

    // Parallax effect for hero section
    window.addEventListener('scroll', function() {
        const scrolled = window.pageYOffset;
        const heroSection = document.querySelector('.hero-section');
        if (heroSection) {
            heroSection.style.transform = `translateY(${scrolled * 0.5}px)`;
        }
    });
});
</script>
@endsection
