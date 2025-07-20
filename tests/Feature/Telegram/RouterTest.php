<?php

namespace Tests\Feature\Telegram;

use App\Models\User;
use App\Service\Gambling\Enum\Emoji;
use App\Service\Telegram\Enum\BotCommand;
use App\Service\Telegram\Enum\MessageType;
use App\Service\Telegram\Router;
use App\Service\Telegram\Users\Roles;
use Database\Factories\Tg\MessageFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class RouterTest extends TestCase
{
    use RefreshDatabase;

    protected Router $router;
    protected string $chatId = '';
    protected int $adminUserId = 0;
    protected int $regularUserId = 0;

    protected function setUp(): void
    {
        parent::setUp();
        $this->chatId = env('TEST_CHAT_ID');
        $this->adminUserId = env('TEST_ADMIN_USER_ID');
        $this->regularUserId = env('TEST_REGULAR_USER_ID');
        $this->router = new Router();

        // Create admin user
        User::factory()->create([
            'tg_user_id' => $this->adminUserId,
            'role'       => Roles::CHAT_ADMIN,
            'chat_id'    => $this->chatId
        ]);

        // Create regular user
        User::factory()->create([
            'tg_user_id' => $this->regularUserId,
            'role'       => Roles::LUDIK,
            'chat_id'    => $this->chatId
        ]);
    }

    /** @test */
    public function isHandlesGamblingMessages()
    {
        $tgFactory = MessageFactory::create(MessageType::GAMBLING_MESSAGE);
        $tgFactory->createMessage();
        $message = $tgFactory->getMessage();
        $request = $this->createRequest($message);
        $response = $this->router->route($request);
        $this->assertEquals(200, $response->getStatusCode());
    }

    /** @test */
    public function isHandlesBotCommands()
    {
        $commands = array_map(function ($item) {
            return $item;
        }, BotCommand::cases());

        foreach ($commands as $command) {
            $tgFactory = MessageFactory::create(MessageType::BOT_COMMAND);
            $tgFactory->createMessage($command);
            $message = $tgFactory->getMessage();
            $request = $this->createRequest($message);
            $response = $this->router->route($request);
            $this->assertEquals(200, $response->getStatusCode());
        }
    }

    /** @test */
    public function isHandlesAdminCommands()
    {
        $tgFactory = MessageFactory::create(MessageType::ADMIN_BOT_COMMAND);
        $tgFactory->createMessage();
        $message = $tgFactory->getMessage();
        $request = $this->createRequest($message);

        $response = $this->router->route($request);
        $this->assertEquals(200, $response->getStatusCode());
    }


    /** @test */
    public function isRejectsAdminCommandsFromNonAdmins()
    {
        $request = $this->createRequest([
            'message' => [
                'chat'     => ['id' => $this->chatId, 'type' => 'group'],
                'from'     => ['id' => $this->regularUserId,'first_name' => ],
                'text'     => '/set_spin_price 10',
                'entities' => [['type' => 'bot_command']]
            ]
        ]);

        $response = $this->router->route($request);
        $this->assertEquals(200, $response->getStatusCode());
    }


    /** @test */
    public function isHandlesCallbackQueries()
    {
        $request = $this->createRequest([
            'callback_query' => [
                'data'    => 'set_spin_price',
                'message' => ['chat' => ['id' => $this->chatId]],
                'from'    => ['id' => $this->regularUserId]
            ]
        ]);

        $response = $this->router->route($request);
        $this->assertEquals(200, $response->getStatusCode());
    }


    /** @test */
    public function isHandlesPrivateMessages()
    {
        $request = $this->createRequest([
            'message' => [
                'chat' => ['id' => $this->regularUserId, 'type' => 'private'],
                'from' => ['id' => $this->regularUserId],
                'text' => 'hey dalbich'
            ]
        ]);

        $response = $this->router->route($request);
        $this->assertEquals(200, $response->getStatusCode());
    }


    /** @test */
    public function isHandlesMessageReplies()
    {
        $request = $this->createRequest([
            'message' => [
                'chat'             => ['id' => $this->chatId],
                'from'             => ['id' => $this->regularUserId],
                'reply_to_message' => [
                    'message_id' => 123,
                    'from'       => ['id' => $this->adminUserId]
                ],
                'text'             => '50'
            ]
        ]);

        $response = $this->router->route($request);
        $this->assertEquals(200, $response->getStatusCode());
    }

    protected function createRequest(array $data): Request
    {
        return Request::create('/', 'POST', $data);
    }
}
