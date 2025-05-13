<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Enrollment Confirmation</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 20px;
        }
        .email-container {
            max-width: 600px;
            margin: auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        h2 {
            color: #2c3e50;
            border-bottom: 1px solid #e0e0e0;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        p {
            color: #444;
            line-height: 1.6;
            margin: 10px 0;
        }
        .details {
            margin: 20px 0;
            background-color: #f9fafb;
            padding: 15px 20px;
            border-left: 4px solid #2c3e50;
            border-radius: 4px;
        }
        .details p {
            margin: 6px 0;
            font-weight: 500;
        }
        .footer {
            margin-top: 30px;
            font-size: 13px;
            color: #888;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <h2>Enrollment Confirmation</h2>

        <p>Dear Parent and Student,</p>

        <p>Thank you for enrolling in our program. Please find the enrollment details below:</p>

        <div class="details">
            <p><strong>Student Name:</strong> {{ $studentName }}</p>
            <p><strong>School:</strong> {{ $school }}</p>
            <p><strong>Packages:</strong> {{ $packages }}</p>
            <p><strong>Total Amount:</strong> ${{ number_format($totalAmount, 2) }}</p>
            <p><strong>Payment Status:</strong> {{ $paymentStatus }}</p>
        </div>

        <p>We look forward to your participation and are excited to begin this journey with you!</p>

        <p>Best regards,<br>
        <strong>Your Team</strong></p>

        <div class="footer">
            &copy; 2025 Your Company Name. All rights reserved.
        </div>
    </div>
</body>
</html>
