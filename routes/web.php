<?php
//
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GamblingMessageController;

//
Route::get('/', function () {
	return view('welcome');
});
//
Route::prefix('api')->group(function () {
	Route::post('/handle_gambling_message', [GamblingMessageController::class, 'store'])
		->withoutMiddleware(['web', 'csrf', 'VerifyCsrfToken'])
		->middleware('api')
		->name('api.handle_message');
});
