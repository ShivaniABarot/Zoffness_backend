<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Schedule Confirmation</title>
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

        .details {
            background-color: #f7f9fc;
            border: 1px solid #e3e9f1;
            padding: 20px;
            border-radius: 6px;
            margin: 25px 0;
        }

        .details p {
            font-weight: 500;
            margin: 8px 0;
            color: #1a1a1a;
        }

        .details p strong {
            display: inline-block;
            width: 130px;
            color: #004c97;
        }

        .signature {
            margin-top: 25px;
        }

        @media only screen and (max-width: 600px) {
            .email-container {
                padding: 25px 20px;
            }

            .details p strong {
                width: 100%;
                display: block;
                margin-bottom: 4px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <h2>Consultation {{ $recipientRole === 'admin' ? 'Scheduled' : 'Confirmation' }}</h2>

        <p>Hello {{ $name }},</p>

        @if ($recipientRole === 'user')
            <p>Thank you for scheduling a consultation with us! Below are your appointment details:</p>
        @else
            <p>A new consultation has been scheduled. Details are below:</p>
        @endif

        <div class="details">
            <p><strong>Name:</strong> {{ $name }}</p>
            <p><strong>Email:</strong> {{ $email }}</p>
            <p><strong>Phone:</strong> {{ $phone }}</p>
            <p><strong>Date:</strong> {{ $date }}</p>
            <p><strong>Time Slot:</strong> {{ $timeSlot }}</p>
            <p><strong>Primary Interest:</strong> {{ $primaryInterest }}</p>
            <p><strong>Consultation Fees:</strong> ${{ number_format($fees, 2) }}</p>
        </div>

        <p>We look forward to speaking with you soon! If you have any questions before the consultation, feel free to reach out.</p>

        <div class="signature">
            <p>Warm regards,</p>
            <p><strong>Zoffness College Prep Team</strong></p>
        </div>
    </div>
</body>
</html>
