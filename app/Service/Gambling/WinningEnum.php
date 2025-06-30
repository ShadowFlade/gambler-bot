<?php

namespace App\Service\Gambling\Enum;

enum WinningEnum: int
{
    /**
     * 777 - biggest jackpot
     */
    case JACKPOT = 1;

    case CHERRIES = 22;

    case BANANAS = 43;

    case LEMONS = 64;
}
