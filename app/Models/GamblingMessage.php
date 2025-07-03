<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
/**
 * @property int $id
 * @property int $chat_id
 * @property int $user_id
 * @property string $emoji_type
 * @property string $win_value
 * @property string $win_price
 * @property boolean $is_win
 */
class GamblingMessage extends Model
{
    use HasFactory;
    protected $fillable = ['chat_id', 'emoji_type', 'is_win', 'win_value', 'user_id'];
}
