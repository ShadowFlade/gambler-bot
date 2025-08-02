<?php

namespace App\Service\Telegram\Factory;

use App\Service\Gambling\Enum\Emoji;

class GamblingMessageFactory extends Message implements \App\Service\Telegram\Contract\Message
{
    private array $message;

    public function __construct(string $chatId, int $fromId)
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

    public function getMessage(): array
    {
        return [
            'message' => $this->message,
        ];
    }

}
