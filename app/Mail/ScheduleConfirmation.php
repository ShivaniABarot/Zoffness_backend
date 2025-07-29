<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ScheduleConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public string $name;
    public string $email;
    public string $phone;
    public string $date;
    public string $timeSlot;
    public string $primaryInterest;
    public float $fees;
    public string $recipientRole;

    public function __construct(
        string $name,
        string $email,
        string $phone,
        string $date,
        string $timeSlot,
        string $primaryInterest,
        float $fees,
        string $recipientRole = 'admin' 
    ) {
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
        $this->date = $date;
        $this->timeSlot = $timeSlot;
        $this->primaryInterest = $primaryInterest;
        $this->fees = $fees;
        $this->recipientRole = $recipientRole;
    }

    public function build()
    {
        return $this->from('web@notifications.zoffnesscollegeprep.com', 'Ben Zoffness')
                    ->replyTo('info@zoffnesscollegeprep.com')
                    ->subject($this->recipientRole === 'admin' ? 'New Consultation Scheduled' : 'Your Consultation Confirmation')
                    ->view('emails.schedule_confirmation');
    }
}
