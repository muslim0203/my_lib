<?php

namespace App\Core\Repository\Notification;

use App\Models\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class NotificationRepository
{
    /**
     * @param Notification $notification
     * @return void
     */
    public function save(Notification $notification): void
    {
        if (!$notification->save()) {
            throw new \RuntimeException(__('client.Notification save error'));
        }
    }

    /**
     * @return Builder[]|Collection<int, Notification>
     */
    public function findAllByActive(): Collection|array
    {
        return Notification::query()
            ->where('enabled', true)
            ->get();
    }

    /**
     * @param int $author_id
     * @return Collection|array
     */
    public function findAllByAuthor(int $author_id): Collection|array
    {
        return Notification::query()
            ->where('apply_id', $author_id)
            ->where('notification_type_id', 3)
            ->get();
    }
}
