<?php

namespace App\Service\Telegram;

use App\Models\User as UserModel;
use App\Service\Gambling\Enum\Emoji;
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
		$data = $request->all();

		RequestLogger::log($request);
		$message = $data['message'] ?? $data['edited_message'] ?? null;
		if (is_null($message)) {
			TgLogger::log($request,'no_message_data');
			return;
		}
		$this->handleIncomingTgMessage($message);
	}


    private function handleIncomingTgMessage(array $message): Response|null
    {
        $messageType = $this->determineTypeOfMessage($message);
        TgLogger::log(
            [$messageType, $messageType == MessageType::GAMBLING_MESSAGE],
            'message_type'
        );

        $resp = null;

        if ($messageType == MessageType::PRIVATE_MESSAGE) {
            $chatId = $message['chat']['id'];
            $tgBot = new Bot($chatId);
//			$tgBot->sendMessage('Наебать меня вздумал? Читай документацию долбоеб. (https://shadowflade.ru/gambler/)');
            return Response(
                [
                    'SUCCESS' => false,
                    'ERROR'   => \App\Service\Telegram\Enum\Error::NO_PRIVATE_CHAT
                ]
            );
        }

        if ($messageType == MessageType::COMMAND) {
            return $this->handleCommand($message);
        }

        if ($messageType == MessageType::GAMBLING_MESSAGE) {
            TgLogger::log(
                [$messageType, $messageType == MessageType::GAMBLING_MESSAGE],
                'am_i_herer'
            );
            $gamblingMessage = new GamblingMessage();
            $resp = $gamblingMessage->handleMessage($message);
        }

        $response = new Response($resp, 200);

        return $response;
    }

    private function determineTypeOfMessage(array $message): MessageType
    {
        TgLogger::log($message, 'type_of_mesage');
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
        return !empty($message['dice']) && $message['dice']['emoji'] ===
            Emoji::CASINO->value;
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
            $username = $message['from']['username'] ?? null;
            $lastName = $message['from']['last_name'] ?? null;

            $name = $message['from']['first_name'];
            if (!is_null($lastName)) {
                $name .= ' ' . $message['from']['last_name'];
            }
            if (is_null($username)) {
                $username = $name;
            }
            $tgUserId = $message['from']['id'];
            \App\Service\Telegram\Users\User::register($username, $chatID,
                $name, $tgUserId);
            TgLogger::log(
                [$username, $chatID, $name],
                'handle_bot_commands'
            );

        } elseif ($command == \App\Service\Telegram\Enum\BotCommands::GAMBLER_OF_DAY->value) {
            $stats = new \App\Service\Gambling\Statistics($chatID);
            $gamblerOfDay = $stats->getGamblerOfDay();
            
            $tgBot = new Bot($chatID);
            if (is_null($gamblerOfDay)) {
                $tgBot->sendMessage('Сегодня еще никто не лудоманил 😢');
                return;
            }

            $name = $gamblerOfDay->user->name;
            $count = $gamblerOfDay->gamble_count;
            $tgBot->sendMessage("🎰 Лудик дня: $name 🎰\nКоличество ставок: $count");
        } elseif ($command == \App\Service\Telegram\Enum\BotCommands::STATISTICS->value) {
            $stats = new \App\Service\Gambling\Statistics($chatID);
            $mostWinsByCounts = $stats->getMostWinsByCount();
            $mostWinsByMoney = $stats->getMostWinsByMoney();
            $mostWinsByMoneyArr = $mostWinsByMoney->keyBy('user_id')
                ->toArray();

            $tgBot = new Bot($chatID);
            $message = "Статистика:\n";
            TgLogger::log([$mostWinsByCounts], "win_by_count_debug");
            if (is_null($mostWinsByCounts)) {
                return;
            }
            foreach ($mostWinsByCounts->win_percent as $userID
            => $winPercentItem) {
                $balance = -$winPercentItem->spentOnSpins +
                    $mostWinsByMoneyArr[$userID]['win_sum'];
                $message .= $winPercentItem->name . ": " .
                    $balance . '$ ( ' .
                    $mostWinsByCounts->win_count[$userID]->userWinCount . '/'
                    . $mostWinsByCounts->win_percent[$userID]->totalCount . ' ' .
                    round($winPercentItem->userWinPercent, 4) . '%)' .
                "\n";

            }

            if (is_null($mostWinsByCounts)) {
                return;
            }

            $tgBot->sendMessage($message);

        }
    }

    public function test(Request $request)
    {
        $chatId = $request->all()['message']['chat']['id'];
        $tgBot = new Bot($chatId);
        $resp = $tgBot->sendTimoshaGif();
        dd($resp);
    }


}
