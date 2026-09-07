<?php

namespace App\Models\Users;

use App\Models\Authority\Authority;
use App\Models\Authors\Author;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property int $id
 * @property int $user_id
 * @property int $model_id
 * @property string $model_type
 * @property boolean $enabled
 *
 * @property-read Authority|Author|null $model
 */
class Merchant extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'model_id',
        'model_type',
        'enabled'
    ];

    /**
     * @return MorphTo
     */
    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): void
    {
        $this->user_id = $user_id;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getModelId(): int
    {
        return $this->model_id;
    }

    public function setModelId(int $model_id): void
    {
        $this->model_id = $model_id;
    }

    public function getModelType(): string
    {
        return $this->model_type;
    }

    public function setModelType(string $model_type): void
    {
        $this->model_type = $model_type;
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
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
