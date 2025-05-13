<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegistrationSuccessMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function build()
    {
        return $this->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
<<<<<<< HEAD
            ->subject('Welcome to Zoffness Academy')
            ->view('emails.registration_success')
            ->with([
                'username' => $this->user->username,
            ]);
=======
    ->subject('Welcome to Our Application')
    ->view('emails.registration_success')
    ->withUsername($this->user->username);

    
>>>>>>> dba5100 (Register and pratie test booking email notification is done)
    }
}
