<?php

namespace App\Policies;

use App\Models\TeacherProfile;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TeacherProfilePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any teacher profiles.
     *
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
        return true;
    }

    /**
     * Determine whether the user can view the teacher profile.
     *
     * @return mixed
     */
    public function view(User $user, TeacherProfile $teacherProfile)
    {
        //
        return true;
    }

    /**
     * Determine whether the user can create teacher profiles.
     *
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the teacher profile.
     *
     * @return mixed
     */
    public function update(User $user, TeacherProfile $teacherProfile)
    {
        //
        return true;
    }

    /**
     * Determine whether the user can delete the teacher profile.
     *
     * @return mixed
     */
    public function delete(User $user, TeacherProfile $teacherProfile)
    {
        //
    }

    /**
     * Determine whether the user can restore the teacher profile.
     *
     * @return mixed
     */
    public function restore(User $user, TeacherProfile $teacherProfile)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the teacher profile.
     *
     * @return mixed
     */
    public function forceDelete(User $user, TeacherProfile $teacherProfile)
    {
        //
    }
}
