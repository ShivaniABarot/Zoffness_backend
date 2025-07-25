<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SatActCourseConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $recipientName;
    public $studentName;
    public $school;
    public $packageName;
    public $examDate;
    public $totalAmount;
    public $paymentStatus;
    public $role;
    public $stripeId;
    public $paymentDate;
    public $stripeDetails;
    public $parentDetails;

    public function __construct(
        $studentName,
        $school,
        $packageName,
        $totalAmount,
        $paymentStatus,
        $recipientName,
        $role,
        $examDate,
        $stripeId,
        $paymentDate,
        $stripeDetails,
        $parentDetails
    ) {
        $this->studentName = $studentName;
        $this->school = $school;
        $this->packageName = $packageName;
        $this->examDate = $examDate;
        $this->totalAmount = $totalAmount;
        $this->paymentStatus = $paymentStatus;
        $this->recipientName = $recipientName;
        $this->role = $role;
        $this->stripeId = $stripeId;
        $this->paymentDate = $paymentDate;
        $this->stripeDetails = $stripeDetails;
        $this->parentDetails = $parentDetails;
    }

    public function build()
    {
        return $this->view('emails.sat_act_course_confirmation')
            ->with([
                'recipientName' => $this->recipientName,
                'studentName' => $this->studentName,
                'school' => $this->school,
                'packageName' => $this->packageName,
                'examDate' => $this->examDate,
                'totalAmount' => $this->totalAmount,
                'paymentStatus' => $this->paymentStatus,
                'role' => $this->role,
                'stripeId' => $this->stripeId,
                'paymentDate' => $this->paymentDate,
                'stripeDetails' => $this->stripeDetails,
                'parentDetails' => $this->parentDetails,
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