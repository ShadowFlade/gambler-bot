<?php

namespace App\Service\Telegram\Factory;

use Illuminate\Support\Str;

class Message
{

    public function __construct()
    {
        $this->message = $this->createMessageSchema(env('MAIN_CHAT_ID'));
    }

    /**
     * Возвращаемые тут поля по идее всегда будут приходить
     * @param string $chatId
     * @return array
     */

    private array $message;

    public function createMessageSchema(
        ?string $chatId = '',
    )
    {
        if (empty($chatId)) {
            $chatId = Str::random(16);
        }
        return [
            'message_id' => 1,
            'from'       => $this->createFrom(),
            'chat'       => [
                'id'    => $chatId,
                'title' => 'Test Chat',
                'type'  => 'supergroup',
            ],
            'date'       => time(),
        ];
    }

    public function getMessage(): array
    {
        return [
            'message' => $this->message
        ];
    }

    public function createFrom(?int $userId = 0)
    {
        $firstName = fake()->firstName();;
        $lastName = fake()->lastName();

        if (empty($userId)) {
            $userId = random_int(1_000_000_000, 9_999_999_999);
        }

        return [
            'id'         => $userId,
            'is_bot'     => false,
            'first_name' => $firstName,
            'last_name'  => $lastName,
            'username'   => 'test_user',
        ];
    }

    public function createCallbackQuery(
        string $dataText,
        string $chatId,
        string $regularUserId,
    ): array
    {
        $query = [
            'data'    => $dataText,
            'message' => ['chat' => ['id' => $chatId]],
            'from'    => ['id' => $regularUserId]
        ];

        return $query;
    }
}
