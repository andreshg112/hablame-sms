<?php

namespace Andreshg112\HablameSms;

use Illuminate\Notifications\Notification;

abstract class HablameNotification extends Notification
{
    /**
     * Get the notification's delivery channels.
     *
     * @param \Illuminate\Notifications\Notifiable $notifiable
     */
    public function via($notifiable): array
    {
        return [HablameChannel::class];
    }

    /**
     * Returns the SMS(s) to send through Háblame SMS.
     *
     * @param \Illuminate\Notifications\Notifiable $notifiable
     */
    abstract public function toHablameNotification($notifiable): HablameMessage;
}
