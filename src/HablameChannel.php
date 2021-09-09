<?php

namespace Andreshg112\HablameSms;

use Andreshg112\HablameSms\Exceptions\CouldNotSendNotification;

class HablameChannel
{
    /**
     * Send the given notification.
     * @param \Illuminate\Notifications\Notifiable $notifiable
     * @throws \Andreshg112\HablameSms\Exceptions\CouldNotSendNotification
     */
    public function send($notifiable, HablameNotification $notification)
    {
        $message = $notification->toHablameNotification($notifiable);

        /** @var \Andreshg112\HablameSms\HablameMessage $message */
        $messageArray = $message->toArray();

        try {
            Facade::sendMessage(
                $messageArray['phoneNumbers'],
                $messageArray['sms'],
                $messageArray['sendDate']
            );
        } catch (\Throwable $th) {
            throw new CouldNotSendNotification($th->getMessage(), $th->getCode(), $th);
        }
    }
}
