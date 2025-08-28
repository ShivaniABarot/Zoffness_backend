<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CollegeEssayConfirmation extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $studentName;
    public $school;
    public $subtotal;
    public $recipientName;
    public $recipientType;
    public $packages;
    public $examDate;
    public $parentDetails;
    public $stripeId;
    public $paymentStatus;
    public $paymentDate;
    public $stripeDetails;
    public $sessions;
    public $studentEmail;

    public function __construct(
        $studentName,
        $school,
        $subtotal,
        $recipientName,
        $recipientType,
        $packages,
        $examDate,
        $parentDetails,
        $stripeId,
        $paymentStatus,
        $paymentDate,
        $stripeDetails,
        $sessions,
        $studentEmail
    ) {
        $this->studentName = $studentName;
        $this->school = $school;
        $this->subtotal = (float) $subtotal;
        $this->recipientName = $recipientName;
        $this->recipientType = $recipientType;
        $this->packages = $packages;
        $this->examDate = $examDate;
        $this->parentDetails = $parentDetails;
        $this->stripeId = $stripeId;
        $this->paymentStatus = $paymentStatus;
        $this->paymentDate = $paymentDate ?: now()->format('m-d-Y');
        $this->stripeDetails = $stripeDetails ?: [
            'payment_method_type' => 'N/A',
            'last4' => 'N/A',
            'status' => 'N/A',
        ];
        $this->sessions = $sessions;
        $this->studentEmail = $studentEmail;
    }

    public function build()
    {
        return $this->subject('College Essay Service Confirmation')
                    ->view('emails.college_essay_confirmation')
                    ->attach(public_path('zoffnesscollegeprep-logo.png'), [
                        'as' => 'zoffnesscollegeprep-logo.png',
                        'mime' => 'image/png',
                    ])
                    ->withSwiftMessage(function ($message) {
                        $message->embed(public_path('zoffnesscollegeprep-logo.png'), 'zoffnesscollegeprep-logo.png');
                    });
    }
}