<?php

namespace App\Traits;

use App\Models\User;

trait ModeratorRights
{
    public function before(User $user, $abilty)
    {
        if ($user->isModerator()) return true;
    }
}
