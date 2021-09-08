<?php

namespace Andreshg112\HablameSms;

use Illuminate\Support\Facades\Facade as BaseFacade;

/**
 * @see \Andreshg112\HablameSms\Client
 * @method static array sendMessage(string $phoneNumber, string $sms, ?string $datetime, ?string $reference)
 */
class Facade extends BaseFacade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'hablame-sms';
    }
}
