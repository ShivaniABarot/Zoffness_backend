<!DOCTYPE html>
<html>
<head>
    <title>Welcome to Zoffness Academy</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #4a86e8;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .content {
            padding: 20px;
            border: 1px solid #ddd;
            border-top: none;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Welcome to Zoffness Academy</h1>
    </div>
    <div class="content">
        <p>Dear {{ $username }},</p>
        <p>Thank you for registering with Zoffness Academy! Your account has been successfully created.</p>
        <p>You can now log in to access our services and resources. If you have any questions or need assistance, please don't hesitate to contact our support team.</p>
        <p>Best Regards,<br>The Zoffness Academy Team</p>
    </div>
    <div class="footer">
        <p>&copy; {{ date('Y') }} Zoffness Academy. All rights reserved.</p>
        <p>This is an automated email, please do not reply to this message.</p>
    </div>
</body>
</html>
