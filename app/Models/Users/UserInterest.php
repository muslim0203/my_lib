<?php

namespace App\Models\Users;

use App\Models\Enums\EnumProductGenre;
use App\Models\Enums\EnumProductTag;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property int|null $id
 * @property int $user_id
 * @property int $model_id
 * @property string $model_type
 * @property boolean $enabled
 *
 * @property-read User $user
 * @property-read EnumProductGenre|EnumProductTag $model
 */
class UserInterest extends Model
{

    protected $fillable = [
        'user_id',
        'model_type',
        'model_id',
        'enabled'
    ];

    /**
     * @return MorphTo
     */
    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * @return HasOne
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'user_id', 'id');
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): void
    {
        $this->user_id = $user_id;
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
}
