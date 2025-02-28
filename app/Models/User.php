<?php
namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Resume;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * 使用者模型
 *
 * 此模型用於管理系統中的使用者資料
 *
 * @property int $id 使用者ID
 * @property string $name 使用者名稱
 * @property string $email 電子郵件
 * @property string $password 密碼(已加密)
 * @property \Illuminate\Support\Carbon|null $email_verified_at 郵件驗證時間
 * @property string|null $remember_token 記住登入token
 * @property \Illuminate\Support\Carbon $created_at 建立時間
 * @property \Illuminate\Support\Carbon $updated_at 更新時間
 * @property-read \App\Models\Resume|null $resume 關聯的履歷資料
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection $notifications 通知集合
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * 可批量寫入的欄位
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];
    /**
     * 序列化時要隱藏的欄位
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    /**
     * 需要自動轉換的欄位類型
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * 取得使用者關聯的履歷
     * 一個使用者只能有一份履歷
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne<Resume>
     */
    public function resume()
    {
        return $this->hasOne(Resume::class);
    }

    /**
     * 取得使用者名稱的縮寫
     *
     * @return string
     */
    public function initials(): string
    {
        $words = explode(' ', $this->name);
        $initials = '';

        foreach ($words as $word) {
            $initials .= mb_substr($word, 0, 1);
        }

        return mb_strtoupper($initials);
    }
}
