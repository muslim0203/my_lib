<?php

namespace App\Models\Notifications\Enums;

use Database\Factories\NotificationMessageFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int|null $id
 * @property int $notification_type_id
 * @property string $message_oz
 * @property string $message_uz
 * @property string $message_ru
 * @property boolean $enabled
 *
 * @property-read EnumNotificationType $notificationType
 */
class EnumNotificationMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'notification_type_id',
        'message_oz',
        'message_uz',
        'message_ru',
        'enabled'
    ];

    /**
     * @return BelongsTo
     */
    public function notificationType(): BelongsTo
    {
        return $this->belongsTo(EnumNotificationType::class);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getNotificationTypeId(): int
    {
        return $this->notification_type_id;
    }

    public function setNotificationTypeId(int $notification_type_id): void
    {
        $this->notification_type_id = $notification_type_id;
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

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): void
    {
        $this->enabled = $enabled;
    }

    /**
     * @return NotificationMessageFactory|Factory
     */
    protected static function newFactory(): NotificationMessageFactory|Factory
    {
        return NotificationMessageFactory::new();
    }
}
