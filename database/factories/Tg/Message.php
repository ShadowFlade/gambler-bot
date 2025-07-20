<?php

namespace Database\Factories\Tg;
class Message
{
    /**
     * Возвращаемые тут поля по идее всегда будут приходить
     * @param string $chatId
     * @return array
     */

    private array $message;

    public function createMessageSchema(
        string $chatId,
    )
    {
        return [
            'message_id' => 1,
            'chat'       => [
                'id'    => $chatId,
                'title' => 'Test Chat',
                'type'  => 'supergroup',
            ],
            'date'       => time(),
        ];
    }

    public function get()
    {
        return [
            'message' => $this->message
        ];
    }
}
