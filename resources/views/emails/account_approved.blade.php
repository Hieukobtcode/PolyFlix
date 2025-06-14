<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Phê duyệt tài khoản</title>
</head>
<body>
    <h2>Chào bạn,</h2>
    <p>Tài khoản quản lý chi nhánh của bạn đã được phê duyệt.</p>

    <p>Thông tin đăng nhập:</p>
    <ul>
        <li><strong>Email:</strong> {{ $email }}</li>
        <li><strong>Mật khẩu:</strong> {{ $password }}</li>
    </ul>

    <p>Vui lòng đăng nhập và đổi mật khẩu sau lần đăng nhập đầu tiên.</p>

    <p>Trân trọng,<br>Đội ngũ quản trị hệ thống</p>
</body>
</html>
