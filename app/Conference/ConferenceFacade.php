<?php

namespace App\Conference;

use Illuminate\Support\Facades\Facade;

/**
 * Class ConferenceFacade
 *
 * Provides a static interface to the Conference
 * service registered in the Laravel service container.
 *
 *
 * @method static mixed someMethod(...$arguments)
 */
class ConferenceFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * This must match the service container binding key.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'conference';
    }
}
