<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            background-color: #4a5568;
            color: #ffffff;
            padding: 15px 20px;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 30px 20px;
        }
        .content p {
            margin-top: 0;
        }
        .footer {
            text-align: center;
            padding: 20px;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Phản hồi từ PolyFlix</h1>
        </div>
        <div class="content">
            <p>Chào bạn,</p>
            <p>Cảm ơn bạn đã liên hệ với chúng tôi. Dưới đây là phản hồi cho yêu cầu của bạn:</p>
            <hr>
            <p><strong>Tiêu đề:</strong> {{ $subject }}</p>
            <div>
                <strong>Nội dung:</strong>
                <p>{!! nl2br(e($replyMessage)) !!}</p>
            </div>
            <hr>
            <p>Nếu bạn có bất kỳ câu hỏi nào khác, xin vui lòng liên hệ lại với chúng tôi.</p>
            <p>Trân trọng,<br>Đội ngũ PolyFlix</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} PolyFlix. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
