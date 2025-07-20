<?php

namespace Database\Factories\Tg;

use App\Service\Telegram\Enum\AdminBotCommand;
use App\Service\Telegram\Enum\BotCommand;

class AdminBotCommandFactory
{
    public function __construct(int $chatId, int $fromId)
    {
        $this->fromId = $fromId;
        $message = parent::createMessageSchema($chatId);
        $this->message = $message;
    }

    public function createMessage($command)
    {
        if (!$command instanceof AdminBotCommand) {
            throw new \Error('Expected AdminBotCommand instance');
        }
        $this->insertBotCommand($command);
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

}
