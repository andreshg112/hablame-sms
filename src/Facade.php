<?php

namespace Andreshg112\HablameSms;

use Illuminate\Support\Facades\Facade as BaseFacade;

/**
 * @see \Andreshg112\HablameSms\Client
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
