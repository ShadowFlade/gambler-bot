<?php

namespace App\Service\Gambling\Enum;

enum WinningValue: int
{
    /**
     * 777 - biggest jackpot
     */
    case JACKPOT = 64;//100%

    case CHERRIES = 22;//100%

	case BARS = 1;//100%

	case LEMONS = 43;//это не выигрыш вроде как
}
