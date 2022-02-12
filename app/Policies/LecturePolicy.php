<?php

namespace App\Policies;

use App\Traits\AdminRights;
use App\Models\Course;
use App\Models\Lecture;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LecturePolicy
{
    use HandlesAuthorization, AdminRights;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Lecture  $lecture
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Lecture $lecture)
    {
        return true;
    }

    public function create(User $user, $course_id)
    {
        $permission = false;
        $course = Course::query()->findOrFail($course_id);
        foreach ($course->departments as $department) {
            if ($department->id == $user->department_id) $permission = true;
        }
        return ($permission && $user->isModerator());
    }


    public function update(User $user, Lecture $lecture)
    {
        $permission = false;
        foreach ($lecture->course->departments as $department) {
            if ($department->id == $user->department_id) $permission = true;
        }
        return ($permission && $user->isModerator());
    }


    public function delete(User $user, Lecture $lecture)
    {
        $permission = false;
        foreach ($lecture->course->departments as $department) {
            if ($department->id == $user->department_id) $permission = true;
        }
        return ($permission && $user->isModerator());
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Lecture  $lecture
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Lecture $lecture)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Lecture  $lecture
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Lecture $lecture)
    {
        //
    }
}
