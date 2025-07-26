<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 作品集項目模型
 *
 * @property int $id 項目ID
 * @property int $user_id 用戶ID
 * @property string $title 項目標題
 * @property string|null $description 項目描述
 * @property string|null $thumbnail 縮略圖路徑
 * @property string|null $url 項目鏈接
 * @property string|null $github_url GitHub鏈接
 * @property array|null $technologies 使用的技術
 * @property \Illuminate\Support\Carbon|null $completion_date 完成日期
 * @property bool $is_featured 是否為特色項目
 * @property int $order 排序順序
 * @property \Illuminate\Support\Carbon $created_at 創建時間
 * @property \Illuminate\Support\Carbon $updated_at 更新時間
 * @property-read \App\Models\User $user 關聯的用戶
 */
class Project extends Model
{
    use HasFactory;

    /**
     * 可以批量賦值的屬性
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'thumbnail',
        'url',
        'github_url',
        'technologies',
        'completion_date',
        'is_featured',
        'order',
    ];

    /**
     * 需要轉換的屬性
     *
     * @var array<string, string>
     */
    protected $casts = [
        'technologies' => 'array',
        'completion_date' => 'date',
        'is_featured' => 'boolean',
    ];

    /**
     * 獲取擁有此項目的用戶
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 獲取項目縮略圖的URL
     *
     * @return string
     */
    public function getThumbnailUrl()
    {
        if ($this->thumbnail) {
            return \Storage::url($this->thumbnail);
        }

        return null;
    }

    /**
     * 獲取格式化的完成日期
     *
     * @param string $format
     * @return string|null
     */
    public function getFormattedCompletionDate($format = 'Y-m-d')
    {
        return $this->completion_date ? $this->completion_date->format($format) : null;
    }

    /**
     * 獲取特色項目
     *
     * @param int $userId
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getFeatured($userId, $limit = 3)
    {
        return self::where('user_id', $userId)
            ->where('is_featured', true)
            ->orderBy('order')
            ->limit($limit)
            ->get();
    }

    /**
     * 獲取用戶的所有項目
     *
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getAllForUser($userId)
    {
        return self::where('user_id', $userId)
            ->orderBy('order')
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
