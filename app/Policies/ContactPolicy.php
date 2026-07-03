<?php

namespace App\Policies;

use App\Models\Query;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ContactPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any queries.
     *
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
        return true;
    }

    /**
     * Determine whether the user can view the query.
     *
     * @return mixed
     */
    public function view(User $user, Query $query)
    {
        //
        return true;
    }

    /**
     * Determine whether the user can create queries.
     *
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the query.
     *
     * @return mixed
     */
    public function update(User $user, Query $query)
    {
        //
        return true;
    }

    /**
     * Determine whether the user can delete the query.
     *
     * @return mixed
     */
    public function delete(User $user, Query $query)
    {
        //
    }

    /**
     * Determine whether the user can restore the contact.
     *
     * @return mixed
     */
    public function restore(User $user, Query $contact)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the contact.
     *
     * @return mixed
     */
    public function forceDelete(User $user, Query $contact)
    {
        //
    }
}
