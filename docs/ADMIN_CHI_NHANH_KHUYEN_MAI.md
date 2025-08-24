# 🏢 Hệ thống quản lý khuyến mãi cho Admin Chi nhánh

## 📋 Tổng quan

Hệ thống này được phát triển để cho phép **Admin Chi nhánh** xem và quản lý các khuyến mãi được áp dụng tại chi nhánh mà họ quản lý. Admin chi nhánh chỉ có thể xem các khuyến mãi do **Admin Tổng** tạo và gán cho chi nhánh của họ.

## 🔐 Phân quyền

### Admin Tổng (vai_tro_id = 1)

-   ✅ Tạo, sửa, xóa khuyến mãi
-   ✅ Gán khuyến mãi cho các chi nhánh
-   ✅ Xem toàn bộ thống kê khuyến mãi

### Admin Chi nhánh (vai_tro_id = 2)

-   ✅ Xem danh sách khuyến mãi áp dụng cho chi nhánh của mình
-   ✅ Xem chi tiết khuyến mãi
-   ✅ Xem báo cáo thống kê sử dụng khuyến mãi tại chi nhánh
-   ❌ Không thể tạo, sửa, xóa khuyến mãi

## 🌟 Tính năng chính

### 1. Danh sách khuyến mãi chi nhánh

**Route:** `/admin/chi-nhanh-khuyen-mai`

**Chức năng:**

-   Hiển thị tất cả khuyến mãi được áp dụng tại chi nhánh
-   Bộ lọc theo: trạng thái, loại áp dụng, thời gian
-   Tìm kiếm theo tên hoặc mã khuyến mãi
-   Thống kê tổng quan: tổng số, đang hoạt động, sắp hết hạn, đã hết hạn

### 2. Chi tiết khuyến mãi

**Route:** `/admin/chi-nhanh-khuyen-mai/{id}`

**Chức năng:**

-   Xem đầy đủ thông tin khuyến mãi
-   Thống kê sử dụng tại chi nhánh cụ thể
-   Hiển thị tất cả chi nhánh áp dụng (highlight chi nhánh hiện tại)
-   Thông tin thời gian và cảnh báo hết hạn

### 3. Báo cáo khuyến mãi

**Route:** `/admin/chi-nhanh-khuyen-mai/bao-cao`

**Chức năng:**

-   Báo cáo theo khoảng thời gian
-   Thống kê tổng quan: tổng khuyến mãi, số vé sử dụng, tổng tiền giảm
-   Báo cáo chi tiết theo từng khuyến mãi
-   Biểu đồ thống kê trực quan
-   Xuất Excel

## 📊 Thống kê được tính toán

### Số vé đã sử dụng

Đếm số đơn đặt vé có sử dụng khuyến mãi tại chi nhánh

### Tổng tiền giảm

Tính toán dựa trên:

-   **Khuyến mãi %:** `(tong_tien * gia_tri_giam) / 100` (tối đa `giam_toi_da`)
-   **Khuyến mãi tiền:** `gia_tri_giam`

### Tỷ lệ sử dụng

`(so_ve_da_su_dung / so_lan_su_dung_toi_da) * 100%`

## 🛡️ Bảo mật

### Middleware `CheckAdminChiNhanh`

-   Kiểm tra đăng nhập
-   Kiểm tra vai trò admin chi nhánh (vai_tro_id = 2)
-   Kiểm tra admin đã được gán chi nhánh chưa

### Quyền hạn trong database

```sql
-- Quyền được thêm vào bảng phan_quyens
INSERT INTO phan_quyens VALUES
(198, 'Xem khuyến mãi chi nhánh', 'admin.chi-nhanh-khuyen-mai.index'),
(199, 'Xem chi tiết khuyến mãi chi nhánh', 'admin.chi-nhanh-khuyen-mai.show'),
(200, 'Xem báo cáo khuyến mãi chi nhánh', 'admin.chi-nhanh-khuyen-mai.bao-cao');

-- Gán cho vai trò admin chi nhánh (vai_tro_id = 2)
INSERT INTO vai_tro_phan_quyens (vai_tro_id, phan_quyen_id) VALUES
(2, 198), (2, 199), (2, 200);
```

## 📱 Giao diện

### Menu sidebar

Khuyến mãi được thêm vào menu "Chi nhánh của tôi" cho admin chi nhánh:

```
📊 Chi nhánh của tôi
├── 🎬 Suất chiếu
├── 🎫 Đơn vé
└── 🎁 Khuyến mãi  ← MỚI
```

### Responsive design

-   Desktop: Layout 2 cột (thông tin + thống kê)
-   Mobile: Layout 1 cột, responsive

### Màu sắc và icon

-   🎁 Icon cho khuyến mãi
-   📊 Icon cho báo cáo
-   🏢 Icon cho chi nhánh
-   Màu sắc theo trạng thái: xanh (hoạt động), vàng (sắp hết), đỏ (hết hạn)

## 🔧 Cài đặt

### 1. Chạy migration (nếu cần)

```bash
php artisan migrate
```

### 2. Chạy seeder

```bash
php artisan db:seed --class=PhanQuyenSeeder
php artisan db:seed --class=VaiTroPhanQuyenSeeder
```

### 3. Tạo admin chi nhánh (nếu chưa có)

```php
// Tạo user admin chi nhánh
$admin = User::create([
    'name' => 'Admin Chi nhánh HN',
    'email' => 'admin.hn@polyflix.com',
    'password' => Hash::make('password'),
    'vai_tro_id' => 2, // Admin chi nhánh
]);

// Gán chi nhánh cho admin
$chiNhanh = ChiNhanh::find(1);
$chiNhanh->quan_ly_id = $admin->id;
$chiNhanh->save();
```

## 🧪 Test

### Test cơ bản

```bash
cd /path/to/project
php artisan tinker

# Test admin chi nhánh
$admin = User::where('vai_tro_id', 2)->first();
$chiNhanh = $admin->chiNhanhDangQuanLy;
echo $chiNhanh->khuyenMais()->count(); // Số khuyến mãi
```

### Test routes

-   `/admin/chi-nhanh-khuyen-mai` - Danh sách
-   `/admin/chi-nhanh-khuyen-mai/1` - Chi tiết
-   `/admin/chi-nhanh-khuyen-mai/bao-cao` - Báo cáo

## 📈 Tương lai

### Tính năng có thể mở rộng

-   [ ] Thông báo khuyến mãi sắp hết hạn
-   [ ] So sánh hiệu quả giữa các chi nhánh
-   [ ] Export PDF báo cáo
-   [ ] Dashboard widget cho admin chi nhánh
-   [ ] API cho mobile app

### Tối ưu hóa

-   [ ] Cache thống kê khuyến mãi
-   [ ] Index database cho performance
-   [ ] Background job tính toán báo cáo

## 🐛 Xử lý lỗi

### Lỗi thường gặp

1. **"Bạn chưa được gán quản lý chi nhánh nào"**

    - Kiểm tra `chi_nhanhs.quan_ly_id = user.id`

2. **"Không có khuyến mãi nào"**

    - Admin tổng chưa gán khuyến mãi cho chi nhánh
    - Kiểm tra bảng `khuyen_mai_chi_nhanhs`

3. **Lỗi permission**
    - Chạy lại seeder quyền
    - Kiểm tra `vai_tro_id = 2`

---

**Phát triển bởi:** Team PolyFlix  
**Phiên bản:** 1.0  
**Ngày cập nhật:** August 2025
