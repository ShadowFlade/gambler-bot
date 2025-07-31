<?php

namespace App\Service\Telegram;

use App\Service\Base\Parser\Number;
use App\Service\Gambling\AdminBotCommandsHandler;
use App\Service\Log\TgLogger;
use App\Service\Telegram\Users\User;

class RepliesHandler
{
	public function __construct(private int $chatID, private int $tgUserID) { }

	public function handle(array $message)
	{
		if (
			isset($message['reply_to_message']['text'])
			&& $message['reply_to_message']['text'] ==
			BotReplies::getSetPriceForSpinText()
			&& !empty($message['text'])
		) {
			TgLogger::log(
				['$message' => $message],
				'3'
			);
			$this->setSpinPrice($message['text']);
		}
	}

	public function setSpinPrice(string $priceText)
	{
		if (!User::isChatAdmin($this->chatID, $this->tgUserID)) {
			return;
		}

		$newPrice = Number::parseNumber($priceText);
		$tgBot = new Bot($this->chatID);
		TgLogger::log(
			['$priceText' => $priceText, '$newPrice' => $newPrice],
			'2'
		);
		if (is_null($newPrice)) {
			$tgBot->sendMessage('Гадость написал какую-то. Попробуй еще раз');
		} else {
			$adminHandler = new AdminBotCommandsHandler($this->chatID);
			$adminHandler->setSpinPrice($newPrice);
		}
	}
}
