<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class VerifyTelegramWebhook
{
	/**
	 * Handle an incoming request.
	 *
	 * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
	 */
	public function handle($request, Closure $next)
	{
		Log::build([
			'driver' => 'daily',
			'name'   => 'info',
			'path'   => storage_path('logs/request_webhook.log'),
		])->info(['$request' => $request,'ip' => $request->ip()]);
		$verifier = new \App\Service\Telegram\VerifyTelegramRequest(
			config('services.telegram.webhook_secret')
		);

		try {
			$verifier->handle($request);
			return $next($request);
		} catch (\Exception $e) {
			return response()->json([
				'status'  => 'error',
				'message' => $e->getMessage()
			], 403);
		}
	}
}
