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

    public function __construct($studentName, $testTypes, $date, $subtotal)
    {
        $this->studentName = $studentName;
        $this->testTypes = $testTypes;
        $this->date = $date;
        $this->subtotal = $subtotal;
    }

    public function build()
    {
        return $this->subject('Practice Test Booking Confirmation')
                    ->view('emails.practice_test_booked');
    }
}
