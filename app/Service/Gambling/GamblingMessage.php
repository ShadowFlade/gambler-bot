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
        $message = $message['message'];
        Log::build([
            'driver' => 'daily',
            'name' => 'info',
            'path' => storage_path('logs/not_gambling.log'),
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
        $newMessage = new \App\Models\GamblingMessage();
        $newMessage->chat_id = $message['chat']['id'];
        $newMessage->emoji_type = 'casino'; //TODO[placeholder] - determine type of emoji
        $resultDicValues = $message['dice']['value'];
        $newMessage->is_win = $this->isWin($resultDicValues);
        $newMessage->win_value = $resultDicValues;
        $newMessage->user_id = $message['from']['id'];

        if ($resultDicValues == Gambling\Enum\WinningValue::JACKPOT) {
            $newMessage->win_price = Enum\WinningPrice::JACKPOT;
        } else {
            $newMessage->win_price = Enum\WinningPrice::DEFAULT;
        }
        $isSuccess = $newMessage->saveOrFail();
        Log::build([
            'driver' => 'daily',
            'name' => 'info',
            'path' => storage_path('logs/store_message.log'),
        ])->info(['$isSuccess' => $isSuccess]);
        return $isSuccess;
    }

    public function getMostWinsByCount(): object
    {
        $baseQuery = \App\Models\GamblingMessage::with('user')
            ->select('user_id', DB::raw('COUNT(*) as win_count'), 'user.name')
            ->groupBy('user_id')
            ->orderByDesc('win_count');

        $topWinners = (clone $baseQuery)
            ->where('is_win', true)
            ->limit(3)
            ->get()
            ->keyBy('user_id');

        $totalUsersTriesCount = $baseQuery->get()
            ->keyBy('user_id')
            ->count();
        $result = (object)[
            'win_percent' => [],
            'win_count' => []
        ];

        foreach ($topWinners as $topWinner) {

            $userWinCount = $totalUsersTriesCount[$topWinner->user_id];
            $userPercentStats = (object)[
                'name' => $topWinner->user->name,
                'userWinPercent' => 0,
            ];
            $userCountStats = (object)[
                'userWinCount' => $userWinCount,
                'name' => $topWinner->user->name,
            ];
            if ($userWinCount != 0) {
                $userWinPercent = ($topWinner->win_count
                        / $totalUsersTriesCount[$topWinner->user_id]) * 100;
                $userPercentStats->userWinPercent = $userWinPercent;
                $result->win_percent[$topWinner->user_id] = $userPercentStats;
                $result->win_count[$topWinner->user_id] = $userCountStats;
            }
        }
        return $result;
    }

    public function getMostWinsByMoney(): Collection
    {
        $topWinners = \App\Models\GamblingMessage::with('user')
            ->where('is_win', '=', true)
            ->select(
                'user_id',
                DB::raw('COUNT(*) as win_count'),
                DB::raw('SUM(win_price) as win_sum'),
                'user.name'
            )
            ->groupBy('user_id')
            ->orderByDesc('win_sum')
            ->limit(3)
            ->get();
        return $topWinners;
    }

    private function isWin(int $tgWinValue): bool
    {
        $allDiceWinsEnumCases = Enum\WinningValue::cases();
        $allDiceWins = array_column($allDiceWinsEnumCases, 'value');
        return in_array($tgWinValue, $allDiceWins);
    }
}
