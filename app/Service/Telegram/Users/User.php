<?php

namespace App\Service\Telegram\Users\User;

use App\Models\User as UserModel;

class User
{


	public static function register(string $username, string $chatId, string $name):
	void
	{

		$isUserExists = !empty(
		UserModel::query()
			->where('username', $username)
			->first()
		);

		if ($isUserExists) {
			return;
		}

		UserModel::create([
			'username' => $username,
			'chat_id'  => $chatId,
			'name'     => $name,
		]);
	}
}
