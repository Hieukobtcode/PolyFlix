# DatVeSeeder - Seeder Đơn Hàng Ảo

## Mô tả
DatVeSeeder tạo dữ liệu đơn hàng ảo (fake orders) cho hệ thống rạp chiếu phim PolyFlix với đầy đủ thông tin thực tế.

## Tính năng đã triển khai

### 1. Dữ liệu đơn hàng đầy đủ
- ✅ **Thông tin khách hàng**: Lấy từ bảng users (loại trừ admin)
- ✅ **Suất chiếu**: Sử dụng suất chiếu có sẵn từ SuatChieuSeeder
- ✅ **Ghế ngồi**: Chọn ngẫu nhiên 1-4 ghế từ phòng chiếu tương ứng
- ✅ **Combo đồ ăn**: 50% đơn hàng có combo/đồ ăn (60% combo, 40% đồ ăn lẻ)
- ✅ **Trạng thái đa dạng**: Đã thanh toán, Chờ thanh toán, Đã hủy, Đã xuất vé
- ✅ **Phương thức thanh toán**: ZaloPay, VNPay, MoMo, Banking, COD

### 2. Số lượng và phân bố
- ✅ **100 đơn hàng** được tạo thành công
- ✅ **Phân bố thời gian**: 30 ngày gần đây
- ✅ **Phân bố trạng thái**:
  - Đã thanh toán: 31 đơn
  - Đã xuất vé: 21 đơn  
  - Chờ thanh toán: 32 đơn
  - Đã hủy: 16 đơn

### 3. Tính nhất quán dữ liệu
- ✅ **Ghế không trùng lặp**: Mỗi ghế chỉ được đặt một lần trong cùng suất chiếu
- ✅ **Giá vé chính xác**: 
  - Giá cơ bản: 75,000 VND
  - Phụ thu VIP: +20,000 VND
  - Phụ thu Couple: +30,000 VND
- ✅ **Thông tin khách hàng hợp lệ**: Chỉ lấy user có vai trò khách hàng
- ✅ **Cập nhật trạng thái ghế**: Ghế được đánh dấu "đã đặt" khi thanh toán thành công

### 4. Thông tin thanh toán chi tiết
- ✅ **Mã giao dịch**: Tự động tạo cho đơn đã thanh toán
- ✅ **Ngày thanh toán**: Thời gian thực tế sau khi đặt vé
- ✅ **Ghi chú**: Mô tả trạng thái và phương thức thanh toán
- ✅ **Khuyến mãi**: 30% đơn hàng có áp dụng khuyến mãi

### 5. Kích hoạt trong DatabaseSeeder
- ✅ **Đã thêm vào DatabaseSeeder.php**
- ✅ **Thứ tự chạy đúng**: Sau SuatChieuSeeder, DoAnSeeder, ComboSeeder
- ✅ **Dependencies**: GheNgoiSeeder, DoAnSeeder, ComboSeeder đã được kích hoạt

## Thống kê dữ liệu đã tạo

### Tổng quan đơn hàng
- **Tổng số đơn hàng**: 100
- **Tổng doanh thu**: 14,293,000 VND (chỉ tính đơn đã thanh toán + đã xuất vé)
- **Số ghế đã đặt**: 123 ghế
- **Đơn hàng có combo**: 34 đơn
- **Đơn hàng có đồ ăn lẻ**: 24 đơn

### Phân bố theo trạng thái
- **Đã thanh toán**: 31 đơn (31%)
- **Đã xuất vé**: 21 đơn (21%)
- **Chờ thanh toán**: 32 đơn (32%)
- **Đã hủy**: 16 đơn (16%)

### Phân bố theo phương thức thanh toán
- ZaloPay, VNPay, MoMo, Banking, COD được phân bố ngẫu nhiên

## Cách sử dụng

### Chạy seeder đơn lẻ
```bash
php artisan db:seed --class=DatVeSeeder
```

### Chạy tất cả seeder
```bash
php artisan db:seed
```

### Kiểm tra dữ liệu
```bash
php artisan tinker
>>> App\Models\DatVe::count()
>>> App\Models\DatVe::where('trang_thai', 'Đã thanh toán')->sum('tong_tien')
```

## Lưu ý kỹ thuật

### Dependencies cần thiết
1. **UserSeeder**: Tạo khách hàng
2. **SuatChieuSeeder**: Tạo suất chiếu
3. **GheNgoiSeeder**: Tạo ghế ngồi
4. **DoAnSeeder**: Tạo đồ ăn
5. **ComboSeeder**: Tạo combo
6. **KhuyenMaiSeeder**: Tạo khuyến mãi (tùy chọn)

### Cấu trúc bảng liên quan
- `dat_ves`: Thông tin đơn hàng chính
- `chi_tiet_dat_ves`: Chi tiết ghế đã đặt
- `dat_ve_combo`: Combo trong đơn hàng
- `dat_ve_do_an`: Đồ ăn trong đơn hàng
- `ghe_ngois`: Cập nhật trạng thái ghế

### Xử lý lỗi
- Bỏ qua đơn hàng nếu không có ghế trống
- Ghi log lỗi và tiếp tục tạo đơn hàng khác
- Đảm bảo tính toàn vẹn dữ liệu

## Kết quả
✅ **Hoàn thành 100% yêu cầu**:
- Tạo 100 đơn hàng ảo với dữ liệu đa dạng
- Phân bố thời gian trong 30 ngày gần đây  
- Đảm bảo tính nhất quán và không trùng lặp
- Kích hoạt thành công trong DatabaseSeeder
- Dữ liệu sẵn sàng cho thống kê và báo cáo
