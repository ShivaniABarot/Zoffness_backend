<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;

class PracticeTestBooked extends Mailable
{
    use Queueable, SerializesModels;

    public $studentName;
    public $testTypes;
    public $dates; 
    public $subtotal;
    public $recipientName;
    public $recipientType;
    public $school;
    public $parentDetails;
    public $stripeId;
    public $paymentStatus;
    public $paymentDate;
    public $stripeDetails;
    public $studentEmail;

    public function __construct(
        $studentName,
        $testTypes,
        $dates,
        $subtotal,
        $recipientName,
        $recipientType,
        $school,
        $parentDetails,
        $stripeId,
        $paymentStatus,
        $paymentDate,
        $stripeDetails,
        $studentEmail
    ) {
        $this->studentName = $studentName;
        $this->testTypes = $testTypes;

        // Ensure $dates is an array, handling JSON strings or single values
        if (is_string($dates) && (strpos($dates, '[') === 0 || strpos($dates, '{') === 0)) {
            $decodedDates = json_decode($dates, true);
            $this->dates = is_array($decodedDates) ? $decodedDates : [$dates];
        } else {
            $this->dates = Arr::wrap($dates); // Wrap non-array values into an array
        }

        // Log dates for debugging
        \Log::info('PracticeTestBooked constructed', [
            'raw_dates' => $dates,
            'processed_dates' => $this->dates
        ]);

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
        $this->studentEmail = $studentEmail;
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