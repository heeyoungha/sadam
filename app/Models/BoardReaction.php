<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoardReaction extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'board_id', 'type'];

    public function board()
    {
        return $this->belongsTo(Board::class);
    }
}
