<?php

namespace Andreshg112\HablameSms;

use Illuminate\Notifications\Notification;
use Andreshg112\HablameSms\Exceptions\CouldNotSendNotification;

class HablameChannel
{
    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification|\Andreshg112\HablameSms\Tests\TestNotification $notification
     *
     * @throws \Andreshg112\HablameSms\Exceptions\CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        /** @var \Andreshg112\HablameSms\HablameMessage $message */
        $message = $notification->toHablameNotification($notifiable);

        $message = $message->toArray();

        $response = Facade::sendMessage(
            $message['numero'],
            $message['sms'],
            $message['fecha'],
            $message['referencia']
        );

        if ($response['resultado'] !== 0) {
            throw CouldNotSendNotification::serviceRespondedWithAnError($response);
        }
    }
}
