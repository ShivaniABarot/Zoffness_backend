<!DOCTYPE html>
<html>
<head>
    <title>College Admission Confirmation</title>
</head>
<body>
    <h2>College Admissions Counseling Registration Confirmation</h2>

    <p>Dear Parent and Student,</p>

    <p>Thank you for registering for our College Admissions Counseling services.</p>

    <p><strong>Student Name:</strong> {{ $studentName }}</p>
    <p><strong>School:</strong> {{ $school ?? 'N/A' }}</p>
    <p><strong>Total Amount:</strong> ${{ number_format($subtotal ?? 0, 2) }}</p>

    <p>We are excited to help your child on their college journey.</p>

    <p>Warm regards,<br>Your Team</p>
</body>
</html>
