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
        Schema::create('view_trackings', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address'); // IP 地址
            $table->string('user_agent')->nullable(); // 瀏覽器信息
            $table->string('trackable_type'); // 追蹤類型 (resume 或 project)
            $table->unsignedBigInteger('trackable_id'); // 追蹤對象 ID
            $table->timestamp('viewed_at'); // 瀏覽時間
            $table->timestamps();

            // 索引
            $table->index(['ip_address', 'trackable_type', 'trackable_id']);
            $table->index(['trackable_type', 'trackable_id']);
            $table->index('viewed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('view_trackings');
    }
};
