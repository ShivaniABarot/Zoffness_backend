w<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Password Reset</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f1f4f9;
            margin: 0;
            padding: 30px 20px;
        }
        .email-container {
            max-width: 620px;
            margin: auto;
            background-color: #ffffff;
            padding: 35px 40px;
            border-radius: 10px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.06);
            border-top: 6px solid #004c97;
        }
        h2 {
            color: #004c97;
            font-size: 24px;
            margin-bottom: 10px;
        }
        p {
            color: #333;
            font-size: 15px;
            line-height: 1.6;
            margin: 12px 0;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color:rgb(241, 243, 245);
            color: #ffffff;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 500;
            margin: 20px 0;
        }
        .signature {
            margin-top: 25px;
        }
        @media only screen and (max-width: 600px) {
            .email-container {
                padding: 25px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <h2>Reset Your Password</h2>
        <p>Hello {{ $user->firstname ?? 'User' }},</p>
        <p>We received a request to reset your password for your zoffness College Prep account.</p>
        <p>Please click the button below to change your password:</p>
        <a href="{{ $resetUrl }}" class="button">Change Password</a>
        <p>If you did not request a password reset, please ignore this email or contact our support team at 
            <a href="mailto:info@zoffnesscollegeprep.com">info@zoffnesscollegeprep.com</a>.
        </p>
        <div class="signature">
            <p>Best regards,</p>
            <p><strong>Zoffness College Prep</strong></p>
        </div>
    </div>
</body>
</html>
