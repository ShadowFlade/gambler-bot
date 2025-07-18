<?php

namespace App\Http\Controllers;

use App\Service\Telegram\Users\Roles;
use Illuminate\Http\Request;

class User extends Controller
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
        $chatId = $request['message']['chat']['id'];
        $username = $request['message']['from']['username'];
        $name = $request['message']['from']['first_name'] . ' ' . $request['message']['from']['last_name'];
        $role = Roles::LUDIK->value;
        $tgUserId = $request['message']['from']['id'];
        \App\Service\Telegram\Users\User::register(
            $username,
            $chatId,
            $name,
            $tgUserId,
            $role
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
