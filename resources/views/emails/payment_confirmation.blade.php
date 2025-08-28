<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Payment Confirmation</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f9f9f9;
            padding: 20px;
        }

        .container {
            background: #fff;
            padding: 25px 30px;
            max-width: 600px;
            margin: auto;
            border-radius: 10px;
            border-top: 4px solid #004c97;
        }

        h2 {
            color: #004c97;
        }

        p {
            font-size: 15px;
            line-height: 1.6;
        }

        .info-box {
            background: #f1f5f9;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
        }

        .info-box p {
            margin: 6px 0;
        }

        .info-box strong {
            display: inline-block;
            width: 140px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div style="text-align:center;">
            <img src="cid:zoffnesscollegeprep-logo.png" alt="Logo" style="max-width:150px;">
        </div>
        <h2>Payment Confirmation</h2>

        <p>
            @if($isAdmin)
                Dear Admin,
            @else
                Dear {{ $payment->parent_first_name ?? $payment->student_first_name }},
            @endif
        </p>



        <div class="info-box">
            <p><strong>Student Name:</strong> {{ $payment->student_first_name }} {{ $payment->student_last_name }}</p>
            <p><strong>Student Email:</strong> {{ $payment->email }}</p>

            @if (!empty($payment->parent_first_name) || !empty($payment->parent_last_name))
                <p><strong>Parent Name:</strong> {{ $payment->parent_first_name }} {{ $payment->parent_last_name }}</p>
                @php
    $digits = preg_replace('/\D/', '', $payment->parent_phone ?? '');
    if (strlen($digits) === 10) {
        $formattedPhone = '(' . substr($digits, 0, 3) . ') ' . substr($digits, 3, 3) . '-' . substr($digits, 6);
    } else {
        $formattedPhone = $payment->parent_phone ?? 'N/A';
    }
@endphp

<p><strong>Parent Phone No:</strong> {{ $formattedPhone }}</p>

                <!-- <p><strong>Parent Phone No:</strong> {{ $payment->parent_phone ?? 'N/A' }}</p> -->
            @endif

            <p><strong>Amount:</strong> ${{ number_format($payment->payment_amount, 2) }}</p>
            <p><strong>Payment Type:</strong> {{ $payment->payment_type }}</p>
            <p><strong>Cardholder:</strong> {{ $payment->cardholder_name }}
                (****{{ $payment->card_number }})
            </p>
            <p><strong>Date:</strong> {{ now()->format('m-d-Y') }}</p>

            @if (!empty($payment->note))
                <p><strong>Note:</strong> {{ $payment->note }}</p>
            @endif

            <p><strong>Address:</strong> {{ $payment->bill_address }}, {{ $payment->city }}, {{ $payment->state }}
                {{ $payment->zip_code }}</p>
        </div>

        <p><strong>Zoffness College Prep</strong></p>
    </div>
</body>

</html>