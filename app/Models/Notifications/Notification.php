<?php

namespace App\Models\Notifications;

use App\Models\Notifications\Enums\EnumNotificationMessage;
use App\Models\Notifications\Enums\EnumNotificationType;
use App\Models\Users\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int|null $id
 * @property int|null $notification_type_id
 * @property int|null $notification_message_id
 * @property string|null $model
 * @property string|null $apply_id
 * @property string|null $apply_link
 * @property boolean $enabled
 * @property string $message_oz
 * @property string $message_uz
 * @property string $message_ru
 * @property integer $user_id
 * @property string $created_at
 * @property string|null $updated_at
 *
 * @property-read EnumNotificationType $notificationType
 * @property-read EnumNotificationMessage $notificationMessage
 * @property-read User|null $user
 */
class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'notification_type_id',
        'notification_message_id',
        'model',
        'apply_id',
        'apply_link',
        'enabled',
        'message_oz',
        'message_uz',
        'message_ru',
        'user_id'
    ];

    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): ?string
    {
        return $this->updated_at;
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function notificationType(): BelongsTo
    {
        return $this->belongsTo(EnumNotificationType::class);
    }

    /**
     * @return BelongsTo
     */
    public function notificationMessage(): BelongsTo
    {
        return $this->belongsTo(EnumNotificationMessage::class);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getNotificationTypeId(): ?int
    {
        return $this->notification_type_id;
    }

    public function setNotificationTypeId(?int $notification_type_id): void
    {
        $this->notification_type_id = $notification_type_id;
    }

    public function getNotificationMessageId(): ?int
    {
        return $this->notification_message_id;
    }

    public function setNotificationMessageId(?int $notification_message_id): void
    {
        $this->notification_message_id = $notification_message_id;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(?string $model): void
    {
        $this->model = $model;
    }

    public function getApplyId(): ?string
    {
        return $this->apply_id;
    }

    public function setApplyId(?string $apply_id): void
    {
        $this->apply_id = $apply_id;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): void
    {
        $this->enabled = $enabled;
    }

    public function getApplyLink(): ?string
    {
        return $this->apply_link;
    }

    public function setApplyLink(?string $apply_link): void
    {
        $this->apply_link = $apply_link;
    }

    public function getMessageOz(): string
    {
        return $this->message_oz;
    }

    public function setMessageOz(string $message_oz): void
    {
        $this->message_oz = $message_oz;
    }

    public function getMessageUz(): string
    {
        return $this->message_uz;
    }

    public function setMessageUz(string $message_uz): void
    {
        $this->message_uz = $message_uz;
    }

    public function getMessageRu(): string
    {
        return $this->message_ru;
    }

    public function setMessageRu(string $message_ru): void
    {
        $this->message_ru = $message_ru;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): void
    {
        $this->user_id = $user_id;
    }
}
