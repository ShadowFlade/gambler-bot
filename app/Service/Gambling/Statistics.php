<?php

namespace App\Service\Gambling;

use App\Models\User;
use App\Service\Log\TgLogger;
use App\Service\Telegram\Bot;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class Statistics
{
    private int $spinPrice = 1;
    public function __construct(private string $chatID) { }

    public function getMostWinsByCount(): object|null
    {
        $baseQuery = \App\Models\GamblingMessage::with(['user' => function ($query) {
            $query->select('tg_user_id', 'name');
        }])->select(
            [
                'user_id',
                DB::raw('COUNT(*) as total_count')
            ]
        )
            ->where('chat_id', '=', $this->chatID)
            ->groupBy('user_id');

        $topWinners = (clone $baseQuery)
            ->where('is_win', true)
            ->addSelect(DB::raw('SUM(CASE WHEN is_win = 1 THEN 1 ELSE 0 END) as win_count'))
			->orderByDesc('win_count')
            ->get()
            ->keyBy('user_id');


        $totalUsersTriesCount = $baseQuery->get()
            ->keyBy('user_id')->toArray();
        $result = (object)[
            'win_percent' => [],
            'win_count'   => []
        ];
	    TgLogger::log(
			[
				$topWinners->toArray(),
				$topWinners->isEmpty(),
				$topWinners->count()
			],
		    "top_winners_debug"
	    );

	    if ($topWinners->isEmpty()) {
            $message = "Никто из вас неудачников ничего и никогда не выигрывал в своей жизни. Идите умойтесь.";
            $tgBot = new Bot($this->chatID);
            $tgBot->sendMessage($message);
            return null;
        }
        foreach ($topWinners as $topWinner) {

            $totalUserSpinsCount =
                $totalUsersTriesCount[$topWinner->user_id]['total_count'];
            $userPercentStats = (object)[
                'name'           => $topWinner->user->name,
                'userWinPercent' => 0,
                'spentOnSpins' => $totalUserSpinsCount * $this->spinPrice,
                'totalCount' => $totalUserSpinsCount,

            ];
            $userCountStats = (object)[
                'userWinCount' => $topWinner->win_count,
                'name'         => $topWinner->user->name,
            ];

            if ($topWinner->win_count != 0) {
                $userWinPercent = ($topWinner->win_count
                        / $totalUsersTriesCount[$topWinner->user_id]['total_count']) * 100;
                $userPercentStats->userWinPercent = $userWinPercent;
                $result->win_percent[$topWinner->user_id] = $userPercentStats;
                $result->win_count[$topWinner->user_id] = $userCountStats;
            } else {
                $result->win_percent[$topWinner->user_id] = (object)[
                    'name'           => $topWinner->user->name,
                    'userWinPercent' => 0,
                ];
                $result->win_count[$topWinner->user_id] = (object)[
                    'userWinCount' => 0,
                    'name'         => $topWinner->user->name,
                ];
            }
        }
        return $result;
    }

    public function getMostWinsByMoney(): Collection
    {
        $topWinners = \App\Models\GamblingMessage::with(['user' => function ($query) {
            $query->select('name','tg_user_id');//тут нужно добавить
            // tg_user_id чтобы он по связи его нашел. можно ли в модели (в
            // юзер модели или в гэмблинг модели сделать чтобы он это сам
            // делал?)
        }])
            ->where('is_win', '=', true)
            ->where('chat_id', '=', $this->chatID)
            ->select(
                'user_id',
                DB::raw('COUNT(*) as win_count'),
                DB::raw('SUM(win_price) as win_sum'),
            )
            ->groupBy('user_id')
            ->orderByDesc('win_sum')
            ->get();
//            ->toRawSql();
//        dd($topWinners);

        return $topWinners;
    }
}
