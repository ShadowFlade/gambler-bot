<?php

namespace App\Http\Controllers;

use App\Models\Price;
use App\Service\Gambling\Enum\WinningSubtype;
use App\Service\Gambling\Statistics;
use Illuminate\Http\Request;

class Controller
{
	public function index(Request $request)
	{
		$count = \App\Service\Telegram\Users\User::getTotalUniqueUsers();
		return view('welcome', ['total_active_users' => $count]);
	}

	public function releases(Request $request)
	{
		return view('releases');
	}

	public function fuckYou(Request $request)
	{
		return view('fuck_you');
	}

	public function test_bot(Request $request)
	{
		$stats = new Statistics("-1002706194619");
		$price = $stats->getSpinPrice(null);
		dd($price);

		$coef = Price::query()
			->where('type', '=', 'win')
			->where('sub_type', '=', WinningSubtype::CHERRIES->value)
			->select('price')
			->first();
		dd($coef->price);
	}
}
