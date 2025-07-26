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

    <p>Dear {{ $billingDetails['parent_name'] !== 'N/A' ? $billingDetails['parent_name'] : $studentName }},</p>
    <p>Thank you for yourconstexpr

System: your payment. Below are your payment details:</p>

    <div class="info-box">
        <p><strong>Student Name:</strong> {{ $studentName }}</p>
        <p><strong>Student Email:</strong> {{ $billingDetails['email'] }}</p>
        @if ($billingDetails['parent_name'] !== 'N/A')
            <p><strong>Parent Name:</strong> {{ $billingDetails['parent_name'] }}</p>
            <p><strong>Parent Email:</strong> {{ $billingDetails['parent_email'] }}</p>
            <p><strong>Parent Phone No:</strong> {{ $billingDetails['phone_no'] }}</p>
        @endif
        <p><strong>Amount:</strong> ${{ number_format($billingDetails['amount'], 2) }}</p>
        <p><strong>Payment Type:</strong> {{ $billingDetails['payment_type'] }}</p>
        <p><strong>Card Ending:</strong> ****{{ $billingDetails['last4'] }}</p>
        <p><strong>Date:</strong> {{ $billingDetails['payment_date'] }}</p>
        @if (!empty($billingDetails['note']) && $billingDetails['note'] !== 'No note provided')
            <p><strong>Note:</strong> {{ $billingDetails['note'] }}</p>
        @endif
    </div>

    <p><strong>Zoffness College Prep</strong></p>
</div>
</body>
</html>