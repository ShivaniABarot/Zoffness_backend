<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CollegeEssayConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $studentName;
    public $sessions;
    public $packages;
    public $recipientName;
    public $recipientType;

    public function __construct($studentName, $sessions, $packages, $recipientName, $recipientType)
    {
        $this->studentName = $studentName;
        $this->sessions = $sessions;
        $this->packages = $packages;
        $this->recipientName = $recipientName;
        $this->recipientType = $recipientType;
    }

    public function build()
    {
        return $this->subject('College Essay Service Confirmation')
                    ->view('emails.college_essay_confirmation');
    }
}

