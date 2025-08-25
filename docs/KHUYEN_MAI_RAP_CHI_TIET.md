# 🎯 Hệ thống quản lý khuyến mãi cho từng rạp cụ thể

## 📋 Tổng quan

Tính năng mới này cho phép **Admin Chi nhánh** gán khuyến mãi cho **từng rạp cụ thể** thay vì chỉ gán cho toàn bộ chi nhánh.

### 🔗 Luồng hoạt động:

1. **Admin Tổng** tạo khuyến mãi và gán cho chi nhánh
2. **Admin Chi nhánh** có thể gán khuyến mãi đó cho từng rạp cụ thể trong chi nhánh
3. Khuyến mãi chỉ áp dụng tại những rạp được gán

---

## 🏗️ Cấu trúc Database

### Bảng mới: `khuyen_mai_rap_phims`

```sql
CREATE TABLE khuyen_mai_rap_phims (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    khuyen_mai_id BIGINT NOT NULL,
    rap_phim_id BIGINT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (khuyen_mai_id) REFERENCES khuyen_mais(id) ON DELETE CASCADE,
    FOREIGN KEY (rap_phim_id) REFERENCES rap_phims(id) ON DELETE CASCADE,
    UNIQUE KEY unique_khuyen_mai_rap (khuyen_mai_id, rap_phim_id)
);
```

### Model relationships:

**KhuyenMai.php:**

```php
public function rapPhims() {
    return $this->belongsToMany(RapPhim::class, 'khuyen_mai_rap_phims', 'khuyen_mai_id', 'rap_phim_id')
        ->withTimestamps('created_at', 'updated_at');
}
```

**RapPhim.php:**

```php
public function khuyenMais() {
    return $this->belongsToMany(KhuyenMai::class, 'khuyen_mai_rap_phims', 'rap_phim_id', 'khuyen_mai_id')
        ->withTimestamps('created_at', 'updated_at');
}
```

---

## 🎮 Controller: ChiNhanhRapKhuyenMaiController

### Các method chính:

#### 1. `index()` - Danh sách khuyến mãi

**Route:** `/admin/chi-nhanh-rap-khuyen-mai`

**Chức năng:**

-   Hiển thị tất cả khuyến mãi áp dụng cho chi nhánh
-   Hiển thị danh sách rạp đã được gán cho từng khuyến mãi
-   Interface để gán/hủy gán khuyến mãi cho rạp

#### 2. `assignToRap()` - Gán khuyến mãi cho rạp

**Route:** `POST /admin/chi-nhanh-rap-khuyen-mai/assign`

**Input:**

```json
{
    "khuyen_mai_id": 1,
    "rap_phim_ids": [1, 2, 3]
}
```

**Chức năng:**

-   Validate quyền truy cập
-   Kiểm tra khuyến mãi thuộc chi nhánh
-   Kiểm tra rạp thuộc chi nhánh
-   Gán khuyến mãi cho các rạp được chọn

#### 3. `removeFromRap()` - Hủy gán khuyến mãi

**Route:** `POST /admin/chi-nhanh-rap-khuyen-mai/remove`

**Input:**

```json
{
    "khuyen_mai_id": 1,
    "rap_phim_id": 1
}
```

#### 4. `getAssignedRaps()` - Lấy danh sách rạp đã gán

**Route:** `GET /admin/chi-nhanh-rap-khuyen-mai/assigned-raps/{khuyenMaiId}`

---

## 🎨 Giao diện

### View: `chi-nhanh-rap-khuyen-mai/index.blade.php`

**Tính năng:**

-   ✅ Hiển thị cards khuyến mãi với thông tin chi tiết
-   ✅ Hiển thị rạp đã được gán (badges có thể xóa)
-   ✅ Modal gán khuyến mãi với checkbox cho từng rạp
-   ✅ AJAX handling cho tất cả thao tác
-   ✅ Responsive design
-   ✅ Real-time update interface

**Các thành phần chính:**

1. **Card khuyến mãi** - Hiển thị thông tin khuyến mãi
2. **Badge rạp** - Hiển thị rạp đã gán, có nút xóa
3. **Modal gán rạp** - Form chọn rạp để gán
4. **Alert messages** - Thông báo kết quả thao tác

---

## 🔐 Phân quyền

### Quyền mới được thêm:

```sql
-- Quyền được thêm vào bảng phan_quyens
INSERT INTO phan_quyens VALUES
(201, 'Xem quản lý khuyến mãi rạp', 'admin.chi-nhanh-rap-khuyen-mai.index'),
(202, 'Gán khuyến mãi cho rạp', 'admin.chi-nhanh-rap-khuyen-mai.assign'),
(203, 'Hủy gán khuyến mãi khỏi rạp', 'admin.chi-nhanh-rap-khuyen-mai.remove');

-- Gán cho vai trò admin chi nhánh (vai_tro_id = 2)
INSERT INTO vai_tro_phan_quyens (vai_tro_id, phan_quyen_id) VALUES
(2, 201), (2, 202), (2, 203);
```

### Middleware bảo vệ:

-   `admin.chi.nhanh` - Chỉ admin chi nhánh mới truy cập được
-   Kiểm tra quyền sở hữu chi nhánh trong mỗi method

---

## 🧩 Menu Navigation

