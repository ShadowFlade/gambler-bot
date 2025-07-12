<?php

namespace App\Service\Base\Parser;

class Number
{
    public static function parseNumber(string $text): int|null
    {
        preg_match_all('/\d+\.?\d*/', $text, $matches);
        if (empty($matches) || empty($matches[0]) || empty($matches[0][0])) {
            return null;
        }

        $match = $matches[0][0];

        if (!empty($match)) {
            return $match;
        } else {
            return null;
        }
    }
}
