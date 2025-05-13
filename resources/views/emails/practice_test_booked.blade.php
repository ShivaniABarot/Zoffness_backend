<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Practice Test Booking Confirmation</title>
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

        .footer {
            margin-top: 40px;
            font-size: 12px;
            color: #777;
            text-align: center;
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
        <h2>Booking Confirmed!</h2>

        <p>Dear Parent and Student,</p>

        <p>Thank you for choosing Zoffness College Prep! We’re excited to support your student’s journey toward academic success. Below are the confirmed details of your practice test booking:</p>

        <div class="details">
            <p><strong>Student Name:</strong> {{ $studentName }}</p>
            <p><strong>Test Type(s):</strong> {{ $testTypes }}</p>
            <p><strong>Test Date:</strong> {{ $date }}</p>
            <p><strong>Total Amount:</strong> ${{ number_format($subtotal, 2) }}</p>
        </div>

        <p>If you have any questions, or if you'd like to make adjustments to your appointment, feel free to reach out. We're here to help!</p>

        <div class="signature">
            <p>Warm regards,</p>
            <p><strong>The Zoffness College Prep Team</strong></p>
        </div>

        <div class="footer">
            &copy; 2025 Zoffness College Prep &mdash; Empowering Futures. All rights reserved.
        </div>
    </div>
</body>
</html>
