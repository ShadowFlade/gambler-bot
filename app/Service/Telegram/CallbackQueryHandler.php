<?php

namespace App\Service\Telegram;

use App\Service\Log\TgLogger;
use App\Service\ProjectGlobal;

class CallbackQueryHandler
{
    public function __construct(private int $chatID, private array $callbackQuery) { }

    public function handle()
    {
        TgLogger::log(['callbackQuery' => $this->callbackQuery],'calbback_smth');

        if ($this->callbackQuery['data'] == 'set_spin_price') {
            $this->replySetSpinPrice();
            return;
        }
    }

    public function replySetSpinPrice(): \Illuminate\Http\Client\Response
    {
        $data = [
            'text'         => BotReplies::getSetPriceForSpinText(),
            'reply_markup' => json_encode([
                'force_reply'             => true,
                'input_field_placeholder' => '100', // Suggestion (visible on mobile)
            ])
        ];

        $tgBot = new Bot($this->chatID);
        $resp = $tgBot->sendRawMessage($data);
        return $resp;
    }

}
