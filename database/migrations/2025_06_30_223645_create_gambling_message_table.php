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
        Schema::create('gambling_message', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('chat_id');
            $table->string('emoji_type');
            $table->boolean('is_win');
            $table->string('win_value');
            $table->string('win_price');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('tg_user_id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gambling_message');
    }

};
