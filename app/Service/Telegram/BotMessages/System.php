<?php

namespace App\Service\Telegram\BotMessages;

class System
{
    public static function getErrorMsg()
    {
        $rand = rand(1, 10);
        $message = 'Босс, что-то пошло не так. Не серчай. Попробуй попозже.';

        if ($rand == 10) {
            $message .= "..\n Или может лучше никогда?";
        }
        return $message;
    }
}
