<?php
namespace App\Service\Telegram\Enum;
enum BotCommand: string
{
	case REGISTER = 'register';
	case STATISTICS = 'statistics';
	case PERSONAL_STATISTICS = 'personal_statistics';
    case ADMIN_COMMANDS = 'admin_commands'; //returns the list of admin
    // commands (not neccecarily THE list, but some type of list/list of
    // buttons)
}
