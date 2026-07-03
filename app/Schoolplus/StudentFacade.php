<?php

namespace App\Schoolplus;

use Illuminate\Support\Facades\Facade;

/**
 * Class StudentFacade
 *
 * Laravel Facade that provides a static interface
 * to the Student service within the Schoolplus module.
 *
 * This facade resolves the `student` service
 * from the Laravel service container and allows
 * easy access to student-related operations.
 *
 * @see StudentService
 */
class StudentFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * Returns the service container binding key
     * used to resolve the Student service.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'student';
    }
}
