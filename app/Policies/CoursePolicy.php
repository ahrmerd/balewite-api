<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Course;
use App\Traits\AdminRights;
use App\Traits\ModeratorRights;
use App\Models\Department;
use Illuminate\Auth\Access\HandlesAuthorization;

class CoursePolicy
{
    use HandlesAuthorization, AdminRights;

    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, Course $course)
    {
        return true;
    }

    public function create(User $user, $department_id)
    {
        // return true;
        return ($user->department_id = $department_id && $user->isModerator());
    }

    public function update(User $user, Course $course)
    {
        $value = false;
        foreach ($course->departments as $department) {
            if ($department->id == $user->department_id) $value = true;
        }
        return ($value && $user->isModerator());
    }

    public function delete(User $user, Course $course)
    {
        $value = false;
        foreach ($course->departments as $department) {
            if ($department->id == $user->department_id) $value = true;
        }
        return ($value && $user->isModerator());
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Course $course)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Course $course)
    {
        //
    }
}
