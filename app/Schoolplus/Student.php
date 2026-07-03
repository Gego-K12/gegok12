<?php

namespace App\Schoolplus;

use Illuminate\Support\Facades\Facade;

/**
 * Class Student
 *
 * Laravel Facade providing a static interface
 * to the StudentService.
 *
 * This facade allows convenient access to
 * student-related operations such as:
 * - Fetching student information
 * - Retrieving marks and exam data
 * - Comparing academic performance
 *
 * @see StudentService
 */
class Student extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * This method resolves the underlying
     * StudentService from the service container.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'student';
    }
}
