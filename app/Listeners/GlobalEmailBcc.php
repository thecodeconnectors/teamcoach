<?php

namespace App\Listeners;

use Illuminate\Mail\Events\MessageSending;

class GlobalEmailBcc
{
    protected array $disabledNotifications = [];

    public function handle(MessageSending $event): void
    {
        if ($this->shouldSendBcc($event)) {
            foreach ((array)config('mail.global_bcc') as $address) {
                $event->message->addBcc($address);
            }
        }
    }

    private function shouldSendBcc(MessageSending $event): bool
    {
        return !in_array($event->data['__laravel_notification'] ?? '', $this->disabledNotifications, false)
            && !$event->message->getHeaders()->has('skiplog');
    }

}
