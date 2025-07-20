<?php

namespace Database\Factories\Tg;

use App\Service\Gambling\Enum\Emoji;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class MessageFactory
{

    public static function create(string $type){
        $chatId = fake()->uuid();
        $fromId = fake()->biasedNumberBetween(1,100);
        return match($type) {
            'gambling' => new GamblingMessageFactory($chatId, $fromId),
            'botCommand' => new
        };
    }
    public function createMessage(
        int    $userId,
        string $chatId,
        string $message,
    )
    {

        return [
            'message_id' => 1,
            'from'       => $this->createFromNotBot($userId),
            'chat'       => [
                'id'    => $chatId,
                'title' => 'Test Chat',
                'type'  => 'supergroup',
            ],
            'date'       => time(),
            'text'       => $message,
        ];
    }

    public function createFromNotBot(int $userId)
    {

        $firstName = fake()->firstName();;
        $lastName = fake()->lastName();

        return [
            'id'         => $userId,
            'is_bot'     => false,
            'first_name' => $firstName,
            'last_name'  => $lastName,
            'username'   => 'test_user',
        ];
    }
}
