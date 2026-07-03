<?php

namespace App\Policies;

use App\Models\Qualification;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class QualificationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any qualifications.
     *
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
        return true;
    }

    /**
     * Determine whether the user can view the qualification.
     *
     * @return mixed
     */
    public function view(User $user, Qualification $qualification)
    {
        //
        return true;
    }

    /**
     * Determine whether the user can create qualifications.
     *
     * @return mixed
     */
    public function create(User $user)
    {
        //
        return true;
    }

    /**
     * Determine whether the user can update the qualification.
     *
     * @return mixed
     */
    public function update(User $user, Qualification $qualification)
    {
        //
        return true;
    }

    /**
     * Determine whether the user can delete the qualification.
     *
     * @return mixed
     */
    public function delete(User $user, Qualification $qualification)
    {
        //
    }

    /**
     * Determine whether the user can restore the qualification.
     *
     * @return mixed
     */
    public function restore(User $user, Qualification $qualification)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the qualification.
     *
     * @return mixed
     */
    public function forceDelete(User $user, Qualification $qualification)
    {
        //
    }
}
