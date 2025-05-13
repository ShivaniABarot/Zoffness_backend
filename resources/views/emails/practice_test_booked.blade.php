<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Practice Test Booking Confirmation</title>
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
        }
        p {
            color: #444;
            line-height: 1.6;
        }
        .details {
            margin-top: 20px;
            margin-bottom: 20px;
        }
        .details p {
            margin: 8px 0;
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
        <h2>Practice Test Booking Confirmation</h2>

        <p>Dear Parent and Student,</p>

        <p>Thank you for booking your practice test with us! Below are the details of your booking:</p>

        <div class="details">
            <p><strong>Student Name:</strong> {{ $studentName }}</p>
            <p><strong>Test Type(s):</strong> {{ $testTypes }}</p>
            <p><strong>Test Date:</strong> {{ $date }}</p>
            <p><strong>Total Amount:</strong> ${{ number_format($subtotal, 2) }}</p>
        </div>

        <p>We wish the student the best of luck on the test! If you have any questions or need to make changes to your booking, please feel free to contact us.</p>

        <p>Best regards,<br>
        The Zoffness College Prep Team</p>

        <div class="footer">
            &copy; 2025 Zoffness College Prep. All rights reserved.
        </div>
    </div>
</body>
</html>
