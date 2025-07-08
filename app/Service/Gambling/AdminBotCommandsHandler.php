<?php


namespace App\Service\Gambling;

use App\Models\Price;
use App\Service\Log\TgLogger;
use App\Service\Telegram\Bot;
use App\Service\Telegram\BotMessages\System;
use App\Service\Telegram\Enum\BotCommands;
use App\Service\Telegram\Enum\Stickers\WomanEmotions;

class AdminBotCommandsHandler
{
    public function __construct(private string $chatID) { }

    public function setSpinPrice(int $spinPrice): void
    {
        $price = new Price();
        $price->price = $spinPrice;
        $tgBot = new Bot($this->chatID);

        $price = Price::query()->where('type', '=', 'spin')->select('price')
            ->get()->price;

        if (is_null($price)) {
            $errMsg = System::getErrorMsg();
            $tgBot->sendMessage($errMsg);
        }

        $isSucc = $price->saveOrFail();

        if (!$isSucc) {
            $errMsg = System::getErrorMsg();
            $tgBot->sendMessage($errMsg);
        } else {
            $message = "Эй, лудики!\nБосс решил, что вы совсем зажрались и решил поднять ставку. А ну-ка скажите А-А-А...";
            $tgBot->sendMessage($message);
            $womanStickers = WomanEmotions::cases();
            $tgBot->sendSticker(array_rand($womanStickers));
        }
    }
}
