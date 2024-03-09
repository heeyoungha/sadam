<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Bookmark;

class BookmarkPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function view(User $user)
    {
        return $user->role_id == 1;
    }
}
