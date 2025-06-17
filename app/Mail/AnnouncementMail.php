<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AnnouncementMail extends Mailable
{
    use Queueable, SerializesModels;

    public $announcement;


    
    public function __construct($announcement)
    {
        $this->announcement = $announcement;
    }

    public function build()
    {
        return $this->view('emails.announcement')
                    ->subject($this->announcement->title);
    }
}