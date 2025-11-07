<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .alert { background: #fee; border-left: 4px solid #f00; padding: 15px; margin: 20px 0; }
        .info { background: #f9f9f9; padding: 10px; margin: 10px 0; border-radius: 5px; }
        .label { font-weight: bold; color: #333; }
        .value { color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <h1 style="color: #c00;">🚨 CRITICAL ERROR ALERT</h1>
        
        <div class="alert">
            <h2>Payment Succeeded But Registration Failed</h2>
            <p><strong>Immediate Action Required!</strong></p>
        </div>

        <div class="info">
            <p><span class="label">Error Type:</span> <span class="value">{{ $error->error_type }}</span></p>
            <p><span class="label">Error Message:</span> <span class="value">{{ $error->error_message }}</span></p>
            <p><span class="label">Severity:</span> <span class="value">{{ strtoupper($error->severity) }}</span></p>
        </div>

        <h3>Payment Information:</h3>
        <div class="info">
            <p><span class="label">Stripe Payment ID:</span> <span class="value">{{ $error->stripe_payment_id }}</span></p>
            <p><span class="label">Customer Email:</span> <span class="value">{{ $error->user_email }}</span></p>
            <p><span class="label">Student Name:</span> <span class="value">{{ $error->student_name }}</span></p>
        </div>

        @if($error->form_data)
        <h3>Registration Details:</h3>
        <div class="info">
            <p><span class="label">Tests:</span> <span class="value">{{ $error->form_data['test_types'] ?? 'N/A' }}</span></p>
            <p><span class="label">Total Amount:</span> <span class="value">${{ $error->form_data['total_amount'] ?? 'N/A' }}</span></p>
            <p><span class="label">School:</span> <span class="value">{{ $error->form_data['school'] ?? 'N/A' }}</span></p>
        </div>
        @endif

        <h3>Next Steps:</h3>
        <ol>
            <li>Check Stripe dashboard for payment: <a href="https://dashboard.stripe.com">View Payment</a></li>
            <li>Manually create registration in database</li>
            <li>Send confirmation email to customer</li>
            <li>Mark error as resolved in admin panel</li>
        </ol>

        <p style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd; color: #999; font-size: 12px;">
            Logged at: {{ $error->created_at->format('Y-m-d H:i:s') }}<br>
            Log ID: #{{ $error->id }}
        </p>
    </div>
</body>
</html>
