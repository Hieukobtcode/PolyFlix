# 🎯 Hướng dẫn Setup ZaloPay cho PolyFlix

## 📋 Tổng quan

Hướng dẫn này sẽ giúp bạn tích hợp thanh toán ZaloPay vào dự án Laravel PolyFlix chạy trên localhost.

## 🛠 Yêu cầu hệ thống

-   Laravel 10+
-   PHP 8.1+
-   Ngrok (để tạo public URL cho callback)
-   ZaloPay Sandbox Account

## 📦 Bước 1: Cài đặt Ngrok

### Windows

```bash
# Tải ngrok từ https://ngrok.com/download
# Giải nén và add vào PATH hoặc chạy trực tiếp

# Đăng ký tài khoản tại https://dashboard.ngrok.com/signup
# Lấy auth token và cấu hình
ngrok config add-authtoken YOUR_AUTH_TOKEN
```

### MacOS (với Homebrew)

```bash
brew install ngrok/ngrok/ngrok
ngrok config add-authtoken YOUR_AUTH_TOKEN
```

### Linux

```bash
# Tải và cài đặt
wget https://bin.equinox.io/c/4VmDzA7iaHb/ngrok-stable-linux-amd64.zip
unzip ngrok-stable-linux-amd64.zip
sudo mv ngrok /usr/local/bin

# Cấu hình auth token
ngrok config add-authtoken YOUR_AUTH_TOKEN
```

## 🚀 Bước 2: Khởi động Ngrok

```bash
# Mở terminal mới và chạy (giả sử Laravel chạy trên port 8000)
ngrok http 8000

# Hoặc nếu Laravel chạy trên port 80
ngrok http 80

# Hoặc chỉ định domain cụ thể
ngrok http --region=ap --hostname=your-subdomain.ngrok.io 8000
```

**Kết quả sẽ hiển thị:**

```
ngrok by @inconshreveable

Session Status                online
Account                       your-email@gmail.com
Update                        update available (version 2.3.40, Ctrl-C to update)
Version                       2.3.35
Region                        Asia Pacific (ap)
Web Interface                 http://127.0.0.1:4040
Forwarding                    https://abc123.ngrok.io -> http://localhost:8000
Forwarding                    http://abc123.ngrok.io -> http://localhost:8000

Connections                   ttl     opn     rt1     rt5     p50     p90
                              0       0       0.00    0.00    0.00    0.00
```

**Lưu lại URL:** `https://abc123.ngrok.io` (thay đổi theo ngrok của bạn)

## ⚙️ Bước 3: Cấu hình .env

Thêm các dòng sau vào file `.env`:

```env
# ZaloPay Configuration (Sandbox)
ZALOPAY_APP_ID=2553
ZALOPAY_KEY1=PcY4iZIKFCIdgZvA6ueMcMHHUbRLYjPL
ZALOPAY_KEY2=kLtgPl8HHhfvMuDHPwKfgfsY4Ydm9eIz
ZALOPAY_ENDPOINT=https://sb-openapi.zalopay.vn/v2/create
ZALOPAY_QUERY_ENDPOINT=https://sb-openapi.zalopay.vn/v2/query
ZALOPAY_CALLBACK_URL=https://YOUR_NGROK_URL.ngrok.io/api/payments/zalopay/callback
ZALOPAY_REDIRECT_URL=http://localhost:8000/thanh-toan/ket-qua
ZALOPAY_ENVIRONMENT=sandbox

# Ngrok Configuration
NGROK_ENABLED=true
NGROK_URL=https://YOUR_NGROK_URL.ngrok.io

# Payment Configuration
PAYMENT_DEFAULT_GATEWAY=zalopay
PAYMENT_TIMEOUT=900
PAYMENT_CURRENCY=VND
```

**⚠️ Quan trọng:** Thay `YOUR_NGROK_URL` bằng URL thực tế từ ngrok (ví dụ: `abc123`)

