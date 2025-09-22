<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * 更新履歷預設值
     * 將 "尚未設定標題" 改為空字串
     */
    public function up(): void
    {
        // 更新現有的履歷記錄
        DB::table('resumes')
            ->where('title', '尚未設定標題')
            ->update(['title' => '']);
            
        DB::table('resumes')
            ->where('summary', '請輸入您的履歷內容...')
            ->update(['summary' => '']);
    }

    /**
     * 回滾遷移
     */
    public function down(): void
    {
        // 回滾到原來的預設值
        DB::table('resumes')
            ->where('title', '')
            ->update(['title' => '尚未設定標題']);
            
        DB::table('resumes')
            ->where('summary', '')
            ->update(['summary' => '請輸入您的履歷內容...']);
    }
};