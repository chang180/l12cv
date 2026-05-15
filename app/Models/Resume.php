<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'template',
        'summary',
        'skills',
        'languages',
        'certifications',
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
        'skills' => 'array',
        'languages' => 'array',
        'certifications' => 'array',
        'is_public' => 'boolean',
    ];

    /**
     * 獲取擁有此履歷的使用者
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function versions(): HasMany
    {
        return $this->hasMany(ResumeVersion::class);
    }

    public function recordVersion(string $event = 'manual'): ResumeVersion
    {
        $resume = $this->fresh() ?? $this;

        return $this->versions()->create([
            'event' => $event,
            'title' => $resume->title,
            'snapshot' => [
                'title' => $resume->title,
                'template' => $resume->template,
                'summary' => $resume->summary,
                'skills' => $resume->skills ?? [],
                'languages' => $resume->languages ?? [],
                'certifications' => $resume->certifications ?? [],
                'education' => $resume->education ?? [],
                'experience' => $resume->experience ?? [],
                'is_public' => $resume->is_public,
            ],
        ]);
    }

    /**
     * 增加履歷瀏覽數（帶防刷機制）
     *
     * @return bool 是否成功增加瀏覽數
     */
    public function incrementViewsWithTracking(string $ipAddress, string $userAgent = ''): bool
    {
        // 檢查是否應該計入瀏覽數
        if (! ViewTracking::shouldCountView($ipAddress, 'resume', $this->id)) {
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
     */
    public function incrementViews(): void
    {
        $this->increment('views');
    }
}
