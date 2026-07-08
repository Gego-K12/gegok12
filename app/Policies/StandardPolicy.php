<?php

namespace App\Policies;

use App\Models\Standard;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StandardPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any standards.
     *
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
        return true;
    }

    /**
     * Determine whether the user can view the standard.
     *
     * @return mixed
     */
    public function view(User $user, Standard $standard)
    {
        //
        return true;
    }

    /**
     * Determine whether the user can create standards.
     *
     * @return mixed
     */
    public function create(User $user)
    {
        //
        return true;
    }

    /**
     * Determine whether the user can update the standard.
     *
     * @return mixed
     */
    public function update(User $user, Standard $standard)
    {
        //
        return true;
    }

    /**
     * Determine whether the user can delete the standard.
     *
     * @return mixed
     */
    public function delete(User $user, Standard $standard)
    {
        //
    }

    /**
     * Determine whether the user can restore the standard.
     *
     * @return mixed
     */
    public function restore(User $user, Standard $standard)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the standard.
     *
     * @return mixed
     */
    public function forceDelete(User $user, Standard $standard)
    {
        //
    }
}
