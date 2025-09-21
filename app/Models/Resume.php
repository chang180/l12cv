<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * 履歷模型
 * 用於管理使用者的履歷資料
 */
class Resume extends Model
{
    use HasFactory;

    /**
     * 可批量賦值的屬性
     *
     * @var array<string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'summary',
        'experience',
        'education',
        'is_public',
        'views',
    ];

    /**
     * 應該被轉換成原生類型的屬性
     *
     * @var array<string, string>
     */
    protected $casts = [
        'experience' => 'array',
        'education' => 'array',
        'is_public' => 'boolean',
    ];

    /**
     * 獲取擁有此履歷的使用者
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 增加履歷瀏覽數（帶防刷機制）
     *
     * @param string $ipAddress
     * @param string $userAgent
     * @return bool 是否成功增加瀏覽數
     */
    public function incrementViewsWithTracking(string $ipAddress, string $userAgent = ''): bool
    {
        // 檢查是否應該計入瀏覽數
        if (!ViewTracking::shouldCountView($ipAddress, 'resume', $this->id)) {
            return false;
        }

        // 記錄瀏覽
        ViewTracking::recordView($ipAddress, $userAgent, 'resume', $this->id);
        
        // 增加瀏覽數
        $this->increment('views');
        
        return true;
    }

    /**
     * 增加履歷瀏覽數（舊方法，向後兼容）
     *
     * @return void
     */
    public function incrementViews(): void
    {
        $this->increment('views');
    }
}
