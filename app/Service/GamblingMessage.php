<?php

namespace App\Service;

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
        \App\Models\GamblingMessage::create($message);
    }

    public function getMostWinsByCount(): Collection
    {
        $topWinners = \App\Models\GamblingMessage::with('user')
            ->where('is_win', '=', true)
            ->select('user_id', DB::raw('COUNT(*) as win_count'))
            ->groupBy('user_id')
            ->orderByDesc('win_count')
            ->limit(3)
            ->get();
        return $topWinners;
    }
}
