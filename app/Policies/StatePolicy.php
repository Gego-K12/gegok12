<?php

namespace App\Policies;

use App\Models\State;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StatePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any states.
     *
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
        return true;
    }

    /**
     * Determine whether the user can view the state.
     *
     * @return mixed
     */
    public function view(User $user, State $state)
    {
        //
        return true;
    }

    /**
     * Determine whether the user can create states.
     *
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the state.
     *
     * @return mixed
     */
    public function update(User $user, State $state)
    {
        //
        return true;
    }

    /**
     * Determine whether the user can delete the state.
     *
     * @return mixed
     */
    public function delete(User $user, State $state)
    {
        //
    }

    /**
     * Determine whether the user can restore the state.
     *
     * @return mixed
     */
    public function restore(User $user, State $state)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the state.
     *
     * @return mixed
     */
    public function forceDelete(User $user, State $state)
    {
        //
    }
}
