<?php

namespace App\Service\Telegram\Contract;

interface Message
{
    public function getMessage(): array;

    /**
     * Can be BotCommand
     * @param mixed $arguments
     * @return mixed
     */
    public function createMessage();
}
