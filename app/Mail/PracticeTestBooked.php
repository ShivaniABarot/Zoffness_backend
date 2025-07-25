<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PracticeTestBooked extends Mailable
{
    use Queueable, SerializesModels;

    public $studentName;
    public $testTypes;
    public $date;
    public $subtotal;
    public $recipientName;
    public $recipientType;
    public $school;
    public $parentDetails;
    public $stripeId;
    public $paymentStatus;
    public $paymentDate;
    public $stripeDetails;

    public function __construct(
        $studentName,
        $testTypes,
        $date,
        $subtotal,
        $recipientName,
        $recipientType,
        $school,
        $parentDetails,
        $stripeId,
        $paymentStatus,
        $paymentDate,
        $stripeDetails
    ) {
        $this->studentName = $studentName;
        $this->testTypes = $testTypes;
        $this->date = $date;
        $this->subtotal = $subtotal;
        $this->recipientName = $recipientName;
        $this->recipientType = $recipientType;
        $this->school = $school;
        $this->parentDetails = $parentDetails;
        $this->stripeId = $stripeId;
        $this->paymentStatus = $paymentStatus;
        $this->paymentDate = $paymentDate ?: now()->format('m-d-Y');
        $this->stripeDetails = $stripeDetails ?: [
            'payment_method_type' => 'N/A',
            'last4' => 'N/A',
            'status' => 'N/A',
        ];
    }

    public function build()
    {
        return $this->subject('Practice Test Booking Confirmation')
                    ->view('emails.practice_test_booked')
                    ->attach(public_path('zoffnesscollegeprep-logo.png'), [
                        'as' => 'zoffnesscollegeprep-logo.png',
                        'mime' => 'image/png',
                    ])
                    ->withSwiftMessage(function ($message) {
                        $message->embed(public_path('zoffnesscollegeprep-logo.png'), 'zoffnesscollegeprep-logo.png');
                    });
    }
}