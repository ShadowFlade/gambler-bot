<?php

namespace App\Service;

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

    public function getMostWins()
    {
        \App\Models\GamblingMessage::query()
            ->selectRaw('count(id) as count')
            ->where(['is_win','=',true])
            ->orderBy('id','')
    }
}
