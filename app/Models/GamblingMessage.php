<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GamblingMessage extends Model
{
    protected $fillable = ['chat_id', 'emoji_type', 'is_win', 'win_value', 'user_id'];
}
