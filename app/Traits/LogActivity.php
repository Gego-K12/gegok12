<?php

namespace App\Traits;

trait LogActivity
{
    /**
     * Fetches the activities of the logged in users.
     *
     * @param  string  $performed_on
     * @param  string  $caused_by
     * @param  text  $properties
     * @param  text  $message
     */
    public function doActivityLog($performed_on, $caused_by, $properties, $logname, $message)
    {
        activity()
            ->performedOn($performed_on)
            ->causedBy($caused_by)
            ->withProperties($properties)
            ->useLog($logname)
            ->log($message);
    }
}
