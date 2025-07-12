<?php

namespace App\Service\Telegram;

use App\Service\ProjectGlobal;

class BotReplies
{
    public static function getSetPriceForSpinText()
    {
        $maxPrice = ProjectGlobal::$SPIN_PRICE_THRESHOLD;
        $text = "Введите цену (от 1 до $maxPrice):";
        return $text;
    }
}
