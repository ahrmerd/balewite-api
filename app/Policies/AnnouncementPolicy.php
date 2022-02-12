<?php

namespace App\Policies;

use App\Traits\ModeratorRights;
use App\Models\Announcement;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AnnouncementPolicy
{
    use HandlesAuthorization, ModeratorRights;
    public function viewAny(User $user)
    {
        //
    }

    public function view(User $user, Announcement $announcement)
    {
        //
    }

    public function create(User $user)
    {
        //
    }

    public function update(User $user, Announcement $announcement)
    {
        //
    }

    public function delete(User $user, Announcement $announcement)
    {
        //
    }
    public function restore(User $user, Announcement $announcement)
    {
        //
    }

    public function forceDelete(User $user, Announcement $announcement)
    {
        //
    }
}
