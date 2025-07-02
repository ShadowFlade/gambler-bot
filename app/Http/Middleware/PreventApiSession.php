<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PreventApiSession
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle(Request $request, Closure $next)
	{
		// Check for API call header from Nginx
		if ($request->header('X-API-CALL') || $request->is('api/*') || $request->is('gambler/api/*')) {
			// Disable session for API requests
			config(['session.driver' => 'array']);

			// Optionally clear existing session
			if ($request->hasSession()) {
				$request->session()->flush();
			}
		}

		return $next($request);
	}
}