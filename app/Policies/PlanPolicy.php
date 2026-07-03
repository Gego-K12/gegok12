<?php

namespace App\Policies;

use App\Models\Plan;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PlanPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any plans.
     *
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
        return true;
    }

    /**
     * Determine whether the user can view the plan.
     *
     * @return mixed
     */
    public function view(User $user, Plan $plan)
    {
        //
        return true;
    }

    /**
     * Determine whether the user can create plans.
     *
     * @return mixed
     */
    public function create(User $user)
    {
        //
        return true;
    }

    /**
     * Determine whether the user can update the plan.
     *
     * @return mixed
     */
    public function update(User $user, Plan $plan)
    {
        //
        return true;
    }

    /**
     * Determine whether the user can delete the plan.
     *
     * @return mixed
     */
    public function delete(User $user, Plan $plan)
    {
        //
    }

    /**
     * Determine whether the user can restore the plan.
     *
     * @return mixed
     */
    public function restore(User $user, Plan $plan)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the plan.
     *
     * @return mixed
     */
    public function forceDelete(User $user, Plan $plan)
    {
        //
    }
}
