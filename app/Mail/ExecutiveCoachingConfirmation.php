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

    public function __construct($studentName, $school, $packageType, $subtotal)
    {
        $this->studentName = $studentName;
        $this->school = $school;
        $this->packageType = $packageType;
        $this->subtotal = $subtotal;
    }

    public function build()
    {
        return $this->subject('Executive Coaching Registration Confirmation')
                    ->view('emails.executive_coaching_confirmation');
    }
}
