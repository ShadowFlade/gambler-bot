<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyTelegramWebhook
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
	public function handle($request, Closure $next)
	{
		$verifier = new \App\Service\Telegram\VerifyTelegramRequest(config('services.telegram.webhook_secret'));

		try {
			$verifier->handle($request);
			return $next($request);
		} catch (\Exception $e) {
			return response()->json([
				'status' => 'error',
				'message' => $e->getMessage()
			], 403);
		}
	}
}
