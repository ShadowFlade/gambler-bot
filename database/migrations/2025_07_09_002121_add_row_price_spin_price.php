<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $price = new \App\Models\Price();
        $price->type = 'spin';
        $price->price = 1;
        $price->chat_id = env('PACANI_CHAT_ID');
        $price->save();

        $price = new \App\Models\Price();
        $price->type = 'spin';
        $price->price = 1;
        $price->chat_id = env('TEST_CHAT_ID');
        $price->save();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \App\Models\Price::query()->where('chat_id', env('PACANI_CHAT_ID'))->delete();
    }
};
