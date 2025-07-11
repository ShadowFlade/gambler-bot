<?php
namespace App\Service\Telegram\Enum;
enum BotCommands: string
{
	case REGISTER = 'register';
	case STATISTICS = 'statistics';

	case PERSONAL_STATISTICS = 'personal_statistics';
	case GAMBLER_OF_DAY = 'gambler_of_day';
}
