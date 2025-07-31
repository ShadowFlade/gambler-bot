<?php

use Illuminate\Database\Migrations\Migration;
use \App\Service\Gambling\Enum\WinningSubtype;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $price = new \App\Models\Price();
        $price->type = 'win';
        $price->sub_type = WinningSubtype::JACKPOT;
        $price->price = 80;
        $price->active_until = '2040-01-01 00:00:00';
        $price->chat_id = env('MAIN_CHAT_ID');
        $price->save();

        $price = new \App\Models\Price();
        $price->type = 'win';
        $price->sub_type = WinningSubtype::BARS;
        $price->price = 20;
        $price->active_until = '2040-01-01 00:00:00';
        $price->chat_id = env('MAIN_CHAT_ID');
        $price->save();

        $price = new \App\Models\Price();
        $price->type = 'win';
        $price->sub_type = WinningSubtype::CHERRIES;
        $price->price = 10;
        $price->active_until = '2040-01-01 00:00:00';
        $price->chat_id = env('MAIN_CHAT_ID');
        $price->save();

        $price = new \App\Models\Price();
        $price->type = 'win';
        $price->sub_type = WinningSubtype::LEMONS;
        $price->price = 5;
        $price->active_until = '2040-01-01 00:00:00';
        $price->chat_id = env('MAIN_CHAT_ID');
        $price->save();

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \App\Models\Price::query()
            ->where('type', '=', 'win')
            ->delete();
    }
};
