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

class GamblingMessage
{

    public function handleMessage(array $message): array| \Error
    {
        Log::build([
            'driver' => 'daily',
            'name'   => 'info',
            'path'   => storage_path('logs/gambling_message.log'),
        ])->info(['message' => $message]);
        $message = $this->filterCasinoEmojis($message);

        if (is_null($message)) {
            return new \Error('Message is null');
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
            TgLogger::log("User $tgUserId not found",'users');
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
        $tgBot = new Bot($chatId);
        $stats = new Statistics($chatId, $tgBot);
        $price = $stats->getSpinPrice(null);
        $newMessage->spin_price = $price;

	    if (isset($message['forward_origin']) || isset($message['forward_from'])) {
		    return false;
	    }

        if ($resultDicValues == Gambling\Enum\WinningValue::JACKPOT->value) {
//            $newMessage->win_price = Enum\WinningPrice::JACKPOT->value;
	        $newMessage->win_price = $price * 80;

            $tgBot = new Bot($chatId);
            $tgBot->sendTimoshaGif();
        } else if ($resultDicValues ==
	        Gambling\Enum\WinningValue::CHERRIES->value) {
	        $newMessage->win_price = $price * 10;
        } else if ($resultDicValues ==
	        Gambling\Enum\WinningValue::BARS->value) {
	        $newMessage->win_price = $price * 20;
        } else if ($resultDicValues ==
	        Gambling\Enum\WinningValue::LEMONS->value) {
	        $newMessage->win_price = $price * 5;
        }
		else {
            $newMessage->win_price = Enum\WinningPrice::DEFAULT->value;
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
