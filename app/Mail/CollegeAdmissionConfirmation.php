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

    public function __construct($studentName, $school, $subtotal)
    {
        $this->studentName = $studentName;
        $this->school = $school;
        $this->subtotal = $subtotal;
    }

    public function build()
    {
        return $this->subject('College Admission Counseling Confirmation')
                    ->view('emails.college_admission_confirmation');
    }
}
