<?php

namespace Database\Factories\Tg;

use App\Service\Gambling\Enum\Emoji;

class GamblingMessageFactory extends Message
{
    private array $message;

    public function __construct(int $chatId, int $fromId)
    {
        $this->fromId = $fromId;
        $message = parent::createMessageSchema($chatId);
        $this->message = $message;
    }

    public function createMessage()
    {
        $this->message['from'] = $this->createFrom($this->fromId);
//        $this->message['entities'] = $this->createFrom($this->fromId);
        $this->message['dice'] = $this->createDice();

    }

    public function createFrom(int $userId)
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

    public function get()
    {
        return $this->message;
    }

}
