<?php

namespace App\Models\Authors;

use App\Models\Users\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int|null $id
 * @property int $model_id
 * @property string $model
 * @property int $created_by
 * @property string $description
 * @property boolean $is_confirmed
 *
 * @property-read User $createdBy
 */
class AuthorRequest extends Model
{
    protected $fillable = [
        'model_id',
        'model',
        'is_confirmed',
        'description',
        'created_by'
    ];

    /**
     * @return BelongsTo
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getCreatedBy(): int
    {
        return $this->created_by;
    }

    public function setCreatedBy(int $created_by): void
    {
        $this->created_by = $created_by;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function isIsConfirmed(): bool
    {
        return $this->is_confirmed;
    }

    public function setIsConfirmed(bool $is_confirmed): void
    {
        $this->is_confirmed = $is_confirmed;
    }

    public function getModelId(): int
    {
        return $this->model_id;
    }

    public function setModelId(int $model_id): void
    {
        $this->model_id = $model_id;
    }

    public function getModel(): string
    {
        return $this->model;
    }

    public function setModel(string $model): void
    {
        $this->model = $model;
    }
}
