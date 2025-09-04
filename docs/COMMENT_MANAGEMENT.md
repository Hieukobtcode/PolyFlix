# Hệ thống Quản lý Bình luận - PolyFlix

## Tính năng đã hoàn thiện

### 1. Quản lý Bình luận Admin

-   **Đường dẫn:** `/admin/comments`
-   **Menu:** Nội dung hiển thị > Bình luận và đánh giá

### 2. Các chức năng chính

#### 2.1 Trang tổng quan

-   Hiển thị danh sách phim có bình luận/đánh giá
-   Thống kê tổng quan: Tổng bình luận, đang hiển thị, đã ẩn, đã phản hồi, lượt đánh giá, điểm trung bình
-   Lọc theo chi nhánh và rạp phim
-   2 chế độ xem:
    -   **Theo phim:** Hiển thị card của từng phim có bình luận
    -   **Tất cả bình luận:** Hiển thị danh sách tất cả bình luận dạng list

#### 2.2 Chi tiết bình luận theo phim

-   **Đường dẫn:** `/admin/comments/{phim_id}`
-   Thống kê chi tiết cho phim
-   Hiển thị rating trung bình và số lượt đánh giá
-   Danh sách bình luận với phân trang
-   Lọc theo trạng thái: Tất cả, đang hiển thị, đã ẩn, đã phản hồi, chưa phản hồi

#### 2.3 Quản lý bình luận

-   **Ẩn/Hiện bình luận:** Admin có thể ẩn hoặc hiện lại bình luận
-   **Phản hồi:** Admin có thể phản hồi trực tiếp với khách hàng
-   **KHÔNG cho phép xóa:** Tuân thủ yêu cầu không xóa bình luận của khách hàng

### 3. Các Model và Relationship

#### Comment Model

```php
protected $fillable = [
    'user_id',
    'phim_id',
    'content',
    'visible',
    'reply'
];

// Relationships
public function user()
public function phim()
```

### 4. Routes

```php
Route::prefix('comments')->name('admin.comments.')->group(function () {
    Route::get('/', [CommentController::class, 'index'])->name('index');
    Route::get('/{phim}', [CommentController::class, 'show'])->name('show');
    Route::post('/{comment}/reply', [CommentController::class, 'reply'])->name('reply');
    Route::post('/{comment}/hide', [CommentController::class, 'hide'])->name('hide');
    Route::post('/{comment}/unhide', [CommentController::class, 'unhide'])->name('unhide');
});
```

### 5. Controller Methods

#### CommentController

-   `index()` - Trang tổng quan, hỗ trợ 2 chế độ xem
-   `show()` - Chi tiết bình luận theo phim, có filter và phân trang
-   `reply()` - Phản hồi bình luận
-   `hide()` - Ẩn bình luận
-   `unhide()` - Hiện lại bình luận

### 6. Views

#### admin/comments/index.blade.php

-   Trang tổng quan với thống kê
-   Filter theo chi nhánh/rạp
-   Toggle giữa 2 chế độ xem

#### admin/comments/all.blade.php

-   Danh sách tất cả bình luận
-   Filter theo trạng thái và địa điểm
-   Phân trang

#### admin/comments/show.blade.php

-   Chi tiết bình luận theo phim
-   Thống kê chi tiết
-   Form phản hồi inline
-   Actions ẩn/hiện

### 7. Phân quyền

-   Tích hợp trong hệ thống phân quyền existing
-   Permissions:
    -   `admin.comments.index` - Xem danh sách
    -   `admin.comments.show` - Xem chi tiết
    -   `admin.comments.reply` - Phản hồi
    -   `admin.comments.hide` - Ẩn bình luận
    -   `admin.comments.unhide` - Hiện bình luận

### 8. UI/UX Features

-   Responsive design
-   Loading states
-   Confirmation dialogs
-   Success/error messages
-   Bootstrap 5 styling
-   Font Awesome icons
-   Hover effects
-   Color coding (xanh=hiện, đỏ=ẩn)

### 9. Bảo mật

-   CSRF protection
-   Input validation
-   XSS protection
-   Authentication required
-   Role-based access

## Lưu ý kỹ thuật

1. **Không xóa bình luận:** Tuân thủ yêu cầu, chỉ có ẩn/hiện
2. **Phân trang:** Sử dụng Laravel pagination
3. **Filter:** Query parameters được bảo toàn qua pagination
4. **Performance:** Eager loading relationships
5. **Responsive:** Mobile-friendly design

## Cách sử dụng

1. Truy cập menu "Nội dung hiển thị" > "Bình luận và đánh giá"
2. Chọn chế độ xem phù hợp
3. Sử dụng filter để tìm kiếm
4. Click vào phim để xem chi tiết bình luận
5. Sử dụng các action buttons để quản lý bình luận
