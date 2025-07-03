<?php

namespace App\Service\Telegram;

use Illuminate\Http\Request;
use App\Service\Gambling\GamblingMessage;
use Illuminate\Support\Facades\Log;

class Router
{
    public function route(Request $request)
    {
        $this->handleIncomingTgMessage($request->all());
    }

    private function handleIncomingTgMessage(array $message): array|null
    {
        $gamblingMessage = new GamblingMessage();
        $gamblingMessage->handleMessage($message);
        return ['SUCCESS' => true];
    }

}