<?php

namespace App\Service\Gambling;

use Illuminate\Support\Facades\DB;
use App\Service\Gambling;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class GamblingMessage
{

    public function handleMessage(array $message): array|null
    {
        Log::build([
            'driver' => 'daily',
            'name' => 'info',
            'path' => storage_path('logs/gambling_message.log'),
        ])->info(['message' => $message]);
        $message = $this->filterCasinoEmojis($message);
        Log::build([
            'driver' => 'daily',
            'name' => 'info',
            'path' => storage_path('logs/gambling.log'),
        ])->info(['message1' => $message]);
        if (is_null($message)) {
            return null;
        }
        Log::build([
            'driver' => 'daily',
            'name' => 'info',
            'path' => storage_path('logs/store_message.log'),
        ])->info(['1' => $message]);
        $this->storeMessage($message);
        Log::build([
            'driver' => 'daily',
            'name' => 'info',
            'path' => storage_path('logs/store_message.log'),
        ])->info(['2' => $message]);
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
	    Log::build([
		    'driver' => 'daily',
		    'name' => 'info',
		    'path' => storage_path('logs/store_message_before.log'),
	    ])->info(['$message' => $message]);
        $newMessage = new \App\Models\GamblingMessage();
        $newMessage->chat_id = $message['chat']['id'];
        $newMessage->emoji_type = 'casino'; //TODO[placeholder] - determine type of emoji
        $resultDicValues = $message['dice']['value'];
        $newMessage->is_win = $this->isWin($resultDicValues);
        $newMessage->win_value = $resultDicValues;
        $newMessage->user_id = $message['from']['id'];

        if ($resultDicValues == Gambling\Enum\WinningValue::JACKPOT->value) {
            $newMessage->win_price = Enum\WinningPrice::JACKPOT->value;
        } else {
            $newMessage->win_price = Enum\WinningPrice::DEFAULT->value;
        }
	    $isSuccess = $newMessage->saveOrFail();
        Log::build([
            'driver' => 'daily',
            'name' => 'info',
            'path' => storage_path('logs/store_message.log'),
        ])->info(['$isSuccess' => $isSuccess,'new id' =>$newMessage->id]);
        return $isSuccess;
    }

    private function isWin(int $tgWinValue): bool
    {
        $allDiceWinsEnumCases = Enum\WinningValue::cases();
        $allDiceWins = array_column($allDiceWinsEnumCases, 'value');
        return in_array($tgWinValue, $allDiceWins);
    }
}
