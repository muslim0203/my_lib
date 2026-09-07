<?php

namespace App\Models\Links;

use App\Core\Helpers\Traits\HasCompositePrimaryKey;
use App\Models\Users\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $author_id
 * @property int $subscriber_id
 * @property boolean $enabled
 *
 * @property-read User $author
 */
class LinkAuthorSubscribers extends Model
{
    protected $table = 'link_author_subscribers';

    protected $fillable = [
        'author_id',
        'subscriber_id',
        'enabled'
    ];

    /**
     * @return HasOne
     */
    public function author(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'author_id');
    }

    /**
     * @return HasOne
     */
    public function subscriber(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'subscriber_id');
    }

    public function getAuthorId(): int
    {
        return $this->author_id;
    }

    public function setAuthorId(int $author_id): void
    {
        $this->author_id = $author_id;
    }

    public function getSubscriberId(): int
    {
        return $this->subscriber_id;
    }

    public function setSubscriberId(int $subscriber_id): void
    {
        $this->subscriber_id = $subscriber_id;
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