## 🔧 Bước 4: Cấu hình Database

Chạy migration để tạo bảng `transactions`:

```bash
php artisan migrate
```

## 🧪 Bước 5: Test ZaloPay

### A. Khởi động Laravel

```bash
php artisan serve --host=0.0.0.0 --port=8000
```

### B. Khởi động Ngrok (terminal khác)

```bash
ngrok http 8000
```

### C. Cập nhật Callback URL

1. Copy URL ngrok (ví dụ: `https://abc123.ngrok.io`)
2. Cập nhật `.env`:
    ```env
    ZALOPAY_CALLBACK_URL=https://abc123.ngrok.io/api/payments/zalopay/callback
    NGROK_URL=https://abc123.ngrok.io
    ```
3. Restart Laravel: `Ctrl+C` và `php artisan serve` lại

### D. Test Flow

1. Truy cập: `http://localhost:8000`
2. Đặt vé như bình thường
3. Chọn thanh toán ZaloPay
4. Sẽ chuyển hướng đến sandbox ZaloPay
5. Thanh toán với thông tin test
6. Callback sẽ được gọi về và cập nhật trạng thái

## 📱 Thông tin Test ZaloPay Sandbox

### Test User

```
Phone: 0963181714
OTP: 123456
```

### Test Cards

```
Số thẻ: 4111111111111111
Tên: NGUYEN VAN A
Tháng/Năm: 03/30
CVV: 123
```

## 🐛 Debug & Troubleshooting

### Kiểm tra Logs

```bash
tail -f storage/logs/laravel.log
```

### Kiểm tra Ngrok Web Interface

Mở: `http://127.0.0.1:4040` để xem:

-   Traffic requests
-   Response codes
-   Request/Response details

### Common Issues

#### 1. Callback không nhận được

-   ✅ Kiểm tra ngrok đang chạy
-   ✅ Kiểm tra URL callback trong .env
-   ✅ Kiểm tra firewall/antivirus
-   ✅ Xem ngrok web interface có nhận request không

#### 2. MAC verification failed

-   ✅ Kiểm tra ZALOPAY_KEY1 và ZALOPAY_KEY2
-   ✅ Đảm bảo không có space thừa trong .env
-   ✅ Restart Laravel sau khi thay đổi .env

#### 3. Transaction not found

-   ✅ Kiểm tra database connection
-   ✅ Chạy `php artisan migrate`
-   ✅ Kiểm tra app_trans_id format

## 🚀 Deploy Production

Khi deploy lên production:

1. **Cập nhật .env:**

```env
ZALOPAY_ENVIRONMENT=production
ZALOPAY_CALLBACK_URL=https://yourdomain.com/api/payments/zalopay/callback
ZALOPAY_REDIRECT_URL=https://yourdomain.com/thanh-toan/ket-qua
```

2. **Đăng ký ZaloPay Production:**

    - Liên hệ ZaloPay để đăng ký merchant thật
    - Nhận APP_ID, KEY1, KEY2 production
    - Cập nhật endpoint URLs

3. **SSL Certificate:**
    - Đảm bảo domain có SSL (https)
    - ZaloPay yêu cầu callback URL phải https

## 📞 Hỗ trợ

-   **ZaloPay Docs:** https://docs.zalopay.vn/
-   **Ngrok Docs:** https://ngrok.com/docs
-   **Laravel Docs:** https://laravel.com/docs

## 🎉 Kết thúc

Bây giờ bạn đã có hệ thống thanh toán ZaloPay hoàn chỉnh cho PolyFlix!

**Flow hoàn chỉnh:**

1. User chọn ghế + đồ ăn → Tạo đặt vé
2. Chuyển trang thanh toán → Chọn ZaloPay
3. Redirect đến ZaloPay → User thanh toán
4. ZaloPay callback → Cập nhật trạng thái
5. User redirect về → Hiển thị kết quả

**Happy Coding! 🚀**
