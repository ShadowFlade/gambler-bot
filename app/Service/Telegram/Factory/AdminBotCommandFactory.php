<?php

namespace App\Service\Telegram\Factory;

use App\Service\Telegram\Enum\AdminBotCommand;
use Database\Factories\Tg\Contract;

class AdminBotCommandFactory implements \App\Service\Telegram\Contract\Message
{
    private AdminBotCommand $command;
    public array $message;

    public function __construct(
        string          $chatId,
        int             $fromId,
        AdminBotCommand $command,
    )
    {
        $this->fromId = $fromId;
        $message = new Message();
        $message->createMessageSchema();
        $msg = $message->getMessage();
        $this->message = $msg;
        $this->command = $command;
    }

    public function createMessage()
    {
        $this->insertBotCommand($this->command);
    }

    public function insertBotCommand(AdminBotCommand $command)
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
        return $this->message;
    }

}