**Sidebar Admin Chi nhánh:**

```
📊 Chi nhánh của tôi
├── 🎬 Suất chiếu
├── 🎫 Đơn vé
├── 🎁 Khuyến mãi
└── 🏪 Khuyến mãi rạp  ← MỊI
```

---

## 🔄 Workflow sử dụng

### Bước 1: Admin Tổng tạo khuyến mãi

```php
// Admin tổng tạo khuyến mãi và gán cho chi nhánh
$khuyenMai = KhuyenMai::create([...]);
$khuyenMai->chiNhanhs()->attach([1, 2]); // Gán cho chi nhánh 1 và 2
```

### Bước 2: Admin Chi nhánh gán cho rạp cụ thể

```php
// Admin chi nhánh gán khuyến mãi cho rạp cụ thể
$khuyenMai = KhuyenMai::find(1);
$khuyenMai->rapPhims()->attach([1, 3]); // Chỉ gán cho rạp 1 và 3
```

### Bước 3: Kiểm tra khuyến mãi áp dụng

```php
// Kiểm tra rạp nào có khuyến mãi
$rapPhim = RapPhim::find(1);
$khuyenMais = $rapPhim->khuyenMais; // Lấy tất cả khuyến mãi của rạp này
```

---

## 🧪 Testing

### Test cơ bản:

```bash
cd /path/to/project
php artisan tinker

# Test gán khuyến mãi cho rạp
$khuyenMai = KhuyenMai::first();
$rapPhim = RapPhim::first();
$khuyenMai->rapPhims()->attach($rapPhim->id);

# Kiểm tra kết quả
echo $khuyenMai->rapPhims()->count(); // Số rạp được gán
echo $rapPhim->khuyenMais()->count(); // Số khuyến mãi của rạp
```

### Test routes:

-   `GET /admin/chi-nhanh-rap-khuyen-mai` - Trang quản lý
-   `POST /admin/chi-nhanh-rap-khuyen-mai/assign` - Gán khuyến mãi
-   `POST /admin/chi-nhanh-rap-khuyen-mai/remove` - Hủy gán
-   `GET /admin/chi-nhanh-rap-khuyen-mai/assigned-raps/{id}` - API lấy rạp đã gán

---

## 🛡️ Bảo mật

### Các biện pháp bảo vệ:

1. **Middleware protection:**

    - `admin.chi.nhanh` - Chỉ admin chi nhánh
    - Route protection với permission check

2. **Data validation:**

    - Kiểm tra khuyến mãi thuộc chi nhánh quản lý
    - Kiểm tra rạp thuộc chi nhánh quản lý
    - Validate foreign key constraints

3. **Authorization checks:**

    ```php
    // Kiểm tra quyền trong controller
    if ($user->vai_tro_id != 2) {
        abort(403, 'Bạn không có quyền truy cập trang này.');
    }

    // Kiểm tra sở hữu chi nhánh
    $chiNhanh = $user->chiNhanhDangQuanLy;
    if (!$chiNhanh->khuyenMais()->where('khuyen_mais.id', $khuyenMai->id)->exists()) {
        return response()->json(['success' => false, 'message' => 'Khuyến mãi này không thuộc chi nhánh bạn quản lý.']);
    }
    ```

---

## 📊 Ưu điểm của hệ thống mới

### 🎯 Tính linh hoạt cao:

-   Admin chi nhánh có thể chọn rạp cụ thể để áp dụng khuyến mãi
-   Không phải áp dụng cho toàn bộ chi nhánh

### 🎮 Dễ sử dụng:

-   Interface trực quan với checkbox
-   Real-time update không cần reload trang
-   Feedback ngay lập tức cho user

### 🔒 Bảo mật tốt:

-   Phân quyền rõ ràng theo vai trò
-   Validation chặt chẽ ở mọi level
-   Prevention duplicate assignments

### 📈 Khả năng mở rộng:

-   Database structure có thể mở rộng thêm thông tin
-   API endpoints sẵn sàng cho mobile app
-   Compatible với hệ thống hiện tại

---

## 🚀 Hướng phát triển

### Tính năng có thể bổ sung:

1. **Bulk operations:**

    - Gán nhiều khuyến mãi cho một rạp
    - Gán một khuyến mãi cho nhiều rạp cùng lúc

2. **Scheduling:**

    - Hẹn giờ tự động gán/hủy gán khuyến mãi
    - Khuyến mãi theo lịch cho từng rạp

3. **Analytics:**

    - Thống kê hiệu quả khuyến mãi theo rạp
    - So sánh performance giữa các rạp

4. **Advanced filtering:**
    - Lọc rạp theo tiêu chí (doanh thu, khu vực, etc.)
    - Smart suggestion based on past performance

---

## ✅ Checklist hoàn thành

-   ✅ Migration `khuyen_mai_rap_phims` table
-   ✅ Model `KhuyenMaiRapPhim` với relationships
-   ✅ Controller `ChiNhanhRapKhuyenMaiController`
-   ✅ Views responsive với AJAX
-   ✅ Routes với middleware protection
-   ✅ Permissions và role assignment
-   ✅ Menu navigation integration
-   ✅ Testing và validation
-   ✅ Documentation đầy đủ

**🎉 Hệ thống đã sẵn sàng sử dụng cho production!**
