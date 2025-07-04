<?php

namespace App\Service\Base\Helper;

class ArrayHelper
{
	public static function every(array $arr, $fn): bool
	{
		$isEvery = true;
		foreach ($arr as $item) {
			if ($fn($item) !== true) {
				$isEvery = false;
			}
		}
		return $isEvery;
	}
}