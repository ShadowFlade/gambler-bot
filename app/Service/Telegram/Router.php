<?php

namespace App\Service\Telegram;

use App\Models\Price;
use App\Models\User as UserModel;
use App\Service\Gambling\AdminBotCommandsHandler;
use App\Service\Gambling\BotCommandsHandler;
use App\Service\Gambling\Enum\Emoji;
use App\Service\Gambling\Statistics;
use App\Service\Log\RequestLogger;
use App\Service\Log\TgLogger;
use App\Service\Telegram\Enum\AdminBotCommands;
use App\Service\Telegram\Enum\BotCommands;
use App\Service\Telegram\Users\User;
use Illuminate\Http\Request;
use App\Service\Gambling\GamblingMessage;
use App\Service\Telegram\Enum\MessageType;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Router
{
    public function route(Request $request)
    {
        $data = $request->all();

        RequestLogger::log($request);
        $message = $data['message'] ?? $data['edited_message'] ?? null;
        if (isset($message['reply_to_message'])) {
            $repliesHandler = new RepliesHandler($message['chat']['id']);
            $repliesHandler->handle($message);
        } else if (!is_null($message)) {
            $this->handleIncomingTgMessage($message);
            return;
        } else if (isset($data['callback_query'])) {
            $callbackHandler = new CallbackQueryHandler(
                $data['callback_query']['message']['chat']['id'],
                $data['callback_query']
            );
            $callbackHandler->handle();
            return;
        }
    }

    private function handleCallbackQuery(array $callbackQuery)
    {
        $command = $callbackQuery['data'];
        $userID = $callbackQuery['from']['id'];
        $chatID = $callbackQuery['message']['chat']['id'];
        $adminBotCommandHandler = new AdminBotCommandsHandler($chatID);
        $newSpinPrice = $this->getBotCommandArguments();
//        $adminBotCommandHandler->setSpinPrice();
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
            $gamblingMessage = new GamblingMessage();
            $resp = $gamblingMessage->handleMessage($message);
        } else if ($messageType ==
            MessageType::BOT_COMMAND || $messageType ==
            MessageType::ADMIN_BOT_COMMAND) {
            $command = $this->getBotCommand($message);
            $this->handleBotCommands($command, $message);
        }

        $response = new Response($resp, 200);

        return $response;
    }

    private function determineTypeOfMessage(array $message): MessageType
    {
        $isBotCommand = $this->isBotCommand($message);

        if ($isBotCommand) {
            $command = $this->getBotCommand($message);
        }

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

    private function handleBotCommands(string $command, array $message): void
    {
        TgLogger::log(['command' => $command, 'message' => $message], 'handle_bot_commands');

        $chatID = $message['chat']['id'];
        $botCommandsHandler = new BotCommandsHandler($chatID);
        if ($command == BotCommands::REGISTER->value) {
            $botCommandsHandler->register($message);
        } elseif ($command == BotCommands::STATISTICS->value) {
            $botCommandsHandler->statistics($message);
        } else if ($command == BotCommands::ADMIN_COMMANDS->value &&
            User::isChatAdmin($chatID, $message['from']['id'])) {
            $botCommandsHandler->adminCommands();
            return;
        } elseif (
            $command == AdminBotCommands::SET_SPIN_PRICE
            && User::isChatAdmin($chatID, $message['from']['id'])
        ) {
            $adminBotCommandHandler = new AdminBotCommandsHandler($chatID);
            $arguments = $this->getBotCommandArguments($message['text'],
                $command);
            $adminBotCommandHandler->setSpinPrice($arguments[0]);
        }
    }

    private function getBotCommandArguments(string $text, string $command): array
    {
        $text = str_replace("/$command", '', $text);
        $args = explode(' ', $text)[1];
        if (str_contains($args, ' ')) {
            $args = explode(' ', $args);
            return $args;
        } else {
            return [$args];
        }
    }

    public function test(Request $request)
    {
        $price = Price::query()
            ->where('chat_id', '=', -1002522114265)
            ->where('type', '=', 'spin')
            ->where('active_until', '>', now())
            ->select('price')
            ->orderByDesc('id')
            ->limit(1)
            ->first();

    }


}
