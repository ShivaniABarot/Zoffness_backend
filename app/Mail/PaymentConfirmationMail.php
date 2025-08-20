<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PaymentConfirmationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $payment; 
    public $isAdmin;

    public function __construct($payment, $isAdmin = false)
    {
        $this->payment = $payment; 
        $this->isAdmin = $isAdmin;
    }

    public function build()
    {
        return $this->subject('Payment Confirmation - Zoffness College Prep')
                    ->view('emails.payment_confirmation')
                    ->with([
                        'payment' => $this->payment,
                        'isAdmin' => $this->isAdmin, // ✅ Pass to Blade
                    ])
                    ->attach(public_path('zoffnesscollegeprep-logo.png'), [
                        'as' => 'zoffnesscollegeprep-logo.png',
                        'mime' => 'image/png',
                    ])
                    ->withSwiftMessage(function ($message) {
                        $message->embed(public_path('zoffnesscollegeprep-logo.png'), 'zoffnesscollegeprep-logo.png');
                    });
    }
}
