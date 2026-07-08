<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Userprofile;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserprofilePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any userprofiles.
     *
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
        return true;
    }

    /**
     * Determine whether the user can view the userprofile.
     *
     * @param  \App\Userprofile  $userprofile
     * @return mixed
     */
    public function view(User $user, Userprofile $userprofile)
    {
        //
        return true;
    }

    /**
     * Determine whether the user can create userprofiles.
     *
     * @return mixed
     */
    public function create(User $user)
    {
        //
        return true;
    }

    /**
     * Determine whether the user can update the userprofile.
     *
     * @param  \App\Userprofile  $userprofile
     * @return mixed
     */
    public function update(User $user, Userprofile $userprofile)
    {
        //
        return true;
    }

    /**
     * Determine whether the user can delete the userprofile.
     *
     * @param  \App\Userprofile  $userprofile
     * @return mixed
     */
    public function delete(User $user, Userprofile $userprofile)
    {
        //
    }

    /**
     * Determine whether the user can restore the userprofile.
     *
     * @param  \App\Userprofile  $userprofile
     * @return mixed
     */
    public function restore(User $user, Userprofile $userprofile)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the userprofile.
     *
     * @param  \App\Userprofile  $userprofile
     * @return mixed
     */
    public function forceDelete(User $user, Userprofile $userprofile)
    {
        //
    }
}
