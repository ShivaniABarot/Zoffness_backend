<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Mail\Events\MessageSent;
use App\Models\EmailLog;
class LogSentEmail
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(MessageSent $event)
    {
        $message = $event->message;

        EmailLog::create([
            'to'      => implode(', ', array_keys($message->getTo() ?? [])),
            'subject' => $message->getSubject(),
            'body'    => $message->getBody(),
            'status'  => 'sent',
        ]);
    }
}
