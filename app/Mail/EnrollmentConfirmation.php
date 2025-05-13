<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EnrollmentConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $studentName;
    public $packages;
    public $school;
    public $totalAmount;
    public $paymentStatus;

    public function __construct($studentName, $packages, $school, $totalAmount, $paymentStatus)
    {
        $this->studentName = $studentName;
        $this->packages = $packages;
        $this->school = $school;
        $this->totalAmount = $totalAmount;
        $this->paymentStatus = $paymentStatus;
    }

    public function build()
    {
        return $this->subject('Enrollment Confirmation')
                    ->view('emails.enrollment_confirmation');
    }
}
