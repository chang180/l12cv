<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Str;

/**
 * 使用者模型觀察者
 * 用於監聽使用者模型的各種事件，並自動執行相應的操作
 */
class UserObserver
{
    /**
     * 監聽使用者建立事件
     * 當新使用者註冊時，自動為其建立一份基本的履歷
     *
     * @param User $user 新建立的使用者實例
     */
    public function created(User $user): void
    {
        // 使用關聯方法創建履歷，這樣會自動設置 user_id
        $user->resume()->create([
            'slug' => Str::slug($user->name) . '-' . Str::random(6),
            'title' => '尚未設定標題',
            'summary' => '請輸入您的履歷內容...',
            'education' => [],
            'experience' => [],
            'is_public' => false
        ]);
    }
}
