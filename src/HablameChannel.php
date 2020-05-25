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
        /** @var \Andreshg112\HablameSms\HablameMessage $message */

        /** @scrutinizer ignore-call */
        $message = $notification->toHablameNotification($notifiable);

        $message = $message->toArray();

        $response = Facade::sendMessage(
            $message['numero'],
            $message['sms'],
            $message['fecha'],
            $message['referencia']
        );

        /**
         * La API retorna 1 para cuando hay error a nivel general, por ejemplo,
         * errores en los parámetros.
         */
        if ($response['resultado'] !== 0) {
            throw CouldNotSendNotification::serviceRespondedWithAnError($response);
        }

        /**
         * Sin embargo, cuando el problema es de saldo, retorna resultado = 0,
         * por lo que hay que evaluar el resultado de cada sms.
         */
        $collect = collect(array_values($response['sms']));

        $failed = $collect->every(function (array $item) {
            return (int) $item['resultado'] !== 0;
        });

        // Si todos fallaron, o sea, ninguno se pudo enviar, lanza error.
        throw_if(
            $failed,
            CouldNotSendNotification::serviceRespondedWithAnError(
                $response
            )
        );
    }
}
