<?php

namespace Andreshg112\HablameSms\Exceptions;

use function GuzzleHttp\json_encode;

class CouldNotSendNotification extends \Exception
{
    public static function serviceRespondedWithAnError(array $response): self
    {
        $encodedResponse = json_encode($response);

        return new static("Message could not be sent: {$encodedResponse}");
    }
}
