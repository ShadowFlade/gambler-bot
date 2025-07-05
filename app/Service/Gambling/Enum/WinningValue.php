<?php

namespace App\Service\Gambling\Enum;

enum WinningValue: int
{
    /**
     * 777 - biggest jackpot
     */
    case JACKPOT = 64;//100%

    case CHERRIES = 22;//100%

    case BANANAS = 17;

	case BARS = 33;//100%

	case LEMONS = 5;
}
