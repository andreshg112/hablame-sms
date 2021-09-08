<?php

namespace Andreshg112\HablameSms;

use Andreshg112\HablameSms\Exceptions\CouldNotSendNotification;
use Illuminate\Notifications\Notification;

class HablameChannel
{
    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     *
     * @throws \Andreshg112\HablameSms\Exceptions\CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        /** @scrutinizer ignore-call */
        $message = $notification->toHablameNotification($notifiable);

        /** @var \Andreshg112\HablameSms\HablameMessage $message */
        $messageArray = $message->toArray();

        try {
            Facade::sendMessage(
                $messageArray['toNumber'],
                $messageArray['sms'],
                $messageArray['sendDate'],
                $messageArray['reference']
            );
        } catch (\Throwable $th) {
            throw new CouldNotSendNotification($th->getMessage(), $th->getCode(), $th);
        }
    }
}
