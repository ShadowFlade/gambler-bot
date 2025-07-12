<?php


namespace App\Service\Gambling;

use App\Models\Price;
use App\Service\Log\TgLogger;
use App\Service\ProjectGlobal;
use App\Service\Telegram\Bot;
use App\Service\Telegram\BotMessages\System;
use App\Service\Telegram\Enum\BotCommands;
use App\Service\Telegram\Enum\Stickers\WomanEmotions;

class AdminBotCommandsHandler
{
    public function __construct(private string $chatID) { }

    public function setSpinPrice(int $newSpinPrice): void
    {
        $queryRes = Price::query()
            ->where('type', '=', 'spin')
            ->where('chat_id', '=', $this->chatID)
            ->first();
        $price = $queryRes->price;
        $tgBot = new Bot($this->chatID);
        $errMsg = System::getErrorMsg();

        if ($newSpinPrice > ProjectGlobal::$SPIN_PRICE_THRESHOLD) {
            $message = 'Чет ты приахуел малёк. Отдохни пока.';
            $tgBot->sendMessage($message);
            return;
        }

        if (!is_numeric($price)) {
            $tgBot->sendMessage($errMsg);
            //TODO:logging here
            return;
        }

        if ($newSpinPrice > $price) {
            $message = "Эй, лудики!\nБосс решил, что вы совсем зажрались и решил поднять ставку. А ну-ка скажите А-А-А...";
            $tgBot->sendMessage($message);
            $womanStickers = array_column(WomanEmotions::cases(), 'value');
            $randomSticker = $womanStickers[rand(0, count($womanStickers) - 1)];
            $tgBot->sendSticker($randomSticker);
            $message = "Новая ставка: $newSpinPrice";
            $tgBot->sendMessage($message);
        } else if ($newSpinPrice < $price) {
            $message = "Эй, лудики!\nНачальник решил сжалиться над вами. Теперь можете крутить хоть до посинения.\nНовая ставка: $newSpinPrice";
            $tgBot->sendMessage($message);
        } else if ($newSpinPrice == $price) {
            $message = "Сам-то понял, че сделал, долбоеб?\nИди очки протри.";
            $tgBot->sendMessage($message);
        }


        $queryRes->price = $newSpinPrice;

        $isSucc = $queryRes->saveOrFail();

        if (!$isSucc) {
            $tgBot->sendMessage($errMsg);
        }
    }
}
