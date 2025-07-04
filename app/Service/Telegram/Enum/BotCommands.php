<?php
namespace App\Servce\Telegram\Enum;
enum BotCommands: string
{
	case REGISTER = 'register';
	case STATISTICS = 'statistics';

	case PERSONAL_STATISTICS = 'personal_statistics';
}