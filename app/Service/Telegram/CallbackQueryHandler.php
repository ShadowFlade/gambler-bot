<?php

namespace App\Service\Telegram;

use App\Service\Log\TgLogger;
use App\Service\ProjectGlobal;
use App\Service\Telegram\Users\User;
use Illuminate\Http\Client\Response;

class CallbackQueryHandler
{
	public function __construct(private int $chatID, private array $callbackQuery, private int $tgUserID) { }

	public function handle()
	{
		TgLogger::log(['callbackQuery' => $this->callbackQuery], 'calbback_smth');

		if ($this->callbackQuery['data'] == 'set_spin_price') {
			$this->replySetSpinPrice();
			return;
		}
	}

	public function replySetSpinPrice(): \Illuminate\Http\Client\Response
	{
		if (!User::isChatAdmin($this->chatID, $this->tgUserID)) {
			return new Response(['SUCCESS' => false]);
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
		return $resp;
	}

}
