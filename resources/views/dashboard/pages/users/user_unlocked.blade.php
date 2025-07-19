<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Tài khoản đã được mở khóa</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f7;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }
        .email-header {
            background-color: #4caf50;
            color: #ffffff;
            padding: 24px;
            text-align: center;
        }
        .email-content {
            padding: 32px 24px;
            color: #333333;
        }
        .email-footer {
            background-color: #f1f1f1;
            padding: 16px;
            text-align: center;
            font-size: 13px;
            color: #777777;
        }
        .btn {
            display: inline-block;
            margin-top: 24px;
            padding: 12px 24px;
            background-color: #4caf50;
            color: #ffffff;
            text-decoration: none;
            border-radius: 6px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h2>Tài khoản đã được mở khóa</h2>
        </div>
        <div class="email-content">
            <p>Xin chào <strong>{{ $user->name }}</strong>,</p>

            <p>Tài khoản của bạn đã được <strong>mở khóa</strong> và có thể sử dụng lại bình thường.</p>

            <p>Nếu bạn có bất kỳ câu hỏi nào, vui lòng liên hệ với chúng tôi.</p>

            <a href="{{ url('/') }}" class="btn">Truy cập Website</a>
        </div>
        <div class="email-footer">
            Trân trọng,<br>
            Đội ngũ hỗ trợ
        </div>
    </div>
</body>
</html>
