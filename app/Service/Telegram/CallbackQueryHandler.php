<?php

namespace App\Service\Telegram;

use App\Service\Log\TgLogger;
use App\Service\ProjectGlobal;
use App\Service\Telegram\Users\User;
use Illuminate\Http\Client\Response;

class CallbackQueryHandler
{
    public function __construct(private int $chatID, private array $callbackQuery, private int $tgUserID) { }

    public function handle(): \Illuminate\Http\Response
    {
        TgLogger::log(['callbackQuery' => $this->callbackQuery], 'calbback_smth');

        if ($this->callbackQuery['data'] == 'set_spin_price') {
            $resp = $this->replySetSpinPrice();
            return $resp;
        }
        return new \Illuminate\Http\Response('Invalid data', 400);
    }

    public function replySetSpinPrice(): \Illuminate\Http\Response
    {
        if (!User::isChatAdmin($this->chatID, $this->tgUserID)) {
            return new \Illuminate\Http\Response(['SUCCESS' => false], 403);
        }
        $data = [
            'text'         => BotReplies::getSetPriceForSpinText(),
            'reply_markup' => json_encode([
                'force_reply'             => true,
                'input_field_placeholder' => '100', // Suggestion (visible on mobile)
            ])
        ];

        $tgBot = new Bot($this->chatID);
        $resp = $tgBot->sendRawMessage($data);
        $newResp = new \Illuminate\Http\Response(
            [
                'success' => $resp->ok(),
                'error'   => $resp->clientError()
            ],
            $resp->status()
        );
        return $newResp;
    }

}
