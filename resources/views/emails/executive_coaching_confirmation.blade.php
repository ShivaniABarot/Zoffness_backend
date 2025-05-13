<!DOCTYPE html>
<html>
<head>
    <title>Executive Coaching Confirmation</title>
</head>
<body>
    <h2>Executive Coaching Registration Confirmation</h2>

    <p>Dear Parent and Student,</p>

    <p>Thank you for registering for the Executive Function Coaching program.</p>

    <p><strong>Student Name:</strong> {{ $studentName }}</p>
    <p><strong>School:</strong> {{ $school }}</p>
    <p><strong>Package Type:</strong> {{ $packageType }}</p>
    <p><strong>Total Amount:</strong> ${{ number_format($subtotal, 2) }}</p>

    <p>We look forward to working with you!</p>

    <p>Regards,<br>Your Team</p>
</body>
</html>
