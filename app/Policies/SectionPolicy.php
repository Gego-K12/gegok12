<?php

namespace App\Policies;

use App\Models\Section;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SectionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any sections.
     *
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
        return true;
    }

    /**
     * Determine whether the user can view the section.
     *
     * @return mixed
     */
    public function view(User $user, Section $section)
    {
        //
        return true;
    }

    /**
     * Determine whether the user can create sections.
     *
     * @return mixed
     */
    public function create(User $user)
    {
        //
        return true;
    }

    /**
     * Determine whether the user can update the section.
     *
     * @return mixed
     */
    public function update(User $user, Section $section)
    {
        //
        return true;
    }

    /**
     * Determine whether the user can delete the section.
     *
     * @return mixed
     */
    public function delete(User $user, Section $section)
    {
        //
    }

    /**
     * Determine whether the user can restore the section.
     *
     * @return mixed
     */
    public function restore(User $user, Section $section)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the section.
     *
     * @return mixed
     */
    public function forceDelete(User $user, Section $section)
    {
        //
    }
}
