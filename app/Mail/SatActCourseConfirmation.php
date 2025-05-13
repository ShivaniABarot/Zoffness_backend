<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SatActCourseConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $studentName;
    public $courses;
    public $school;
    public $packageName;
    public $totalAmount;
    public $paymentStatus;

    public function __construct($studentName, $courses, $school, $packageName, $totalAmount, $paymentStatus)
    {
        $this->studentName = $studentName;
        $this->courses = $courses;
        $this->school = $school;
        $this->packageName = $packageName;
        $this->totalAmount = $totalAmount;
        $this->paymentStatus = $paymentStatus;
    }

    public function build()
    {
        return $this->subject('SAT/ACT Course Registration Confirmation')
                    ->view('emails.sat_act_course_confirmation');
    }
}
