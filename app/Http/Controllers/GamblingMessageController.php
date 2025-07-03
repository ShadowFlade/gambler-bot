<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGamblingMessageRequest;
use App\Http\Requests\UpdateGamblingMessageRequest;
use App\Models\GamblingMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GamblingMessageController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $tgMessage = $request->all();
		$gamblingMessage = new \App\Service\Gambling\GamblingMessage();
//        Log::build([
//            'driver' => 'daily',
//            'name' => 'info',
//            'path' => storage_path('logs/gambling.log'),
//        ])->info(['json' => $request->json()]);
//        Log::build([
//            'driver' => 'daily',
//            'name' => 'info',
//            'path' => storage_path('logs/gambling.log'),
//        ])->info(['all' => $message]);

	    $gamblingMessage->handleMessage($tgMessage);
    }

    /**
     * Display the specified resource.
     */
    public function show(GamblingMessage $gamblingMessage)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GamblingMessage $gamblingMessage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGamblingMessageRequest $request, GamblingMessage $gamblingMessage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GamblingMessage $gamblingMessage)
    {
        //
    }

	public function authorize()
	{
		return true;
	}

}
