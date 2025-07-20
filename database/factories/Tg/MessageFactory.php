<?php

namespace Database\Factories\Tg;

use App\Service\Gambling\Enum\Emoji;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class MessageFactory
{
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

    public function createDice(
        string $emoji = Emoji::CASINO->value,
    ): array
    {
        $value = fake()->biasedNumberBetween(1,64);
        return [
            'emoji' => $emoji,
            'value' => $value,
        ];
    }
}
