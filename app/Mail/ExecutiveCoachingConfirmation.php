<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ExecutiveCoachingConfirmation extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $studentName;
    public $school;
    public $packageName; // ✅ use packageName consistently
    public $subtotal;
    public $recipientName;
    public $recipientType;
    public $examDate;
    public $parentDetails;
    public $stripeId;
    public $paymentStatus;
    public $paymentDate;
    public $stripeDetails;
    public $studentEmail;

    public function __construct(
        $studentName,
        $school,
        $packageName, // ✅ renamed here
        $subtotal,
        $recipientName,
        $recipientType,
        $examDate,
        $parentDetails,
        $stripeId,
        $paymentStatus,
        $paymentDate,
        $stripeDetails,
        $studentEmail
    ) {
        $this->studentName   = $studentName;
        $this->school        = $school;
        $this->packageName   = $packageName; // ✅ assign correctly
        $this->subtotal      = (float) $subtotal;
        $this->recipientName = $recipientName;
        $this->recipientType = $recipientType;
        $this->examDate      = $examDate;
        $this->parentDetails = $parentDetails;
        $this->stripeId      = $stripeId;
        $this->paymentStatus = $paymentStatus;
        $this->paymentDate   = $paymentDate ?: now()->format('m-d-Y');
        $this->stripeDetails = $stripeDetails ?: [
            'payment_method_type' => 'N/A',
            'last4' => 'N/A',
            'status' => 'N/A',
        ];
        $this->studentEmail  = $studentEmail;
    }

    public function build()
    {
        return $this->subject('Executive Coaching Registration Confirmation')
                    ->view('emails.executive_coaching_confirmation')
                    ->attach(public_path('zoffnesscollegeprep-logo.png'), [
                        'as' => 'zoffnesscollegeprep-logo.png',
                        'mime' => 'image/png',
                    ])
                    ->withSwiftMessage(function ($message) {
                        $message->embed(public_path('zoffnesscollegeprep-logo.png'), 'zoffnesscollegeprep-logo.png');
                    });
    }
}
