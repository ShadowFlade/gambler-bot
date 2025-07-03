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
	Route::post('/handle_gambling_message', [App\Service\Telegram\Router::class, 'route'])
		->withoutMiddleware(['web', 'csrf', 'VerifyCsrfToken'])
		->middleware('api')
		->name('api.handle_message');
});


//Route::prefix('api')->group(function () {
//	Route::post('/handle_gambling_message', function(\Illuminate\Http\Request $request){
//		Log::build([
//			'driver' => 'daily',
//			'name' => 'info',
//			'path' => storage_path('logs/gambling.log'),
//		])->info('start???');
//		echo 'test';
//	})
//		->withoutMiddleware(['web', 'csrf', 'VerifyCsrfToken'])
//		->middleware('api')
//		->name('api.handle_message');
//});
