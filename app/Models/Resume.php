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
        'title',
        'slug',
        'summary',
        'experience',
        'education',
        'is_public',
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
}
