<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Telegram\Bot\Api;

class TelegramChannel
{
    public function __construct(
        protected Api $telegram
    )
    {
    }

    /**
     * Send the given notification.
     */
    public function send(object $notifiable, Notification $notification): void
    {
        $message = $notification->toText($notifiable);

        $this->telegram->sendMessage([
            'chat_id' => config('telegram.chat_id'),
            'text' => $message
        ]);
    }
}
