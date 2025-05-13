<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ExecutiveCoachingConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $studentName;
    public $school;
    public $packageType;
    public $subtotal;
    public $recipientName;
    public $recipientType;

    public function __construct($studentName, $school, $packageType, $subtotal, $recipientName, $recipientType)
    {
        $this->studentName = $studentName;
        $this->school = $school;
        $this->packageType = $packageType;
        $this->subtotal = $subtotal;
        $this->recipientName = $recipientName;
        $this->recipientType = $recipientType;
    }

    public function build()
    {
        return $this->subject('Executive Coaching Registration Confirmation')
                    ->view('emails.executive_coaching_confirmation');
    }
}
