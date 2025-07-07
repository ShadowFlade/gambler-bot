<?php

namespace App\Service\Gambling\Enum;
enum WinningPrice: int
{
    case JACKPOT = 50;
	case CHERRIES = 15;
	case BARS = 25;
	case LEMONS = 10;

	case DEFAULT = 5;

}
