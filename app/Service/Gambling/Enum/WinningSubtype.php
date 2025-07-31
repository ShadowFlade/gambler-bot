<?php

namespace App\Service\Gambling\Enum;

enum WinningSubtype: string
{
    /**
     * 777 - biggest jackpot
     */
    case JACKPOT = 'jackpot';

    case CHERRIES = 'cherries';

	case BARS = 'bars';

	case LEMONS = 'lemons';
}
