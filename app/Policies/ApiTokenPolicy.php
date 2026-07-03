<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Laravel\Airlock\PersonalAccessToken;

class ApiTokenPolicy
{
    use HandlesAuthorization;

    public function __call($ability, $args)
    {
        return $this->can($ability, ...$args);
    }

    public function can($ability, User $user, PersonalAccessToken $token)
    {
        return $user->tokenCan($ability);
    }
}
