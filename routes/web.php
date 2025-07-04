<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [\App\Http\Controllers\Controller::class, 'index'])->name('home');
Route::get('/releases', [\App\Http\Controllers\Controller::class, 'releases'])->name('releases');
Route::get('/fuck_you', [\App\Http\Controllers\Controller::class,
                         'fuckYou'])->name('fuck_you');

Route::prefix('api')->group(function () {
	Route::post(
		'/handle_gambling_message',
		[\App\Service\Telegram\Router::class, 'route']
	)
		->withoutMiddleware(['web', 'csrf', 'VerifyCsrfToken'])
		->middleware(
			[
				'api',
				\App\Http\Middleware\VerifyTelegramWebhook::class
			]
		) //TODO[изучить]:тут почему-то если вместо
		// VerifyTelegramWebhook
		// класса
		// юзать telegram.webhook алиас - будет ошибка, он будето не находит
		// класс под этим алиасом, хотя в хттп кернеле я его зарегал,
		->name('api.handle_message');
});
