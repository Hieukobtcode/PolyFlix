@extends('layouts.client')

@section('title', 'Liên hệ - PolyFlix')

@section('content')
<style>
    .contact-section {
        padding: 60px 0;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    
    .contact-form-section {
        padding: 80px 0;
        background-color: #f8f9fa;
    }
    
    .contact-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        padding: 40px;
        margin-bottom: 30px;
    }
    
    .contact-info-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 15px;
        padding: 40px;
        height: 100%;
    }
    
    .contact-icon {
        width: 60px;
        height: 60px;
        background: rgba(255,255,255,0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
        font-size: 24px;
    }
    
    .form-control {
        border: 2px solid #e9ecef;
        border-radius: 10px;
        padding: 15px;
        font-size: 16px;
        transition: all 0.3s ease;
    }
    
    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
    
    .btn-submit {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 10px;
        padding: 15px 40px;
        font-size: 16px;
        font-weight: 600;
        color: white;
        transition: all 0.3s ease;
    }
    
    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        color: white;
    }
    
    .page-title {
        font-size: 3rem;
        font-weight: 700;
        margin-bottom: 20px;
    }
    
    .page-subtitle {
        font-size: 1.2rem;
        opacity: 0.9;
        margin-bottom: 0;
    }
    
    .contact-item {
        margin-bottom: 30px;
    }
    
    .contact-item h5 {
        margin-bottom: 10px;
        font-weight: 600;
    }
    
    .contact-item p {
        margin-bottom: 5px;
        opacity: 0.9;
    }
    
    .alert {
        border-radius: 10px;
        border: none;
        padding: 20px;
        margin-bottom: 30px;
    }
    
    .alert-success {
        background: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%);
        color: white;
    }
    
    .alert-danger {
        background: linear-gradient(135deg, #ff416c 0%, #ff4b2b 100%);
        color: white;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .page-title {
            font-size: 2rem;
        }

        .contact-card, .contact-info-card {
            padding: 30px 20px;
        }

        .contact-section {
            padding: 40px 0;
        }

        .contact-form-section {
            padding: 60px 0;
        }
    }

    /* Animation */
    .contact-card, .contact-info-card {
        animation: fadeInUp 0.6s ease-out;
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

    /* Hover effects */
    .contact-item:hover .contact-icon {
        transform: scale(1.1);
        transition: transform 0.3s ease;
    }
</style>

<!-- Header Section -->
<div class="contact-section">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <h1 class="page-title">Liên hệ với chúng tôi</h1>
                <p class="page-subtitle">Chúng tôi luôn sẵn sàng lắng nghe và hỗ trợ bạn. Hãy để lại thông tin và chúng tôi sẽ phản hồi sớm nhất có thể.</p>
            </div>
        </div>
    </div>
</div>

<!-- Contact Form Section -->
<div class="contact-form-section">
    <div class="container">
        <div class="row">
            <!-- Form liên hệ -->
            <div class="col-lg-8">
                <div class="contact-card">
                    <h3 class="mb-4" style="color: #333; font-weight: 600;">Gửi tin nhắn cho chúng tôi</h3>
                    
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
                    
                    <form action="{{ route('client.lien-he.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="ten" class="form-label fw-semibold">Họ và tên <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('ten') is-invalid @enderror" 
                                       id="ten" name="ten" value="{{ old('ten') }}" 
                                       placeholder="Nhập họ và tên của bạn">
                                @error('ten')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}" 
                                       placeholder="Nhập địa chỉ email">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="so_dien_thoai" class="form-label fw-semibold">Số điện thoại <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control @error('so_dien_thoai') is-invalid @enderror" 
                                   id="so_dien_thoai" name="so_dien_thoai" value="{{ old('so_dien_thoai') }}" 
                                   placeholder="Nhập số điện thoại">
                            @error('so_dien_thoai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="noi_dung" class="form-label fw-semibold">Nội dung <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('noi_dung') is-invalid @enderror" 
                                      id="noi_dung" name="noi_dung" rows="6" 
                                      placeholder="Nhập nội dung bạn muốn gửi...">{{ old('noi_dung') }}</textarea>
                            @error('noi_dung')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <button type="submit" class="btn btn-submit">
                            <i class="fas fa-paper-plane me-2"></i>
                            Gửi tin nhắn
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Thông tin liên hệ -->
            <div class="col-lg-4">
                <div class="contact-info-card">
                    <h4 class="mb-4 fw-bold">Thông tin liên hệ</h4>
                    
                    @if($cauHinh)
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <h5>Địa chỉ</h5>
                            <p>{{ $cauHinh->dia_chi ?? 'Chưa cập nhật địa chỉ' }}</p>
                        </div>
                        
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <h5>Số điện thoại</h5>
                            <p>{{ $cauHinh->so_dien_thoai ?? 'Chưa cập nhật số điện thoại' }}</p>
                        </div>
                        
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <h5>Email</h5>
                            <p>{{ $cauHinh->email ?? 'Chưa cập nhật email' }}</p>
                        </div>
                        
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <h5>Thời gian làm việc</h5>
                            <p>{{ $cauHinh->thoi_gian_lam_viec ?? 'Thứ 2 - Chủ nhật: 8:00 - 22:00' }}</p>
                        </div>
                    @else
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            <h5>Thông tin</h5>
                            <p>Thông tin liên hệ đang được cập nhật...</p>
                        </div>
                    @endif
                    
                    <!-- Social Media -->
                    <div class="contact-item">
                        <h5>Theo dõi chúng tôi</h5>
                        <div class="d-flex gap-3 mt-3">
                            @if($cauHinh && $cauHinh->link_facebook)
                                <a href="{{ $cauHinh->link_facebook }}" class="text-white" style="font-size: 24px;">
                                    <i class="fab fa-facebook"></i>
                                </a>
                            @endif
                            @if($cauHinh && $cauHinh->link_youtube)
                                <a href="{{ $cauHinh->link_youtube }}" class="text-white" style="font-size: 24px;">
                                    <i class="fab fa-youtube"></i>
                                </a>
                            @endif
                            <a href="#" class="text-white" style="font-size: 24px;">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="#" class="text-white" style="font-size: 24px;">
                                <i class="fab fa-tiktok"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-hide success/error messages after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';
            setTimeout(function() {
                alert.remove();
            }, 500);
        }, 5000);
    });

    // Form validation enhancement
    const form = document.querySelector('form');
    const submitBtn = document.querySelector('.btn-submit');

    if (form && submitBtn) {
        form.addEventListener('submit', function() {
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang gửi...';
            submitBtn.disabled = true;
        });
    }

    // Phone number formatting
    const phoneInput = document.getElementById('so_dien_thoai');
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 10) {
                value = value.substring(0, 10);
            }
            e.target.value = value;
        });
    }
});
</script>
@endsection
