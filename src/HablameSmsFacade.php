<?php

namespace Andreshg112\HablameSms;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Andreshg112\HablameSms\Client
 */
class HablameSmsFacade extends Facade
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
