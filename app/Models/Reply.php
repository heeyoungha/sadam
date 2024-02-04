<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id','board_id','content','reply_user_name'
    ];
    public function board()
    {
        return $this->belongsTo(Board::class);
    }
}
