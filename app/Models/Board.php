<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    use HasFactory;

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }
    public function boardLikeReactions()
    {
        return $this->hasMany(boardReaction::class)
                ->where('type', 'like');
    }
    public function boardBookmarkReactions()
    {
        return $this->hasMany(boardReaction::class)
                ->where('type', 'bookmark');
    }
}
