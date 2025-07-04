<?php

namespace App\Service\Gambling;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class Statistics
{
	public function __construct(private string $chatID) { }

	public function getMostWinsByCount(): object
	{
		$baseQuery = \App\Models\GamblingMessage::with('user')
			->select('user_id', DB::raw('COUNT(*) as win_count'), 'user.name')
			->where('chat_id', '=', $this->chatID)
			->groupBy('user_id')
			->orderByDesc('win_count');

		$topWinners = (clone $baseQuery)
			->where('is_win', true)
			->limit(3)
			->get()
			->keyBy('user_id');

		$totalUsersTriesCount = $baseQuery->get()
			->keyBy('user_id')
			->count();
		$result = (object)[
			'win_percent' => [],
			'win_count'   => []
		];

		foreach ($topWinners as $topWinner) {

			$userWinCount = $totalUsersTriesCount[$topWinner->user_id];
			$userPercentStats = (object)[
				'name'           => $topWinner->user->name,
				'userWinPercent' => 0,
			];
			$userCountStats = (object)[
				'userWinCount' => $userWinCount,
				'name'         => $topWinner->user->name,
			];
			if ($userWinCount != 0) {
				$userWinPercent = ($topWinner->win_count
						/ $totalUsersTriesCount[$topWinner->user_id]) * 100;
				$userPercentStats->userWinPercent = $userWinPercent;
				$result->win_percent[$topWinner->user_id] = $userPercentStats;
				$result->win_count[$topWinner->user_id] = $userCountStats;
			}
		}
		return $result;
	}

	public function getMostWinsByMoney(): Collection
	{
		$topWinners = \App\Models\GamblingMessage::with('user')
			->where('is_win', '=', true)
			->where('chat_id', '=', $this->chatID)
			->select(
				'user_id',
				DB::raw('COUNT(*) as win_count'),
				DB::raw('SUM(win_price) as win_sum'),
				'user.name'
			)
			->groupBy('user_id')
			->orderByDesc('win_sum')
			->limit(3)
			->get();
		return $topWinners;
	}
}