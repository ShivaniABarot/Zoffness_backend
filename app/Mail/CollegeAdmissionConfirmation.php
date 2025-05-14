<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CollegeAdmissionConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $studentName;
    public $school;
    public $subtotal;
    public $recipientName;
    public $recipientType;

    public function __construct($studentName, $school, $subtotal, $recipientName, $recipientType)
    {
        $this->studentName = $studentName;
        $this->school = $school;
        $this->subtotal = $subtotal;
        $this->recipientName = $recipientName;
        $this->recipientType = $recipientType;
    }

    public function build()
    {
        return $this->subject('College Admission Counseling Confirmation')
                    ->view('emails.college_admission_confirmation');
    }
}
