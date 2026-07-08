<?php

namespace App\Calendar;

use Illuminate\Support\Facades\Facade;

/**
 * Class CalendarFacade
 *
 * This facade provides a static interface to the
 * Calendar service registered in the Laravel service container.
 *
 *
 * @method static mixed someMethod(...$arguments)
 */
class CalendarFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * This value should match the binding key
     * used in the service container.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'calendar';
    }
}
