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
    <meta charset="UTF-8">
    <title>Welcome to Our Application</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f9fc;
            margin: 0;
            padding: 20px;
        }
        .email-container {
            max-width: 600px;
            margin: auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }
        h1 {
            color: #2c3e50;
        }
        p {
            color: #555;
            line-height: 1.6;
        }
        .footer {
            margin-top: 30px;
            font-size: 13px;
            color: #999;
            text-align: center;
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
    <div class="email-container">
        <h1>Welcome to Our Application</h1>
        <p>Dear {{ $user->username ?? 'User' }},</p>

        <p>Thank you for registering with us. We are thrilled to welcome you to our community! Our goal is to support your journey every step of the way, and we’re excited to have you on board.</p>

        <p>If you have any questions or need assistance, feel free to reply to this email — we're always happy to help.</p>

        <p>Best regards,<br>
        The Zoffness College Prep Team</p>

        <div class="footer">
            &copy; 2025 Zoffness College Prep. All rights reserved.
        </div>
    </div>
</body>
</html>
