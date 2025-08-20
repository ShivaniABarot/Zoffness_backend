<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentConfirmationMail1 extends Mailable
{
    use Queueable, SerializesModels;

    public $studentName;
    public $billingDetails;
    public $isAdmin;
    
    public function __construct($studentName, $billingDetails, $isAdmin = false)
    {
        $this->studentName = $studentName;
        $this->billingDetails = $billingDetails;
        $this->isAdmin = $isAdmin;
    }

    public function build()
    {
        return $this->view('emails.payment_confirmation')
            ->subject('Payment Confirmation - Zoffness College Prep')
            ->attach(public_path('zoffnesscollegeprep-logo.png'), [
                'as' => 'zoffnesscollegeprep-logo.png',
                'mime' => 'image/png',
            ])
            ->withSwiftMessage(function ($message) {
                $message->embed(public_path('zoffnesscollegeprep-logo.png'), 'zoffnesscollegeprep-logo.png');
            });
    }
}