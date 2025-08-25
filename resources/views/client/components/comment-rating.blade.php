{{-- Component Comment và Rating --}}
<div class="comment-rating-section mt-5" data-phim-id="{{ $phim->id }}">
    <div class="container">
        {{-- Header --}}
        <div class="row mb-4">
            <div class="col-12">
                <h3 class="fw-bold text-primary">
                    <i class="fas fa-comments me-2"></i>
                    Bình luận & Đánh giá
                </h3>
            </div>
        </div>

        {{-- Rating Summary --}}
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <div class="rating-summary" id="rating-summary">
                            <div class="average-rating mb-2">
                                <span class="display-4 fw-bold text-warning" id="avg-rating">0.0</span>
                                <div class="stars fs-4" id="avg-stars">
                                    <span class="text-muted">☆☆☆☆☆</span>
                                </div>
                            </div>
                            <p class="text-muted mb-0" id="rating-count">0 đánh giá</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3">Phân bố đánh giá</h6>
                        <div id="rating-distribution">
                            @for ($i = 5; $i >= 1; $i--)
                            <div class="d-flex align-items-center mb-2">
                                <span class="me-2">{{ $i }} <i class="fas fa-star text-warning"></i></span>
                                <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                    <div class="progress-bar bg-warning" role="progressbar"
                                        style="width: 0%" id="progress-{{ $i }}"></div>
                                </div>
                                <small class="text-muted" id="count-{{ $i }}">0</small>
                            </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @auth
        {{-- Form đánh giá và bình luận --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-edit me-2"></i>
                            Viết bình luận của bạn
                        </h5>
                    </div>
                    <div class="card-body">
                        {{-- Rating Form --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Đánh giá của bạn:</label>
                            <div class="rating-input">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star rating-star" data-rating="{{ $i }}"
                                    style="font-size: 1.5rem; color: #ddd; cursor: pointer;"></i>
                                    @endfor
                            </div>
                            <small class="text-muted">Click vào sao để đánh giá</small>
                        </div>

                        {{-- Comment Form --}}
                        <form id="comment-form">
                            @csrf
                            <input type="hidden" name="phim_id" value="{{ $phim->id }}">
                            <input type="hidden" name="rating" id="selected-rating" value="">

                            <div class="mb-3">
                                <label for="content" class="form-label fw-bold">Nội dung bình luận:</label>
                                <textarea name="content" id="content" class="form-control" rows="4"
                                    placeholder="Chia sẻ cảm nhận của bạn về bộ phim này..." required></textarea>
                                <small class="text-muted">Tối thiểu 10 ký tự, tối đa 1000 ký tự</small>
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-2"></i>
                                Gửi bình luận
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="row mb-4">
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle me-2"></i>
                    <a href="{{ route('login.form') }}" class="alert-link">Đăng nhập</a> để viết bình luận và đánh giá
                </div>
            </div>
        </div>
        @endauth

        {{-- Comments List --}}
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <i class="fas fa-comment-dots me-2"></i>
                            Bình luận từ khán giả
                        </h5>
                    </div>
                    <div class="card-body">
                        <div id="comments-list">
                            {{-- Comments sẽ được load bằng AJAX --}}
                            <div class="text-center py-4">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Đang tải...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .rating-star:hover,
    .rating-star.active {
        color: #ffc107 !important;
    }

    .comment-item {
        border-bottom: 1px solid #eee;
        padding: 1rem 0;
    }

    .comment-item:last-child {
        border-bottom: none;
    }

    .user-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
    }

    .user-rating {
        color: #ffc107;
        font-size: 0.9rem;
    }

    .admin-reply {
        background-color: #f8f9fa;
        border-left: 4px solid #007bff;
        padding: 0.75rem;
        margin-top: 0.5rem;
        border-radius: 0 0.375rem 0.375rem 0;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const phimId = document.querySelector('.comment-rating-section').dataset.phimId;
        let selectedRating = 0;

        // Load comments khi trang load
        loadComments();

        // Xử lý rating stars
        document.querySelectorAll('.rating-star').forEach(star => {
            star.addEventListener('click', function() {
                selectedRating = parseInt(this.dataset.rating);
                document.getElementById('selected-rating').value = selectedRating;

                // Update visual
                document.querySelectorAll('.rating-star').forEach((s, index) => {
                    if (index < selectedRating) {
                        s.classList.add('active');
                    } else {
                        s.classList.remove('active');
                    }
                });
            });

            star.addEventListener('mouseenter', function() {
                const hoverRating = parseInt(this.dataset.rating);
                document.querySelectorAll('.rating-star').forEach((s, index) => {
                    if (index < hoverRating) {
                        s.style.color = '#ffc107';
                    } else {
                        s.style.color = '#ddd';
                    }
                });
            });
        });

        // Reset hover effect
        document.querySelector('.rating-input').addEventListener('mouseleave', function() {
            document.querySelectorAll('.rating-star').forEach((s, index) => {
                if (index < selectedRating) {
                    s.style.color = '#ffc107';
                } else {
                    s.style.color = '#ddd';
                }
            });
        });

        // Submit form
        document.getElementById('comment-form').addEventListener('submit', function(e) {
            e.preventDefault();

            if (selectedRating === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Chưa đánh giá',
                    text: 'Vui lòng chọn số sao để đánh giá phim.'
                });
                return;
            }

            const formData = new FormData(this);

            // Submit rating trước
            submitRating(selectedRating, phimId).then(() => {
                // Sau đó submit comment
                submitComment(formData);
            });
        });

        function submitRating(rating, phimId) {
            return fetch('/ratings', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        phim_id: phimId,
                        rating: rating
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateRatingSummary(data.average_rating, data.rating_count);
                    }
                });
        }

        function submitComment(formData) {
            fetch('/comments', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Thành công!',
                            text: data.message,
                            timer: 2000
                        });

                        // Reset form
                        document.getElementById('comment-form').reset();
                        selectedRating = 0;
                        document.querySelectorAll('.rating-star').forEach(s => {
                            s.classList.remove('active');
                            s.style.color = '#ddd';
                        });

                        // Reload comments
                        loadComments();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi!',
                            text: data.message
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi!',
                        text: 'Có lỗi xảy ra khi gửi bình luận.'
                    });
                });
        }

        function loadComments() {
            fetch(`/comments/${phimId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateRatingSummary(data.data.average_rating, data.data.rating_count);
                        updateRatingDistribution(data.data.rating_distribution);
                        displayComments(data.data.comments);
                    }
                })
                .catch(error => {
                    document.getElementById('comments-list').innerHTML =
                        '<div class="text-center text-muted py-4">Không thể tải bình luận</div>';
                });
        }

        function updateRatingSummary(avgRating, ratingCount) {
            document.getElementById('avg-rating').textContent = avgRating || '0.0';
            document.getElementById('rating-count').textContent = `${ratingCount} đánh giá`;

            // Update stars
            const stars = Math.round(avgRating);
            let starsHtml = '';
            for (let i = 1; i <= 5; i++) {
                starsHtml += i <= stars ? '★' : '☆';
            }
            document.getElementById('avg-stars').innerHTML = `<span class="text-warning">${starsHtml}</span>`;
        }

        function updateRatingDistribution(distribution) {
            for (let i = 1; i <= 5; i++) {
                const data = distribution[i];
                document.getElementById(`progress-${i}`).style.width = `${data.percentage}%`;
                document.getElementById(`count-${i}`).textContent = data.count;
            }
        }

        function displayComments(comments) {
            const commentsHtml = comments.length > 0 ? comments.map(comment => `
            <div class="comment-item">
                <div class="d-flex">
                    <img src="${comment.user_avatar}" alt="${comment.user_name}" class="user-avatar me-3">
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h6 class="mb-0 fw-bold">${comment.user_name}</h6>
                            <small class="text-muted">${comment.created_at}</small>
                        </div>
                        ${comment.user_rating ? `
                            <div class="user-rating mb-2">
                                ${'★'.repeat(comment.user_rating)}${'☆'.repeat(5 - comment.user_rating)}
                            </div>
                        ` : ''}
                        <p class="mb-2">${comment.content}</p>
                        ${comment.reply ? `
                            <div class="admin-reply">
                                <small class="fw-bold text-primary">Phản hồi từ Admin:</small>
                                <p class="mb-0 mt-1">${comment.reply}</p>
                            </div>
                        ` : ''}
                    </div>
                </div>
            </div>
        `).join('') : '<div class="text-center text-muted py-4">Chưa có bình luận nào</div>';

            document.getElementById('comments-list').innerHTML = commentsHtml;
        }
    });
</script>