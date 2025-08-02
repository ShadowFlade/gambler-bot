<?php

namespace App\Service\Telegram\Factory;

use App\Service\Telegram\Enum\BotCommand;

class BotCommandFactory extends Message implements
    \App\Service\Telegram\Contract\Message
{

    private BotCommand $command;
    private array $message;
    public function __construct(
        string $chatId,
        int $fromId,
        BotCommand $command,
    )
    {
        $this->fromId = $fromId;
        $message = parent::createMessageSchema($chatId);
        $this->message = $message;
        $this->command = $command;
    }


    public function createMessage()
    {
        $this->insertBotCommand($this->command);
    }


    public function insertBotCommand(BotCommand $command)
    {
        $this->message['entities'] = [
            [
                'offset' => 0,
                'length' => 27,
                'type'   => 'bot_command',
            ]
        ];
        $this->message['text'] = $command->value;
    }

    public function getMessage(): array
    {
        return ['message' => $this->message];
    }


}
