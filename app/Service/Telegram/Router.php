<?php

namespace App\Service\Telegram;

use Illuminate\Http\Request;
use App\Service\Gambling\GamblingMessage;
use Illuminate\Support\Facades\Log;

class Router
{
    public function route(Request $request)
    {
        $this->filterBySender($request);
        $this->handleIncomingTgMessage($request->all());
    }

    private function handleIncomingTgMessage(array $message): array|null
    {
        if (!$this->isTgMessage($message)) {
            return null;
        }

        $gamblingMessage = new GamblingMessage();
        $gamblingMessage->handleMessage($message);
        return ['SUCCESS' => true];

    }

    private function isTgMessage(array $message): bool
    {
        return issset($message['message']);
    }

    private function isTgSender(Request $request)
    {
        $baseUrl = $request->getBaseUrl();
        Log::build([
            'driver' => 'daily',
            'name' => 'info',
            'path' => storage_path('logs/tg_router8.log'),
        ])->info(['$baseUrl' => $baseUrl]);
    }
}