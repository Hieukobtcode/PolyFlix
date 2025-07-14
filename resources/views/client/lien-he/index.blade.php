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
        font-family: 'Inter', 'Segoe UI', -apple-system, BlinkMacSystemFont, sans-serif;
        line-height: 1.6;
        color: #1f2937;
        overflow-x: hidden;
    }

    /* Particle Background */
    #particles-js {
        position: absolute;
        width: 100%;
        height: 100%;
        z-index: 1;
    }

    /* Hero Section */
    .hero-section {
        min-height: 60vh;
        background: radial-gradient(circle at top left, #3f2b96 0%, #454578 40%, #3b3b96 100%);
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .hero-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.2);
        z-index: 2;
    }

    .hero-section::after {
        content: '';
        position: absolute;
        bottom: -1px;
        left: 0;
        right: 0;
        height: 50px;
        background: #f8fafc;
        clip-path: polygon(0 30px, 100% 0, 100% 100%, 0 100%);
        z-index: 3;
    }

    .hero-content {
        position: relative;
        z-index: 4;
        text-align: center;
        color: white;
        max-width: 800px;
        padding: 0 20px;
    }

    .hero-title {
        font-size: clamp(2.5rem, 5vw, 4rem);
        font-weight: 800;
        margin-bottom: 1.5rem;
        background: linear-gradient(135deg, #ffffff 0%, #f0f9ff 50%, #e0f2fe 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        animation: titleGlow 3s ease-in-out infinite alternate;
    }

    @keyframes titleGlow {
        0% {
            filter: drop-shadow(0 0 10px rgba(255, 255, 255, 0.3));
        }

        100% {
            filter: drop-shadow(0 0 20px rgba(255, 255, 255, 0.6));
        }
    }

    .hero-subtitle {
        font-size: clamp(1.1rem, 2.5vw, 1.4rem);
        opacity: 0.95;
        line-height: 1.8;
        font-weight: 300;
        letter-spacing: 0.5px;
    }

    /* Main Content */
    .contact-main {
        background: #f8fafc;
        padding: 80px 0;
        position: relative;
    }

    .contact-main::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background:
            radial-gradient(circle at 20% 80%, rgba(63, 43, 150, .05) 0%, transparent 50%),
            radial-gradient(circle at 80% 20%, rgba(69, 69, 120, .05) 0%, transparent 50%);
        pointer-events: none;
    }

    .container-custom {
        width: 80%;
        max-width: 1280px;
        min-width: 1024px;
        margin: 0 auto;
        padding: 0 16px;
        position: relative;
        z-index: 2;
    }

    /* Glass Morphism Cards */
    .glass-card {
        background: rgba(255, 255, 255, 0.25);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 24px;
        box-shadow:
            0 25px 45px rgba(0, 0, 0, 0.1),
            inset 0 1px 0 rgba(255, 255, 255, 0.3);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
        position: relative;
    }

    .glass-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 2px;
        background: linear-gradient(90deg, #667eea, #764ba2, #f093fb);
        opacity: 0.8;
    }

    .glass-card:hover {
        transform: translateY(-8px);
        box-shadow:
            0 35px 60px rgba(0, 0, 0, 0.15),
            inset 0 1px 0 rgba(255, 255, 255, 0.4);
        border-color: rgba(255, 255, 255, 0.4);
    }

    /* Form Card */
    .form-card {
        padding: 60px;
        margin-bottom: 40px;
    }

    .form-header {
        text-align: center;
        margin-bottom: 50px;
    }

    .form-title {
        font-size: 2.5rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 0.5rem;
        background: linear-gradient(135deg, #1f2937 0%, #4f46e5 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .form-subtitle {
        color: #6b7280;
        font-size: 1.1rem;
        font-weight: 400;
        line-height: 1.6;
    }

    /* Enhanced Form Styling */
    .form-group {
        margin-bottom: 32px;
        position: relative;
    }

    .form-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #374151;
        font-size: 0.95rem;
        letter-spacing: 0.3px;
    }

    .form-input {
        width: 100%;
        padding: 20px 24px;
        border: 2px solid rgba(229, 231, 235, 0.6);
        border-radius: 16px;
        font-size: 16px;
        font-family: inherit;
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .form-input:focus {
        outline: none;
        border-color: #667eea;
        background: rgba(255, 255, 255, 0.95);
        box-shadow:
            0 0 0 4px rgba(102, 126, 234, 0.1),
            0 10px 15px -3px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }

    .form-input.is-invalid {
        border-color: #ef4444;
        background: rgba(254, 242, 242, 0.8);
        animation: shake 0.5s ease-in-out;
    }

    @keyframes shake {

        0%,
        100% {
            transform: translateX(0);
        }

        25% {
            transform: translateX(-5px);
        }

        75% {
            transform: translateX(5px);
        }
    }

    .invalid-feedback {
        color: #ef4444;
        font-size: 0.875rem;
        margin-top: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .required {
        color: #ef4444;
        font-weight: 600;
    }

    /* Enhanced Submit Button */
    .submit-btn {
        background: linear-gradient(135deg, #3f2b96 0%, #454578 100%);
        color: white;
        border: none;
        padding: 20px 48px;
        border-radius: 16px;
        font-size: 1.1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        min-width: 220px;
        box-shadow: 0 10px 25px rgba(63, 43, 150, 0.4);
    }

    .submit-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 20px 40px rgba(63, 43, 150, 0.5);
        background: linear-gradient(135deg, #2a1a6b 0%, #3f2b96 100%);
    }

    .submit-btn:active {
        transform: translateY(-1px);
    }

    .submit-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        transition: left 0.6s;
    }

    .submit-btn:hover::before {
        left: 100%;
    }

    /* Info Card */
    .info-card {
        padding: 60px;
        background: linear-gradient(135deg,
                rgba(102, 126, 234, 0.1) 0%,
                rgba(118, 75, 162, 0.1) 100%);
        border: 1px solid rgba(102, 126, 234, 0.2);
        height: fit-content;
        position: sticky;
        top: 100px;
    }

    .info-title {
        font-size: 2rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 40px;
        text-align: center;
        background: linear-gradient(135deg, #1f2937 0%, #667eea 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .contact-item {
        display: flex;
        align-items: flex-start;
        margin-bottom: 32px;
        padding: 24px;
        background: rgba(255, 255, 255, 0.4);
        border-radius: 20px;
        border: 1px solid rgba(255, 255, 255, 0.3);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
    }

    .contact-item:hover {
        transform: translateY(-5px);
        background: rgba(255, 255, 255, 0.6);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
    }

    .contact-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #3f2b96 0%, #454578 100%);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 20px;
        font-size: 1.4rem;
        color: white;
        flex-shrink: 0;
        box-shadow: 0 8px 16px rgba(63, 43, 150, 0.3);
        transition: all 0.3s ease;
    }

    .contact-item:hover .contact-icon {
        transform: scale(1.1);
        box-shadow: 0 12px 24px rgba(63, 43, 150, 0.4);
    }

    .contact-details h5 {
        font-size: 1.2rem;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 8px;
    }

    .contact-details p {
        color: #6b7280;
        margin: 0;
        line-height: 1.6;
        font-size: 0.95rem;
    }

    /* Map Section */
    .map-section {
        margin-top: 40px;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .map-container {
        height: 300px;
        width: 100%;
        position: relative;
        background: linear-gradient(45deg, #f3f4f6, #e5e7eb);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6b7280;
        font-size: 1.1rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .map-container:hover {
        background: linear-gradient(45deg, #e5e7eb, #d1d5db);
    }

    /* Social Links */
    .social-section {
        text-align: center;
        margin-top: 40px;
        padding: 30px;
        background: rgba(255, 255, 255, 0.3);
        border-radius: 20px;
        border: 1px solid rgba(255, 255, 255, 0.4);
    }

    .social-title {
        font-size: 1.3rem;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 20px;
    }

    .social-links {
        display: flex;
        justify-content: center;
        gap: 16px;
        flex-wrap: wrap;
    }

    .social-link {
        width: 56px;
        height: 56px;
        background: linear-gradient(135deg, #3f2b96 0%, #454578 100%);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        text-decoration: none;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        font-size: 1.3rem;
        box-shadow: 0 8px 16px rgba(63, 43, 150, 0.3);
    }

    .social-link:hover {
        transform: translateY(-5px) scale(1.1);
        color: white;
        box-shadow: 0 15px 30px rgba(63, 43, 150, 0.4);
        background: linear-gradient(135deg, #2a1a6b 0%, #3f2b96 100%);
    }

    /* Enhanced Alerts */
    .alert {
        border-radius: 20px;
        border: none;
        padding: 24px;
        margin-bottom: 32px;
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        animation: slideInDown 0.6s ease;
        position: relative;
        overflow: hidden;
    }

    @keyframes slideInDown {
        from {
            opacity: 0;
            transform: translateY(-30px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .alert-success {
        background: rgba(16, 185, 129, 0.9);
        color: white;
        border: 1px solid rgba(16, 185, 129, 0.3);
    }

    .alert-danger {
        background: rgba(239, 68, 68, 0.9);
        color: white;
        border: 1px solid rgba(239, 68, 68, 0.3);
    }

    .alert::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: rgba(255, 255, 255, 0.4);
    }

    /* Loading Animation */
    .loading {
        display: inline-block;
        width: 20px;
        height: 20px;
        border: 3px solid rgba(255, 255, 255, .3);
        border-radius: 50%;
        border-top-color: #fff;
        animation: spin 1s ease-in-out infinite;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }

    /* Responsive Design */
    @media (max-width: 1024px) {
        .hero-title {
            font-size: 3rem;
        }

        .form-card,
        .info-card {
            padding: 40px;
        }

        .info-card {
            position: static;
            margin-top: 40px;
        }
    }

    @media (max-width: 768px) {
        .hero-section {
            min-height: 60vh;
        }

        .contact-main {
            padding: 80px 0;
        }

        .form-card,
        .info-card {
            padding: 30px;
            margin-bottom: 30px;
        }

        .form-title {
            font-size: 2rem;
        }

        .contact-item {
            flex-direction: column;
            text-align: center;
            padding: 20px;
        }

        .contact-icon {
            margin: 0 auto 16px;
        }

        .social-links {
            gap: 12px;
        }

        .social-link {
            width: 48px;
            height: 48px;
            font-size: 1.1rem;
        }
    }

    @media (max-width: 480px) {
        .container-custom {
            padding: 0 16px;
        }

        .form-card,
        .info-card {
            padding: 24px;
        }

        .form-input {
            padding: 16px 20px;
        }

        .submit-btn {
            padding: 16px 32px;
            min-width: 180px;
        }
    }

    /* Smooth scroll behavior */
    html {
        scroll-behavior: smooth;
    }

    /* Selection styling */
    ::selection {
        background: rgba(102, 126, 234, 0.3);
        color: #1f2937;
    }

    /* Custom scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
    }

    ::-webkit-scrollbar-track {
        background: #f1f5f9;
    }

    ::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(135deg, #5a67d8 0%, #667eea 100%);
    }

    /* Bootstrap Grid System for Layout */
    .row {
        display: flex;
        flex-wrap: wrap;
        margin: 0 -15px;
    }

    .col-lg-8 {
        flex: 0 0 66.666667%;
        max-width: 66.666667%;
        padding: 0 15px;
    }

    .col-lg-4 {
        flex: 0 0 33.333333%;
        max-width: 33.333333%;
        padding: 0 15px;
    }

    .col-md-6 {
        flex: 0 0 50%;
        max-width: 50%;
        padding: 0 15px;
    }

    @media (max-width: 992px) {

        .col-lg-8,
        .col-lg-4 {
            flex: 0 0 100%;
            max-width: 100%;
        }
    }

    @media (max-width: 768px) {
        .col-md-6 {
            flex: 0 0 100%;
            max-width: 100%;
        }
    }

    /* Utility Classes */
    .text-center {
        text-align: center;
    }

    .d-flex {
        display: flex;
    }

    .align-items-center {
        align-items: center;
    }

    .mb-0 {
        margin-bottom: 0;
    }

    .mb-2 {
        margin-bottom: 0.5rem;
    }

    .mt-2 {
        margin-top: 0.5rem;
    }

    .me-2 {
        margin-right: 0.5rem;
    }

    .me-3 {
        margin-right: 1rem;
    }

    .ms-4 {
        margin-left: 1.5rem;
    }
</style>

<!-- Hero Section -->
<div class="hero-section">
    <div id="particles-js"></div>
    <div class="hero-content">
        <h1 class="hero-title">Liên hệ với chúng tôi</h1>
        <p class="hero-subtitle">
            Chúng tôi luôn sẵn sàng lắng nghe và hỗ trợ bạn. Hãy để lại thông tin và chúng tôi sẽ phản hồi trong thời gian sớm nhất có thể.
        </p>
    </div>
</div>

<!-- Main Content -->
<div class="contact-main">
    <div class="container-custom">
        <div class="row">
            <!-- Contact Form -->
            <div class="col-lg-8">
                <div class="glass-card form-card">
                    <div class="form-header">
                        <h2 class="form-title">Gửi tin nhắn</h2>
                        <p class="form-subtitle">Điền thông tin bên dưới và chúng tôi sẽ liên hệ lại với bạn sớm nhất có thể.</p>
                    </div>

                    @if(session('success'))
                    <div class="alert alert-success">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-check-circle me-3" style="font-size: 24px;"></i>
                            <h5 class="mb-0">{{ session('success') }}</h5>
                        </div>
                        @if(session('success_detail'))
                        <p class="mb-0 ms-4 mt-2">{{ session('success_detail') }}</p>
                        @endif
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="alert alert-danger">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-exclamation-circle me-3" style="font-size: 24px;"></i>
                            <span>{{ session('error') }}</span>
                        </div>
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
                                    <span class="invalid-feedback">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        {{ $message }}
                                    </span>
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
                                    <span class="invalid-feedback">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        {{ $message }}
                                    </span>
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
                            <span class="invalid-feedback">
                                <i class="fas fa-exclamation-triangle"></i>
                                {{ $message }}
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="noi_dung" class="form-label">Nội dung <span class="required">*</span></label>
                            <textarea class="form-input @error('noi_dung') is-invalid @enderror"
                                id="noi_dung" name="noi_dung" rows="6"
                                placeholder="Nhập nội dung bạn muốn gửi...">{{ old('noi_dung') }}</textarea>
                            @error('noi_dung')
                            <span class="invalid-feedback">
                                <i class="fas fa-exclamation-triangle"></i>
                                {{ $message }}
                            </span>
                            @enderror
                        </div>

                        <div class="text-center">
                            <button type="submit" class="submit-btn" id="submitBtn">
                                <i class="fas fa-paper-plane me-2"></i>
                                <span class="btn-text">Gửi tin nhắn</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="col-lg-4">
                <div class="glass-card info-card">
                    <h3 class="info-title">Thông tin liên hệ</h3>

                    @if($cauHinh ?? null)
                    <div class="contact-item" onclick="openMap()">
                        <div class="contact-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="contact-details">
                            <h5>Địa chỉ</h5>
                            <p>{{ $cauHinh->dia_chi ?? '123 Đường Công Nghệ, Q1, TP.HCM' }}</p>
                        </div>
                    </div>

                    <div class="contact-item" onclick="callPhone('{{ $cauHinh->so_dien_thoai ?? '0123456789' }}')">
                        <div class="contact-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div class="contact-details">
                            <h5>Số điện thoại</h5>
                            <p>{{ $cauHinh->so_dien_thoai ?? '0123 456 789' }}</p>
                        </div>
                    </div>

                    <div class="contact-item" onclick="sendEmail('{{ $cauHinh->email ?? 'lienhe@polytech.vn' }}')">
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
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="contact-details">
                            <h5>Địa chỉ</h5>
                            <p>123 Đường Công Nghệ, Q1, TP.HCM</p>
                        </div>
                    </div>

                    <div class="contact-item" onclick="callPhone('0123456789')">
                        <div class="contact-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div class="contact-details">
                            <h5>Số điện thoại</h5>
                            <p>0123 456 789</p>
                        </div>
                    </div>

                    <div class="contact-item" onclick="sendEmail('lienhe@polytech.vn')">
                        <div class="contact-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="contact-details">
                            <h5>Email</h5>
                            <p>lienhe@polytech.vn</p>
                        </div>
                    </div>

                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="contact-details">
                            <h5>Thời gian làm việc</h5>
                            <p>T2 - T7: 08:00 - 17:00</p>
                        </div>
                    </div>
                    @endif

                    <!-- Social Media -->
                    <div class="social-section">
                        <h5 class="social-title">Theo dõi chúng tôi</h5>
                        <div class="social-links">
                            @if(($cauHinh->link_facebook ?? null))
                            <a href="{{ $cauHinh->link_facebook }}" class="social-link" target="_blank" title="Facebook">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            @endif
                            @if(($cauHinh->link_youtube ?? null))
                            <a href="{{ $cauHinh->link_youtube }}" class="social-link" target="_blank" title="YouTube">
                                <i class="fab fa-youtube"></i>
                            </a>
                            @endif
                            <a href="#" class="social-link" title="Instagram">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="#" class="social-link" title="TikTok">
                                <i class="fab fa-tiktok"></i>
                            </a>
                            <a href="#" class="social-link" title="Twitter">
                                <i class="fab fa-twitter"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Map Section -->
                    <div class="map-section">
                        <div class="map-container" id="mapContainer">
                            <div>
                                <i class="fas fa-map-marked-alt" style="font-size: 2rem; margin-bottom: 1rem; color: #667eea;"></i>
                                <br>
                                <span>Nhấp để xem bản đồ</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Particles.js
        if (window.particlesJS) {
            particlesJS('particles-js', {
                particles: {
                    number: {
                        value: 50,
                        density: {
                            enable: true,
                            value_area: 800
                        }
                    },
                    color: {
                        value: '#ffffff'
                    },
                    shape: {
                        type: 'circle'
                    },
                    opacity: {
                        value: 0.5,
                        random: true
                    },
                    size: {
                        value: 3,
                        random: true
                    },
                    line_linked: {
                        enable: true,
                        distance: 150,
                        color: '#ffffff',
                        opacity: 0.2,
                        width: 1
                    },
                    move: {
                        enable: true,
                        speed: 2,
                        direction: 'none',
                        random: false,
                        straight: false,
                        out_mode: 'out'
                    }
                },
                interactivity: {
                    detect_on: 'canvas',
                    events: {
                        onhover: {
                            enable: true,
                            mode: 'grab'
                        },
                        onclick: {
                            enable: true,
                            mode: 'push'
                        }
                    },
                    modes: {
                        grab: {
                            distance: 140,
                            line_linked: {
                                opacity: 0.5
                            }
                        },
                        push: {
                            particles_nb: 4
                        }
                    }
                },
                retina_detect: true
            });
        }

        // Intersection Observer for animations
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

        // Observe elements for animation
        document.querySelectorAll('.glass-card, .contact-item').forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(30px)';
            el.style.transition = 'opacity 0.8s ease, transform 0.8s ease';
            observer.observe(el);
        });

        // Auto-hide alerts with animation
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            setTimeout(function() {
                alert.style.transition = 'all 0.5s ease';
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-20px)';
                setTimeout(function() {
                    alert.remove();
                }, 500);
            }, 8000);
        });

        // Enhanced form handling
        const form = document.getElementById('contactForm');
        const submitBtn = document.getElementById('submitBtn');
        const btnText = submitBtn?.querySelector('.btn-text');

        if (form && submitBtn) {
            form.addEventListener('submit', function(e) {
                submitBtn.disabled = true;
                submitBtn.style.opacity = '0.8';
                if (btnText) {
                    btnText.innerHTML = '<span class="loading"></span> Đang gửi...';
                }
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

            // Add typing effect
            input.addEventListener('input', function() {
                if (this.value) {
                    this.style.background = 'rgba(255, 255, 255, 0.95)';
                } else {
                    this.style.background = 'rgba(255, 255, 255, 0.8)';
                }
            });
        });

        // Parallax effect for hero section
        let ticking = false;

        function updateParallax() {
            const scrolled = window.pageYOffset;
            const heroSection = document.querySelector('.hero-section');
            if (heroSection) {
                heroSection.style.transform = `translateY(${scrolled * 0.3}px)`;
            }
            ticking = false;
        }

        window.addEventListener('scroll', function() {
            if (!ticking) {
                requestAnimationFrame(updateParallax);
                ticking = true;
            }
        });

        // Smooth scroll to form when clicking CTA
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    });

    // Contact interaction functions
    function callPhone(phone) {
        window.location.href = `tel:${phone.replace(/\s/g, '')}`;
    }

    function sendEmail(email) {
        window.location.href = `mailto:${email}`;
    }

    function openMap() {
        // You can replace this with actual Google Maps link or coordinates
        const mapUrl = 'https://maps.google.com/?q=PolyFlix+Cinema';
        window.open(mapUrl, '_blank');
    }

    // Map click handler
    const mapContainer = document.getElementById('mapContainer');
    if (mapContainer) {
        mapContainer.addEventListener('click', function() {
            // You can integrate real Google Maps here
            const iframe = document.createElement('iframe');
            iframe.src = 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.467675210835!2d106.69832631462258!3d10.776889162084328!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752f4b3328eb2f%3A0x33c1813c5e068d0e!2zVHLGsOG7nW5nIMSQ4bqhaSBo4buNYyBGUFQgVFAuSENNIC0gVHLGsOG7nW5nIMSQ4bqhaSBo4buNYyBGUFQgVUktSVQgVFAuSENNIC0gxJHhuqFpIGjhu41jIEZQVCBIQ00!5e0!3m2!1svi!2s!4v1623456789012!5m2!1svi!2s';
            iframe.width = '100%';
            iframe.height = '300';
            iframe.style.border = '0';
            iframe.allowFullscreen = true;
            iframe.loading = 'lazy';
            iframe.referrerPolicy = 'no-referrer-when-downgrade';

            this.innerHTML = '';
            this.appendChild(iframe);
        });
    }
</script>
@endsection