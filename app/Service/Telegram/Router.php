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
        if ($messageType == MessageType::BOT_COMMAND) {
            $command = str_replace('/', '', $message['text']);
            $this->handleBotCommands($command, $message);
        } else if ($messageType == MessageType::GAMBLING_MESSAGE) {
            $gamblingMessage = new GamblingMessage();
            $resp = $gamblingMessage->handleMessage($message);
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

    private function handleBotCommands(string $command, array $message)
    {
        TgLogger::log([$command], 'handle_bot_commands');

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
            \App\Service\Telegram\Enum\BotCommands::STATISTICS) {
            $stats = new App\Service\Gambling\Statistics($chatID);
        }
    }


}
