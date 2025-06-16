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

        // Safely extract body content
        $body = '';

        // Check if getBody() is a TextPart or similar
        $bodyPart = $message->getBody();

        if (is_object($bodyPart) && method_exists($bodyPart, 'getContent')) {
            $body = $bodyPart->getContent();
        } elseif (is_string($bodyPart)) {
            $body = $bodyPart;
        }

        // Optional: limit large body size
        $body = \Illuminate\Support\Str::limit($body, 10000);

        EmailLog::create([
            'to' => implode(', ', array_keys($message->getTo() ?? [])),
            'subject' => $message->getSubject(),
            'body' => $body,
            'status' => 'sent',
        ]);
    }


}
