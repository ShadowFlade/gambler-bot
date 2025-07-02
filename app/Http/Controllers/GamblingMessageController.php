<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGamblingMessageRequest;
use App\Http\Requests\UpdateGamblingMessageRequest;
use App\Models\GamblingMessage;

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
    public function store(StoreGamblingMessageRequest $request)
    {
		$gamblingMessage = new \App\Service\Gambling\GamblingMessage();
	    $gamblingMessage->handleMessage([
            'chat_id'    => $request->input('chat_id'),
            'emoji_type' => $request->input('emoji_type'),
            'is_win'     => $request->input('is_win'),
            'win_value'  => $request->input('win_value'),
            'user_id'    => $request->input('user_id'),
        ]);
		echo 'suck';
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
