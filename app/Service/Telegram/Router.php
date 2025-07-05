<?php

namespace App\Service\Telegram;

use App\Service\Log\RequestLogger;
use App\Service\Log\TgLogger;
use Illuminate\Http\Request;
use App\Service\Gambling\GamblingMessage;
use App\Service\Telegram\Enum\MessageType;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class Router
{
	public function route(Request $request)
	{
		RequestLogger::log($request);
		$this->handleIncomingTgMessage($request->all()['message']);
	}

	private function handleIncomingTgMessage(array $message): Response|null
	{
		$messageType = $this->determineTypeOfMessage($message);

		$resp = null;
		if ($messageType == MessageType::PRIVATE_MESSAGE) {
			$chatId = $message['chat']['id'];
			$tgBot = new Bot($chatId);
			$tgBot->sendMessage('Наебать меня вздумал? Читай документацию долбоеб. (https://shadowflade.ru/gambler/)');
			return Response(
				['SUCCESS' => false,
				 'ERROR'   => \App\Service\Telegram\Enum\Error::NO_PRIVATE_CHAT]
			);
		} else if ($messageType == MessageType::GAMBLING_MESSAGE) {
			$gamblingMessage = new GamblingMessage();
			$resp = $gamblingMessage->handleMessage($message);
		} else if ($messageType ==
			MessageType::BOT_COMMAND) {
            if(str_contains($message['text'],'@')) {
                $message['text'] = explode("@", $message['text'])[1];
            }
            TgLogger::log(['without bot name' => $message['text']]);
			$command = str_replace('/', '', $message['text']);
			$this->handleBotCommands($command, $message);
		}

		$response = new Response($resp, 200);

		return $response;
	}

	private function determineTypeOfMessage(array $message): MessageType

	{
		if ($this->isBotCommand($message)) {
			return MessageType::BOT_COMMAND;
		} else if ($this->isGamblingMessage($message)) {
			return MessageType::GAMBLING_MESSAGE;
		} else if ($this->isPrivateMessage($message)) {
			return MessageType::PRIVATE_MESSAGE;
		}
		return MessageType::PRIVATE_MESSAGE;
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

	private function isPrivateMessage(array $message): bool
	{
		return $message['chat']['type'] === 'private';
	}

	private function handleBotCommands(string $command, array $message)
	{
		TgLogger::log(['command' => $command, 'message' => $message], 'handle_bot_commands');

		$chatID = $message['chat']['id'];
		if ($command == \App\Service\Telegram\Enum\BotCommands::REGISTER
				->value) {
			$username = $message['from']['username'];
			$name = $message['from']['first_name'] . ' ' . $message['from']['last_name'];
			$tgUserId = $message['from']['id'];
			\App\Service\Telegram\Users\User::register($username, $chatID,
				$name, $tgUserId);
			TgLogger::log(
				[$username, $chatID, $name],
				'handle_bot_commands'
			);

		} elseif ($command ==
			\App\Service\Telegram\Enum\BotCommands::STATISTICS->value) {
			$stats = new \App\Service\Gambling\Statistics($chatID);
			$mostWinsByCounts = $stats->getMostWinsByCount();
			$mostWinsByMoney = $stats->getMostWinsByMoney();

            $tgBot = new Bot($chatID);
			$message = "Топ 3 победителей по проценту выигрышей:\n";
			foreach ($mostWinsByCounts->win_percent as $winPercent) {
				$message .= $winPercent->name . ": " . $winPercent->userWinPercent . "%\n";
			}
			$message .= "\nТоп 3 победителей по количеству выигрышей:\n";

            foreach ($mostWinsByCounts->win_count as $winCount) {
				$message .= $winCount->name . ": " . $winCount->userWinCount . "\n";
			}
			$message .= "\nТоп 3 победителей по деньгам\n";

			foreach ($mostWinsByMoney as $winMoney) {
                $message .= $winMoney->user->name . ": " . $winMoney->win_sum .
                    "\n";
			}

			$tgBot->sendMessage($message);

		}
	}


}
