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

        h3 {
            color: #004c97;
            font-size: 18px;
            margin: 20px 0 10px;
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
            margin: 15px 0;
        }

        .details p {
            font-weight: 500;
            margin: 8px 0;
            color: #1a1a1a;
        }

        .details p strong {
            display: inline-block;
            width: 160px;
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
        <!-- Logo -->
        <div style="text-align: center;">
            <img src="cid:zoffnesscollegeprep-logo.png" alt="Zoffness College Prep Logo"
                style="max-width: 180px; margin-bottom: 20px; display: inline-block;">
        </div>

        <h2>Practice Test Booking Confirmation</h2>

        <p>
            @if($recipientType === 'admin')
                Hello Admin Team,<br><br>
                A new student has registered for a practice test. Below are the registration details:
            @else
                Dear {{ $recipientName }},<br><br>
                Thank you for registering for the practice test with Zoffness College Prep. Below are your registration
                details:
            @endif
        </p>

        <h3>Personal Information</h3>
        <div class="details">
            <p><strong>Student Name:</strong> {{ $studentName }}</p>
            <p><strong>Student Email:</strong> {{ $studentEmail }}</p> {{-- ✅ NEW --}}
            @if($school)
                <p><strong>School:</strong> {{ $school }}</p>
            @endif
            @if($parentDetails['name'])
                <p><strong>Parent Name:</strong> {{ $parentDetails['name'] }}</p>
            @endif
            @if($parentDetails['phone'])
    @php
        $digits = preg_replace('/\D/', '', $parentDetails['phone']); // remove non-digits
        if(strlen($digits) === 10) {
            $formattedPhone = '(' . substr($digits, 0, 3) . ') ' . substr($digits, 3, 3) . '-' . substr($digits, 6);
        } else {
            $formattedPhone = $parentDetails['phone']; // fallback
        }
    @endphp
    <p><strong>Parent Phone:</strong> {{ $formattedPhone }}</p>
@endif

            <!-- @if($parentDetails['phone'])
                <p><strong>Parent Phone:</strong> {{ $parentDetails['phone'] }}</p>
            @endif -->
            @if($parentDetails['email'])
                <p><strong>Parent Email:</strong> {{ $parentDetails['email'] }}</p>
            @endif
        </div>

        <h3>Test Details</h3>
        <div class="details">
            <p><strong>Test Type(s):</strong> {{ $testTypes }}</p>
            <p><strong>Test Date(s):</strong>
                @if(is_array($date))
                    @foreach($date as $d)
                        {{ \Carbon\Carbon::parse($d)->format('m-d-Y') }}@if(!$loop->last), @endif
                    @endforeach
                @elseif($date)
                    {{ \Carbon\Carbon::parse($date)->format('m-d-Y') }}
                @else
                    N/A
                @endif
            </p>
        </div>

        <h3>Payment Information</h3>
        <div class="details">
            <p><strong>Total Amount:</strong> ${{ number_format($subtotal, 2) }}</p>
            <p><strong>Payment Status:</strong> {{ $paymentStatus }}</p>
            <p><strong>Payment Date:</strong> {{ $paymentDate }}</p>
        </div>

        @if($recipientType !== 'admin')
            <p>We look forward to helping you succeed in your academic journey!</p>
        @endif

        <div class="signature">
            <p>Best regards,</p>
            <p><strong>Zoffness College Prep</strong></p>
        </div>
    </div>
</body>

</html>