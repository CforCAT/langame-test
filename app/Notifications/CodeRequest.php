<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class CodeRequest extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        protected string $code,
    )
    {
        $this->onConnection('rabbitmq');
        $this->onQueue('jobs');
    }

    /**
     * @param object $notifiable
     * @return string
     */
    public function via(object $notifiable): string
    {
        return TelegramChannel::class;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toText(object $notifiable): string
    {
        return "$notifiable->name ($notifiable->phone), your code is $this->code";
    }
}
