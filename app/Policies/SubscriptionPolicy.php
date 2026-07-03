<?php

namespace App\Policies;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SubscriptionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any subscriptions.
     *
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
        return true;
    }

    /**
     * Determine whether the user can view the subscription.
     *
     * @return mixed
     */
    public function view(User $user, Subscription $subscription)
    {
        //
        return true;
    }

    /**
     * Determine whether the user can create subscriptions.
     *
     * @return mixed
     */
    public function create(User $user)
    {
        //
        return true;
    }

    /**
     * Determine whether the user can update the subscription.
     *
     * @return mixed
     */
    public function update(User $user, Subscription $subscription)
    {
        //
        return true;
    }

    /**
     * Determine whether the user can delete the subscription.
     *
     * @return mixed
     */
    public function delete(User $user, Subscription $subscription)
    {
        //
    }

    /**
     * Determine whether the user can restore the subscription.
     *
     * @return mixed
     */
    public function restore(User $user, Subscription $subscription)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the subscription.
     *
     * @return mixed
     */
    public function forceDelete(User $user, Subscription $subscription)
    {
        //
    }
}
