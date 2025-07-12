<?php
namespace App\Service\Telegram\Enum;

enum MessageType: string
{
	case BOT_COMMAND = 'bot_command';
	case GAMBLING_MESSAGE = 'gambling_message';
	case PRIVATE_MESSAGE = 'private_message';
    case ADMIN_BOT_COMMAND = 'admin_bot_command';
}
