<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ViewTracking extends Model
{
    /**
     * 可批量賦值的屬性
     *
     * @var array<string>
     */
    protected $fillable = [
        'ip_address',
        'user_agent',
        'trackable_type',
        'trackable_id',
        'viewed_at',
    ];

    /**
     * 需要轉換的屬性
     *
     * @var array<string, string>
     */
    protected $casts = [
        'viewed_at' => 'datetime',
    ];

    /**
     * 獲取被追蹤的對象
     */
    public function trackable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * 檢查是否應該計入瀏覽數
     * 同一個 IP 在 24 小時內只能計算一次
     *
     * @param string $ipAddress
     * @param string $trackableType
     * @param int $trackableId
     * @return bool
     */
    public static function shouldCountView(string $ipAddress, string $trackableType, int $trackableId): bool
    {
        $lastView = static::where('ip_address', $ipAddress)
            ->where('trackable_type', $trackableType)
            ->where('trackable_id', $trackableId)
            ->where('viewed_at', '>=', now()->subDay()) // 24 小時內
            ->first();

        return $lastView === null;
    }

    /**
     * 記錄一次瀏覽
     *
     * @param string $ipAddress
     * @param string $userAgent
     * @param string $trackableType
     * @param int $trackableId
     * @return static
     */
    public static function recordView(string $ipAddress, string $userAgent, string $trackableType, int $trackableId): static
    {
        return static::create([
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'trackable_type' => $trackableType,
            'trackable_id' => $trackableId,
            'viewed_at' => now(),
        ]);
    }
}
