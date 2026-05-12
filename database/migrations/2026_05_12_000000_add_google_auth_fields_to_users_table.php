<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('google_id')->nullable()->unique()->after('remember_token');
            $table->string('google_avatar')->nullable()->after('google_id');
            $table->timestamp('google_linked_at')->nullable()->after('google_avatar');
            $table->timestamp('password_set_at')->nullable()->after('password');
        });

        DB::table('users')
            ->whereNotNull('password')
            ->update(['password_set_at' => now()]);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'google_id',
                'google_avatar',
                'google_linked_at',
                'password_set_at',
            ]);
        });
    }
};
