<?php
namespace App\Service\Telegram\Users;

enum Roles: int {
    case LUDIK = 1;
    case CHAT_ADMIN = 2;
    case SUPER_ADMIN = 3;
    case SYSTEM_CHAT_ADMIN = 4;
}
