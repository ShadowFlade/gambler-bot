<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property int $chat_id
 * @property int $spin_price
 * @property string $emoji_type
 * @property string $win_value
 * @property string $win_price
 * @property boolean $is_win
 */
class GamblingMessage extends Model
{
    use HasFactory;

    protected $table = 'gambling_message';
    protected $fillable = [
        'chat_id',
        'emoji_type',
        'is_win',
        'win_value',
        'user_id',
        'win_price',
        'spin_price'
    ];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
