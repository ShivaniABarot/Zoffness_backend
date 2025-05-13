<!DOCTYPE html>
<html>
<head>
    <title>SAT/ACT Course Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            padding: 20px;
            background-color: #f9f9f9;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 30px;
        }
        h2 {
            color: #2c3e50;
        }
        p {
            margin: 10px 0;
        }
        ul {
            padding-left: 20px;
        }
        .footer {
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <h2>SAT/ACT Course Registration Confirmation</h2>

        <p>Dear Parent and Student,</p>

        <p>Thank you for registering for the SAT/ACT course. Below are your registration details:</p>

        <p><strong>Student Name:</strong> {{ $studentName }}</p>
        <p><strong>School:</strong> {{ $school }}</p>
        <p><strong>Package:</strong> {{ $packageName }}</p>

        <p><strong>Courses Enrolled:</strong></p>
        <ul>
            @foreach($courses as $course)
                <li>{{ $course['name'] }} – ${{ number_format($course['price'], 2) }}</li>
            @endforeach
        </ul>

        <p><strong>Total Amount:</strong> ${{ number_format($totalAmount, 2) }}</p>
        <p><strong>Payment Status:</strong> {{ $paymentStatus }}</p>

        <p>We look forward to helping you succeed in your SAT/ACT journey!</p>

        <p class="footer">Best regards,<br><strong>Your Team</strong></p>
    </div>
</body>
</html>
