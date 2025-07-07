<?php

namespace App\Service\Gambling\Enum;

enum WinningValue: int
{
    /**
     * 777 - biggest jackpot
     */
    case JACKPOT = 64;

    case CHERRIES = 22;


	case BARS = 1;

	case LEMONS = 43;
}
