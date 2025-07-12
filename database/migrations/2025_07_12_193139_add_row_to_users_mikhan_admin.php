<?php

use App\Service\Telegram\Users\Roles;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        \App\Models\User::query()
            ->where("tg_user_id", "=", env('MIKHAN_TG_USER_ID'))
            ->update(["role" => Roles::CHAT_ADMIN->value]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \App\Models\User::query()
            ->where("tg_user_id", "=", env('MIKHAN_TG_USER_ID'))
            ->update(["role" => Roles::LUDIK->value]);
    }
};
