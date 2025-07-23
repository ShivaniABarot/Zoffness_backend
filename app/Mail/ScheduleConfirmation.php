<?php

namespace App\Mail;

use App\Models\Schedule;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ScheduleConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $schedule;

    public function __construct(Schedule $schedule)
    {
        $this->schedule = $schedule;
    }

    // public function build()
    // {
    //     return $this->subject('Your Schedule is Confirmed')
    //                 ->view('emails.schedule_confirmation');
    // }

    public function build()
    {
        return $this->from('web@notifications.zoffnesscollegeprep.com', 'Ben Zoffness')
                    ->replyTo('info@zoffnesscollegeprep.com')
                    ->subject(subject: 'Consultation Scheduled Confirmation')
                    ->view('emails.schedule_confirmation');
    }
    
}
