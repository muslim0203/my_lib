<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $user_id
 * @property string $token
 * @property string $expire_at
 * @property boolean $enabled
 *
 * @property-read User|null $user
 */
class UsersVerifyMailToken extends Model
{
    use HasFactory;

    protected $primaryKey = 'user_id';
    public $incrementing = false;
    protected $fillable = [
        'user_id',
        'token',
        'expire_at',
        'enabled'
    ];

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): void
    {
        $this->user_id = $user_id;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function setToken(int $token): void
    {
        $this->token = (string)$token;
    }

    public function getExpireAt(): string
    {
        return $this->expire_at;
    }

    public function setExpireAt(string $expire_at): void
    {
        $this->expire_at = $expire_at;
    }

    /**
     * @return bool
     */
    public function isExpiredToken(): bool
    {
        return now()->format('Y-m-d H:i:s') <= $this->expire_at;
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): void
    {
        $this->enabled = $enabled;
    }

    /**
     * @return bool
     */
    public function isEnable(): bool
    {
        return $this->enabled === true;
    }
}
