<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SatActCourseConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $studentName;
    public $school;
    public $packageName;
    public $totalAmount;
    public $paymentStatus;
    public $recipientName;
    public $recipientType;

    public function __construct($studentName, $school, $packageName, $totalAmount, $paymentStatus, $recipientName, $recipientType)
    {
        $this->studentName = $studentName;
        $this->school = $school;
        $this->packageName = $packageName;
        $this->totalAmount = $totalAmount;
        $this->paymentStatus = $paymentStatus;
        $this->recipientName = $recipientName;
        $this->recipientType = $recipientType;
    }


    public function build()
{
    return $this->from('web@notifications.zoffnesscollegeprep.com', 'Ben Zoffness')
                ->replyTo('info@zoffnesscollegeprep.com')
                ->subject('SAT/ACT Course Registration Confirmation')
                ->view('emails.sat_act_course_confirmation');
}


    // public function build()
    // {
    //     return $this->subject('SAT/ACT Course Registration Confirmation')
    //                 ->view('emails.sat_act_course_confirmation');
    // }
}
