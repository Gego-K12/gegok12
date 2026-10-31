<?php

namespace App\Auth;

use Illuminate\Auth\EloquentUserProvider;

/** Authentication needs the profile, not a fully serialized family tree. */
class AcademyUserProvider extends EloquentUserProvider
{
    protected function newModelQuery($model = null)
    {
        return parent::newModelQuery($model)->without(['members', 'children', 'parents']);
    }
}
