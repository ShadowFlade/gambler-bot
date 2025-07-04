<?php

namespace App\Service\Telegram;

use Illuminate\Http\Request;
use App\Service\Gambling\GamblingMessage;
use App\Service\Telegram\Enum\MessageType;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class Router
{
	public function route(Request $request)
	{
		Log::build([
			'driver' => 'daily',
			'name'   => 'info',
			'path'   => storage_path('logs/route_messsage.log'),
		])->info(['$request' => $request->all()]);
		$this->handleIncomingTgMessage($request->all()['message']);
	}

	private function handleIncomingTgMessage(array $message): Response|null
	{
		$messageType = $this->determineTypeOfMessage($message);

		$resp = null;
		if ($messageType == MessageType::BOT_COMMAND) {
			$command = str_replace('/', '', $message['text']);
			$resp = $this->handleBotCommands($command, $message);
		} else if ($messageType == MessageType::GAMBLING_MESSAGE) {
			$gamblingMessage = new GamblingMessage();
			$resp = $gamblingMessage->handleMessage($message);
		}

		return $resp;
	}

	private function determineTypeOfMessage(array $message): MessageType

	{
		if ($this->isBotCommand($message)) {
			return MessageType::BOT_COMMAND;
		} else if ($this->isGamblingMessage($message)) {
			return MessageType::GAMBLING_MESSAGE;
		}
	}

	private function isBotCommand(array $message): bool
	{
		$entities = $message['entities'] ?? null;
		if (!is_null($entities) && count($entities) > 0) {
			foreach ($entities as $entity) {
				if ($entity['type'] === 'bot_command') {
					return true;
				}
			}
		}
		return false;
	}

	private function isGamblingMessage(array $message): bool
	{
		return !empty($message['dice']);
	}

	private function handleBotCommands(string $command, array $message): array
	{
		$chatID = $message['chat']['id'];

		if ($command === \App\Servce\Telegram\Enum\BotCommands::REGISTER) {
			$username = $message['from']['username'];
			$name = $message['from']['first_name'] . ' ' . $message['from']['last_name'];
			\App\Service\Telegram\Users\User::register($username, $chatID, $name);
		} elseif ($command ===
			\App\Servce\Telegram\Enum\BotCommands::STATISTICS) {
			$stats = new App\Service\Gambling\Statistics($chatID);
		}
	}


}