<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * 建立履歷資料表的遷移檔
 * 此資料表用於儲存使用者的履歷資訊，包含基本資料、學經歷等
 */
return new class extends Migration
{
    /**
     * 執行遷移
     * 建立 resumes 資料表，包含以下欄位：
     * - id: 主鍵
     * - user_id: 關聯到使用者表的外鍵，一個使用者只能有一份履歷
     * - slug: 用於產生友善的 URL，例如 /resume/john-doe
     * - title: 履歷標題
     * - summary: 自我介紹或摘要
     * - avatar: 大頭照路徑
     * - education: 學歷資料（JSON 格式）
     * - experience: 工作經驗（JSON 格式）
     * - is_public: 是否公開履歷
     * - timestamps: 建立及更新時間
     */
    public function up(): void
    {
        Schema::create('resumes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade');
            $table->string('slug')->unique();
            $table->string('title')->default('尚未設定標題');
            $table->text('summary')->nullable();
            $table->string('avatar')->nullable();
            $table->json('education')->nullable();
            $table->json('experience')->nullable();
            $table->boolean('is_public')->default(false);
            $table->timestamps();
        });
    }

    /**
     * 回滾遷移
     * 當需要回滾時，刪除 resumes 資料表
     */
    public function down(): void
    {
        Schema::dropIfExists('resumes');
    }
};
