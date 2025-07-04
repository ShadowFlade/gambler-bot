<?php

namespace App\Service\Log;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class RequestLogger
{
    public static function log(Request $request)
    {
        if (config('logging.logging.IS_LOG_REQUESTS') == 'N') {
            return;
        }

        Log::build([
            'driver' => 'daily',
            'name'   => 'info',
            'path'   => storage_path('logs/request_api/message.log'),
        ])
            ->info(['payload' => $request->all()]);
    }
}
