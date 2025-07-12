<?php

namespace App\Service\Telegram;

use App\Service\Log\TgLogger;
use Illuminate\Http\Response;
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

    /**
     * Use this to rawdog your message structure, so its not just simple text
     * @param array $structure
     * @return Response
     */
    public function sendRawMessage(array $structure): \Illuminate\Http\Client\Response
    {
        $url = $this->baseUrl . "/sendMessage";

        $data = [
            'chat_id'      => $this->chatID,
        ];
        $data = array_merge($data, $structure);
        $response = Http::post($url, $data);
        TgLogger::log(['response' => $response,'data' => $data],'raw_message_response');
        return $response;

    }


    public function sendMessage(string $message): array
    {
        $url = $this->baseUrl . "/sendMessage";
        $data = [
            'chat_id'    => $this->chatID,
            'text'       => $message,
            'parse_mode' => 'html'
        ];
        $response = Http::post($url, $data);

        return ['SUCCESS' => $response->ok()];
    }

    public function sendTimoshaGif(): array
    {
        $response = Http::post("{$this->baseUrl}/sendAnimation", [
            'chat_id'   => $this->chatID,
            'animation' => "CgACAgIAAyEFAAShTUC7AAICZmhpa0zjQuw1p-Bxn1qFl5fEFT3cAAIpdQACXGXwSqejyWcrvH5ONgQ",
        ]);

        return $response->json();
    }

    public function sendSticker(string $fileID)
    {
        $response = Http::post("{$this->baseUrl}/sendSticker", [
            'chat_id' => $this->chatID,
            'sticker' => $fileID,
        ]);

        return $response->json();
    }
}
