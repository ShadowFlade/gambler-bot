<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @property int $id
 * @property int $role
 * @property string $tg_user_id
 * @property string $name
 * @property string $username
 * @property string $chat_id
 * @property boolean $is_win
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'chat_id',
        //TODO[подумоть]:не проебался ли я тут по архитектуре -
        // 12.07.2025 (вроде не проебался)
        'tg_user_id',
        'role'
    ];
}
