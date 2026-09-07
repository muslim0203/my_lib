<?php

namespace App\Core\Repository\LInks;

use App\Models\Links\LinkAuthorSubscribers;
use PHPUnit\Event\Subscriber;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class LinkAuthorSubscriberRepository
{
    /**
     * @param LinkAuthorSubscribers $linkAuthorSubscribers
     * @param array $options
     * @return void
     */
    public function save(LinkAuthorSubscribers $linkAuthorSubscribers, array $options = []): void
    {
        if (!$linkAuthorSubscribers->save($options)) {
            throw new \RuntimeException(__('client.Save error link author subscriptions'));
        }
    }

    /**
     * @param int $authorId
     * @return int
     */
    public function subscriberCount(int $authorId): int
    {
        return LinkAuthorSubscribers::query()
            ->where(['author_id' => $authorId])
            ->where('enabled', true)
            ->count();
    }

    /**
     * @param int $authorId
     * @param int $subscriberId
     * @return bool
     */
    public function checkSubscribe(int $authorId, int $subscriberId): bool
    {
        return LinkAuthorSubscribers::query()
            ->where(['author_id' => $authorId, 'subscriber_id' => $subscriberId])
            ->exists();
    }

    public function delete(int $authorId, int $subscriberId): mixed
    {
        $model = LinkAuthorSubscribers::query()->where('author_id', $authorId)->where('subscriber_id', $subscriberId)->first();
        if (!$model) {
            throw new NotFoundHttpException(__('client.Delete error link author subscriber'));
        }
        return $model->delete();
    }
}
