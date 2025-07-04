<?php

namespace App\Service\Log;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class TgLogger
{
    public static function log($message, string $filename)
    {
        if (config('logging.logging.IS_LOG_TG') == 'N') {
            return;
        }

        Log::build([
            'driver' => 'daily',
            'name'   => 'info',
            'path'   => storage_path("logs/tg/$filename.log"),
        ])
            ->info(['data' => $message]);
    }
}
