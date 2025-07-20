<?php

namespace Database\Factories\Tg\Contract;

use App\Service\Telegram\Enum\BotCommand;

interface Message
{
    public function getMessage(): array;

    /**
     * Can be BotCommand
     * @param mixed $arguments
     * @return mixed
     */
    public function createMessage(mixed $arguments);
}
