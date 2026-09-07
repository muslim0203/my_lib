<?php

namespace App\Core\Repository\Notification;

use App\Models\Notifications\Enums\EnumNotificationMessage;
use Illuminate\Database\Eloquent\Builder;

class NotificationMessageRepository
{
    /**
     * @param int $type_id
     * @return Builder|EnumNotificationMessage
     */
    public function getByTypeId(int $type_id): Builder|EnumNotificationMessage
    {
        return EnumNotificationMessage::query()
            ->where('notification_type_id', $type_id)
            ->where('enabled', true)
            ->firstOrFail();
    }
}
