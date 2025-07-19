<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Thông báo khóa tài khoản</title>
</head>
<body>
    <h2>Xin chào {{ $user->name }},</h2>

    <p>Tài khoản của bạn đã bị <strong>khóa</strong> vì lý do sau:</p>

    <ul>
        <li><strong>Lý do:</strong> {{ $reason }}</li>
        <li><strong>Ghi chú:</strong> {{ $note ?? 'Không có ghi chú.' }}</li>
    </ul>

    <p>Nếu bạn cần hỗ trợ, vui lòng liên hệ với quản trị viên.</p>

    <p>Trân trọng,<br>Đội ngũ hỗ trợ</p>
</body>
</html>
