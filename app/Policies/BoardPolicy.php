<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Board;

class BoardPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function update(User $user, Board $board)
    {
        return $user->role_id == 1 || $user->id == $board->user_id;
    }

    public function delete(User $user, Board $board)
    {
        return $user->role_id == 1 || $user->id == $board->user_id;
    }
}
