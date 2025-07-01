<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Welcome to Zoffness College Prep</title>
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
    <div class="email-container">
        <h1>Welcome to Zoffness College Prep</h1>
        <p>Dear {{ $username }},</p>
        <p>Thank you for registering with Zoffness College Prep! Your account has been successfully created.</p>
        <p>You can now log in to access our services and resources. If you have any questions or need assistance, please don't hesitate to contact our support team.</p>
        <p>Best Regards,<br>The Zoffness College Prep Team</p>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Zoffness College Prep. All rights reserved.</p>
            <p>This is an automated email, please do not reply to this message.</p>
        </div>
    </div>
</body>
</html>