<?php

namespace App\Models\Request;

use App\Models\Enums\EnumRequestType;
use App\Models\Steps\ProcessStep;
use App\Models\Users\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $step_id
 * @property int $author_id
 * @property int $confirm_author_id
 * @property int $reject_author_id
 * @property string $status
 * @property string $model
 * @property string $data
 * @property string|null $comment
 * @property integer|null $model_id
 * @property integer|null $request_type_id
 * @property string|null $changed_data
 * @property int|null $product_id
 * @property bool $is_agree
 * @property bool $is_editable
 * @property string $created_at
 * @property string $updated_at
 *
 * @property-read ProcessStep|null $step
 * @property-read User $author
 * @property-read User|null $rejectAuthor
 * @property-read User|null $confirmAuthor
 * @property-read EnumRequestType|null $requestType
 */
class Request extends Model
{
    use HasFactory;

    protected $fillable = [
        'is_editable',
        'step_id',
        'model',
        'data',
        'author_id',
        'confirm_author_id',
        'reject_author_id',
        'status',
        'model_id',
        'changed_data',
        'comment',
        'request_type_id',
        'is_agree'
    ];

    public function isIsEditable(): bool
    {
        return $this->is_editable;
    }

    public function setIsEditable(bool $is_editable): void
    {
        $this->is_editable = $is_editable;
    }

    /**
     * @return BelongsTo
     */
    public function requestType(): BelongsTo
    {
        return $this->belongsTo(EnumRequestType::class);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): void
    {
        $this->comment = $comment;
    }

    public function getModelId(): ?int
    {
        return $this->model_id;
    }

    public function setModelId(?int $model_id): void
    {
        $this->model_id = $model_id;
    }

    public function getRequestTypeId(): ?int
    {
        return $this->request_type_id;
    }

    public function setRequestTypeId(?int $request_type_id): void
    {
        $this->request_type_id = $request_type_id;
    }

    public function setChangedData(?string $changed_data): void
    {
        $this->changed_data = $changed_data;
    }

    public function getStepId(): int
    {
        return $this->step_id;
    }

    public function setStepId(int $step_id): void
    {
        $this->step_id = $step_id;
    }

    public function getAuthorId(): int
    {
        return $this->author_id;
    }

    public function setAuthorId(int $author_id): void
    {
        $this->author_id = $author_id;
    }

    public function getConfirmAuthorId(): int
    {
        return $this->confirm_author_id;
    }

    public function setConfirmAuthorId(int $confirm_author_id): void
    {
        $this->confirm_author_id = $confirm_author_id;
    }

    public function getRejectAuthorId(): int
    {
        return $this->reject_author_id;
    }

    public function setRejectAuthorId(int $reject_author_id): void
    {
        $this->reject_author_id = $reject_author_id;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getModel(): string
    {
        return $this->model;
    }

    public function setModel(string $model): void
    {
        $this->model = $model;
    }

    public function getData(): string
    {
        return $this->data;
    }

    public function setData(string $data): void
    {
        $this->data = $data;
    }

    public function setProductId(int $product_id)
    {
        $this->product_id = $product_id;
    }

    /**
     * @return BelongsTo
     */
    public function step(): BelongsTo
    {
        return $this->belongsTo(ProcessStep::class);
    }

    /**
     * @return BelongsTo
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function confirmAuthor(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function rejectAuthor(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isIsAgree(): bool
    {
        return $this->is_agree;
    }

    public function setIsAgree(bool $is_agree): void
    {
        $this->is_agree = $is_agree;
    }
}
