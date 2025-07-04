<?php
namespace App\Service\Telegram\Enum;

enum MessageType: string
{
	case BOT_COMMAND = 'bot_command';
	case GAMBLING_MESSAGE = 'gambling_message';
}