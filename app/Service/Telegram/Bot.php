<?php

namespace App\Service\Telegram;

use Illuminate\Support\Facades\Http;


class Bot
{

	private string $baseUrl;
	private string $chatID;


	public function __construct(string $chatID)
	{
		$this->baseUrl = "https://api.telegram.org/bot" . env('TELEGRAM_BOT_TOKEN');
		$this->chatID = $chatID;
	}


	public function sendMessage(string $message): array
	{
		$url = $this->baseUrl. "/sendMessage";
		$data = [
			'chat_id' => $this->chatID,
			'text' => $message,
			'parse_mode' => 'html'
		];
		$response = Http::post($url, $data);

//		return [];
		return ['SUCCESS' => $response->ok()];
	}

    public function sendTimoshaGif()
    {
        $response = Http::post("{$this->baseUrl}/sendAnimation", [
            'chat_id' => $this->chatID,
            'animation' => "CgACAgIAAyEFAAShTUC7AAICZmhpa0zjQuw1p-Bxn1qFl5fEFT3cAAIpdQACXGXwSqejyWcrvH5ONgQ",
        ]);

        return $response->json();
    }
}
