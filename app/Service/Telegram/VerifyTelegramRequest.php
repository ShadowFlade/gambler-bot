<?php
namespace App\Service\Telegram;
use App\Service\Log\TgLogger;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\IpUtils;

class VerifyTelegramRequest
{
	private $secret_token;
	private $allowed_ips;

	public function __construct(string $secret_token)
	{
		$this->secret_token = $secret_token;
		$this->allowed_ips = ['149.154.160.0/20', '91.108.4.0/22'];
	}

	public function handle(\Illuminate\Http\Request $request)
	{
		TgLogger::log(
			[
				$request->ip(),
				$request->all(),
				!$this->isTelegramIP($request->ip())
			],
			'first_request');
		if (!$this->isTelegramIP($request->ip())) {
			abort(403, 'Invalid origin');
		}

		if ($request->header('X-Telegram-Bot-Api-Secret-Token') !== $this->secret_token) {
			abort(403, 'Invalid token');
		}

		if (!$this->isValidTelegramData($request->all())) {
			abort(400, 'Invalid data');
		}

		return true;
	}

	private function isTelegramIP($ip): bool
	{
		return IpUtils::checkIp($ip, $this->allowed_ips);
	}

	private function isValidTelegramData($data)
	{
		return isset($data['update_id']) && isset($data['message']['chat']['id']);
	}
}
