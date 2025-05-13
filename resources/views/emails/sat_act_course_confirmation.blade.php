<!DOCTYPE html>
<html>
<head>
    <title>SAT/ACT Course Confirmation</title>
</head>
<body>
    <h2>SAT/ACT Course Registration Confirmation</h2>

    <p>Dear Parent and Student,</p>

    <p>Thank you for registering for the SAT/ACT course.</p>

    <p><strong>Student Name:</strong> {{ $studentName }}</p>
    <p><strong>School:</strong> {{ $school }}</p>
    <p><strong>Package:</strong> {{ $packageName }}</p>

    <p><strong>Courses:</strong></p>
    <ul>
        @foreach($courses as $course)
            <li>{{ $course['name'] }} - ${{ number_format($course['price'], 2) }}</li>
        @endforeach
    </ul>

    <p><strong>Total Amount:</strong> ${{ number_format($totalAmount, 2) }}</p>
    <p><strong>Payment Status:</strong> {{ $paymentStatus }}</p>

    <p>We look forward to helping you succeed!</p>

    <p>Regards,<br>Your Team</p>
</body>
</html>
