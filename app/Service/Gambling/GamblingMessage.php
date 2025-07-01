<?php

namespace App\Service\Gambling;

use Illuminate\Support\Facades\DB;
use App\Service\Gambling;
use Illuminate\Database\Eloquent\Collection;

class GamblingMessage
{

    public function handleMessage(array $message): array|null
    {
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

    public function storeMessage(array $message): void
    {
        if ($message['win_value'] == Gambling\Enum\WinningEnum::JACKPOT) {
            $message['win_price'] = Enum\WinningPrice::JACKPOT;
        } else {
            $message['win_price'] = Enum\WinningPrice::DEFAULT;
        }
        \App\Models\GamblingMessage::create($message);
    }

    public function getMostWinsByCount(): object
    {
        $baseQuery = \App\Models\GamblingMessage::with('user')
            ->select('user_id', DB::raw('COUNT(*) as win_count'))
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
            'win_count'   => []
        ];

        foreach ($topWinners as $topWinner) {
            $userWinCount = $totalUsersTriesCount[$topWinner->user_id];
            if ($userWinCount != 0) {
                $result->win_percent[$topWinner->user_id] = $topWinner->win_count
                    / $totalUsersTriesCount[$topWinner->user_id];
                $result->win_count[$topWinner->user_id] = $topWinner->win_count;
            }
        }
        return $topWinners;
    }

    public function getMostWinsByMoney(): Collection
    {
        $topWinners = \App\Models\GamblingMessage::with('user')
            ->where('is_win', '=', true)
            ->select('user_id', DB::raw('COUNT(*) as win_count'), DB::raw('SUM(win_price) as win_sum'))
            ->groupBy('user_id')
            ->orderByDesc('win_sum')
            ->limit(3)
            ->get();
        return $topWinners;
    }
}
