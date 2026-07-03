<?php

namespace App\Policies;

use App\Models\City;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CityPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any cities.
     *
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
        return true;
    }

    /**
     * Determine whether the user can view the city.
     *
     * @return mixed
     */
    public function view(User $user, City $city)
    {
        //
        return true;
    }

    /**
     * Determine whether the user can create cities.
     *
     * @return mixed
     */
    public function create(User $user)
    {
        //
        return true;
    }

    /**
     * Determine whether the user can update the city.
     *
     * @return mixed
     */
    public function update(User $user, City $city)
    {
        //
        return true;
    }

    /**
     * Determine whether the user can delete the city.
     *
     * @return mixed
     */
    public function delete(User $user, City $city)
    {
        //
    }

    /**
     * Determine whether the user can restore the city.
     *
     * @return mixed
     */
    public function restore(User $user, City $city)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the city.
     *
     * @return mixed
     */
    public function forceDelete(User $user, City $city)
    {
        //
    }
}
