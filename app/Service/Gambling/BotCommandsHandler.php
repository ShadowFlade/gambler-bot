<?php

namespace App\Service\Gambling;

use App\Service\Log\TgLogger;
use App\Service\Telegram\Bot;
use App\Service\Telegram\Enum\AdminBotCommand;
use App\Service\Telegram\Enum\BotCommand;
use App\Service\Telegram\Users\Roles;

class BotCommandsHandler
{
    public function __construct(private string $chatID) { }

    public function register(array $message): void
    {
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
        \App\Service\Telegram\Users\User::register(
            $username,
            $this->chatID,
            $name,
            $tgUserId,
            Roles::LUDIK->value,
        );
        TgLogger::log(
            [$username, $this->chatID, $name],
            'handle_bot_commands'
        );
    }

    public function statistics(array $message): void
    {
        $stats = new \App\Service\Gambling\Statistics($this->chatID);
        $mostWinsByCounts = $stats->getMostWinsByCount();
        $mostWinsByMoney = $stats->getMostWinsByMoney();

        $mostWinsByMoneyArr = $mostWinsByMoney->keyBy('user_id')
            ->toArray();

        $tgBot = new Bot($this->chatID);
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

    public function adminCommands(): void
    {
        $keyboard = [
            'inline_keyboard' => [
                [
                    [
                        'text'          => 'Введи цену спина ($)',
                        'callback_data' => 'set_spin_price'
                    ]
                ]
            ]
        ];
        $data = [
            'text'         => 'Админские команды:',
            'reply_markup' => json_encode($keyboard),
        ];
        $tgBot = new Bot($this->chatID);
        $resp = $tgBot->sendRawMessage($data);

        TgLogger::log($resp, 'admin_commands');
    }
}
