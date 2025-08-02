<?php

namespace App\Service\Telegram\Factory;

use App\Service\Telegram\Enum\AdminBotCommand;
use App\Service\Telegram\Enum\BotCommand;
use App\Service\Telegram\Enum\MessageType;


class MessageFactory //well its not really a FACTORY
{

    public static function create(
        MessageType $type,
        ?BotCommand $command = BotCommand::REGISTER,
    ): \App\Service\Telegram\Contract\Message
    {
        $chatId = fake()->uuid();
        $fromId = fake()->biasedNumberBetween(1, 100);

        return match ($type->value) {
            MessageType::GAMBLING_MESSAGE->value => new GamblingMessageFactory(
                $chatId,
                $fromId
            ),
            MessageType::BOT_COMMAND->value => new BotCommandFactory(
                $chatId,
                $fromId,
                $command
            ),
            MessageType::ADMIN_BOT_COMMAND->value => new AdminBotCommandFactory(
                $chatId,
                $fromId,
                AdminBotCommand::SET_SPIN_PRICE
            )
        };
    }

    public function createMessage(
        int    $userId,
        string $chatId,
        string $message,
    )
    {

        return [
            'message_id' => 1,
            'from'       => $this->createFromNotBot($userId),
            'chat'       => [
                'id'    => $chatId,
                'title' => 'Test Chat',
                'type'  => 'supergroup',
            ],
            'date'       => time(),
            'text'       => $message,
        ];
    }

    public function createFromNotBot(int $userId)
    {
        $firstName = fake()->firstName();;
        $lastName = fake()->lastName();

        return [
            'id'         => $userId,
            'is_bot'     => false,
            'first_name' => $firstName,
            'last_name'  => $lastName,
            'username'   => 'test_user',
        ];
    }
}
