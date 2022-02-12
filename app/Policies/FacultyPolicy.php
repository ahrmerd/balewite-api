<?php

namespace App\Policies;

use App\Models\Faculty;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FacultyPolicy
{
    use HandlesAuthorization;
    public function before(User $user, $abilty)
    {
        if ($user->isAdmin()) return true;
    }

    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, Faculty $faculty)
    {
        return true;
    }

    public function create(User $user)
    {
        return $user->isAdmin();
    }

    public function update(User $user, Faculty $faculty)
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Faculty $faculty)
    {
        return $user->isAdmin();
    }

    public function restore(User $user, Faculty $faculty)
    {
        //
    }

    public function forceDelete(User $user, Faculty $faculty)
    {
        //
    }
}
