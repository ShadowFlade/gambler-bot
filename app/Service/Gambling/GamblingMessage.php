<?php

namespace App\Service\Gambling;

use App\Models\Price;
use App\Service\Log\TgLogger;
use App\Service\Telegram\Bot;
use App\Service\Telegram\Users\User;
use Illuminate\Support\Facades\DB;
use App\Service\Gambling;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use App\Service\Gambling\Enum\WinningSubtype;
use App\Service\Gambling\Enum\WinningValue;

class GamblingMessage
{

    public function handleMessage(array $message): array|null
    {
        Log::build([
            'driver' => 'daily',
            'name'   => 'info',
            'path'   => storage_path('logs/gambling_message.log'),
        ])->info(['message' => $message]);
        $message = $this->filterCasinoEmojis($message);

        if (is_null($message)) {
            return null;
        }

        $this->storeMessage($message);

        return $message;
    }

    public function filterCasinoEmojis(array $message): array|null
    {
        if (!isset($message['dice'])) {
            return null;
        }
        return $message;
    }

    public function storeMessage(array $message): bool
    {
        $chatId = $message['chat']['id'];
        $tgUserId = $message['from']['id'];
        $isUserExists = User::isExists($chatId, $tgUserId);
        if (!$isUserExists) {
            TgLogger::log("User $tgUserId not found", 'users');
            return false;
        }
        Log::build([
            'driver' => 'daily',
            'name'   => 'info',
            'path'   => storage_path('logs/store_message_before.log'),
        ])->info(['$message' => $message]);
        $newMessage = new \App\Models\GamblingMessage();
        $newMessage->chat_id = $chatId;
        $newMessage->emoji_type = 'casino'; //TODO[placeholder] - determine type of emoji
        $resultDicValues = $message['dice']['value'];
        $newMessage->is_win = $this->isWin($resultDicValues);
        $newMessage->win_value = $resultDicValues;
        $newMessage->user_id = $message['from']['id'];
        $stats = new Statistics($chatId);
        $price = $stats->getSpinPrice(null);
        $newMessage->spin_price = $price;

        if (isset($message['forward_origin']) || isset($message['forward_from'])) {
            return false;
        }

        if ($resultDicValues == WinningValue::JACKPOT->value) {
//            $newMessage->win_price = Enum\WinningPrice::JACKPOT->value;
            //TODO[refactoring]:вынести это в репозиторий???
            $coef = Price::query()
                ->where('type', '=', 'win')
                ->where('sub_type', '=', WinningSubtype::JACKPOT->value)
                ->select('price')
                ->first();
            $newMessage->win_price = $price * $coef->price;


            $tgBot = new Bot($chatId);
            $tgBot->sendTimoshaGif();
        } else if ($resultDicValues == WinningValue::CHERRIES->value) {
            $coef = Price::query()
                ->where('type', '=', 'win')
                ->where('sub_type', '=', WinningSubtype::CHERRIES->value)
                ->select('price')
                ->first();
            $newMessage->win_price = $price * $coef->price;
        } else if ($resultDicValues == WinningValue::BARS->value) {
            $coef = Price::query()
                ->where('type', '=', 'win')
                ->where('sub_type', '=', WinningSubtype::BARS->value)
                ->select('price')
                ->first();
            $newMessage->win_price = $price * $coef->price;
        } else if ($resultDicValues == WinningValue::LEMONS->value) {
            $coef = Price::query()
                ->where('type', '=', 'win')
                ->where('sub_type', '=', WinningSubtype::LEMONS->value)
                ->select('price')
                ->first();
            $newMessage->win_price = $price * $coef->price;
        } else {
            $newMessage->win_price = 1;

        }
        $isSuccess = $newMessage->saveOrFail();
        Log::build([
            'driver' => 'daily',
            'name'   => 'info',
            'path'   => storage_path('logs/store_message.log'),
        ])->info(['$isSuccess' => $isSuccess, 'new id' => $newMessage->id]);
        return $isSuccess;
    }

    private function isWin(int $tgWinValue): bool
    {
        $allDiceWinsEnumCases = Enum\WinningValue::cases();
        $allDiceWins = array_column($allDiceWinsEnumCases, 'value');
        return in_array($tgWinValue, $allDiceWins);
    }
}
