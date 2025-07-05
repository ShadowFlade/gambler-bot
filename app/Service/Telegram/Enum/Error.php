<?php
namespace App\Service\Telegram\Enum;

enum Error : string{
	case NO_PRIVATE_CHAT = 'Запрещено отправлять боту личные сообщения';
}