<!DOCTYPE html>
<html>
<head>
    <title>Enrollment Confirmation</title>
</head>
<body>
    <h2>Enrollment Confirmation</h2>

    <p>Dear Parent and Student,</p>

    <p>Thank you for enrolling in our program.</p>

    <p><strong>Student Name:</strong> {{ $studentName }}</p>
    <p><strong>School:</strong> {{ $school }}</p>
    <p><strong>Packages:</strong> {{ $packages }}</p>
    <p><strong>Total Amount:</strong> ${{ number_format($totalAmount, 2) }}</p>
    <p><strong>Payment Status:</strong> {{ $paymentStatus }}</p>

    <p>We look forward to your participation!</p>

    <p>Regards,<br>Your Team</p>
</body>
</html>
