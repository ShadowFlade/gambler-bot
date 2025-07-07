<?php

namespace App\Http\Controllers;

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
}
