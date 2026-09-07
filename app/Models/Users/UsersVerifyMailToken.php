<?php

namespace App\Models\Users;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $user_id
 * @property string $token
 * @property string|null $token_hash
 * @property int $attempts
 * @property string|null $used_at
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
    /**
     * `enabled` ni aniq boolean'ga keltirish shart: ba'zi drayverlar
     * (masalan SQLite) uni 1/0 butun son sifatida qaytaradi va
     * `=== true` tekshiruvi jimgina false bo'lib qolardi.
     */
    protected $casts = [
        'enabled' => 'boolean',
        'attempts' => 'integer',
    ];

    protected $fillable = [
        'user_id',
        'token',
        'token_hash',
        'attempts',
        'used_at',
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

    public function setToken(int|string $token): void
    {
        $this->token = (string)$token;
    }

    /**
     * Kodning hash'i. Ochiq matnli kod hech qachon saqlanmaydi.
     *
     * @return string|null
     */
    public function getTokenHash(): ?string
    {
        return $this->token_hash;
    }

    public function setTokenHash(?string $token_hash): void
    {
        $this->token_hash = $token_hash;
    }

    public function getAttempts(): int
    {
        return (int)$this->attempts;
    }

    public function setAttempts(int $attempts): void
    {
        $this->attempts = $attempts;
    }

    public function getUsedAt(): ?string
    {
        return $this->used_at;
    }

    public function setUsedAt(?string $used_at): void
    {
        $this->used_at = $used_at;
    }

    /**
     * Kod allaqachon ishlatilganmi (bir martalik).
     *
     * @return bool
     */
    public function isConsumed(): bool
    {
        return !empty($this->used_at);
    }

    /**
     * Kod muddati o'tganmi.
     *
     * Eski `isExpiredToken()` nomi teskari ma'no bergani uchun
     * (u "muddati o'tmagan" holatda true qaytaradi) alohida, to'g'ri
     * ma'noli metod kiritildi.
     *
     * @return bool
     */
    public function isExpired(): bool
    {
        if (empty($this->expire_at)) {
            return true;
        }

        return now()->greaterThan(Carbon::parse($this->expire_at));
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
