<?php

namespace App\Service\Telegram;

use App\Models\User as UserModel;
use App\Service\Gambling\BotCommandsHandler;
use App\Service\Gambling\Enum\Emoji;
use App\Service\Log\RequestLogger;
use App\Service\Log\TgLogger;
use App\Service\Telegram\Enum\AdminBotCommands;
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
            TgLogger::log($request, 'no_message_data');
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
        } else if ($messageType == MessageType::GAMBLING_MESSAGE) {
            TgLogger::log(
                [$messageType, $messageType == MessageType::GAMBLING_MESSAGE],
                'am_i_herer'
            );
            $gamblingMessage = new GamblingMessage();
            $resp = $gamblingMessage->handleMessage($message);
        } else if ($messageType == MessageType::ADMIN_BOT_COMMAND) {

        } else if ($messageType ==
            MessageType::BOT_COMMAND) {
            $command = $this->getBotCommand($message);
            $this->handleBotCommands($command, $message);
        }

        $response = new Response($resp, 200);

        return $response;
    }

    private function determineTypeOfMessage(array $message): MessageType
    {
        TgLogger::log($message, 'type_of_mesage');
        $isBotCommand = $this->isBotCommand($message);
        $command = $this->getBotCommand($message);

        if ($isBotCommand && $this->isAdminBotCommand($command)) {
            return MessageType::ADMIN_BOT_COMMAND;
        } else if ($isBotCommand) {
            return MessageType::BOT_COMMAND;
        } else if ($this->isGamblingMessage($message)) {
            return MessageType::GAMBLING_MESSAGE;
        } else if ($this->isPrivateMessage($message)) {
            return MessageType::PRIVATE_MESSAGE;
        }

        return MessageType::PRIVATE_MESSAGE;
    }

    private function getBotCommand(array $message): string
    {
        if (str_contains($message['text'], '@')) {
            $message['text'] = explode("@", $message['text'])[0];
        }
        $command = str_replace('/', '', $message['text']);
        return $command;
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

    private function isAdminBotCommand(string $command): bool
    {
        if (in_array($command, AdminBotCommands::cases())) {
            return true;
        }
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
        $botCommandsHandler = new BotCommandsHandler($chatID);
        if ($command == \App\Service\Telegram\Enum\BotCommands::REGISTER
                ->value) {
            $botCommandsHandler->register($message);
        } elseif ($command ==
            \App\Service\Telegram\Enum\BotCommands::STATISTICS->value) {
            $botCommandsHandler->statistics($message);
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
