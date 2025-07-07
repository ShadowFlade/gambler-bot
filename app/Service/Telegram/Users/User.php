<?php

namespace App\Service\Telegram\Users;

use App\Models\User as UserModel;
use App\Service\Log\TgLogger;
use App\Service\Telegram\Bot;

class User
{


    public static function register(
        string $username,
        string $chatId,
        string $name,
        string $tgUserId,
    ):
    void
    {

        $isUserExists = !empty(
        UserModel::query()
            ->where('tg_user_id', $tgUserId)
	        ->where('chat_id', $chatId)
            ->first()
        );

        if ($isUserExists) {
	        $tgBot = new Bot($chatId);
	        $tgBot->sendMessage('Ты уже и так лудик, отъебись от меня!');
            return;
        }

        $user = UserModel::create([
            'username'   => $username,
            'chat_id'    => $chatId,
            'name'       => $name,
            'tg_user_id' => $tgUserId
        ]);

        if (!empty($user->id)) {
            $tgBot = new Bot($chatId);
            $tgBot->sendMessage('Теперь ты лудоман!');
        }

    }

    public static function isExists(string $chatId, string $tgUserId): bool
    {
        return UserModel::query()->where('chat_id', $chatId)->where('tg_user_id',
            $tgUserId)->exists();
    }
}
