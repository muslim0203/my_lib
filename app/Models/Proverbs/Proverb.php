<?php

namespace App\Models\Proverbs;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $author_uz
 * @property string $author_oz
 * @property string $author_ru
 * @property string $content_oz
 * @property string $content_uz
 * @property string $content_ru
 * @property boolean $enabled
 * @property string $created_at
 * @property string $updated_at
 **/


class Proverb extends Model
{
    protected $fillable = [
        'author_oz',
        'author_uz',
        'author_ru',
        'content_oz',
        'content_uz',
        'content_ru',
        'enabled',
    ];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuthorOz(): string
    {
        return $this->author_oz;
    }
    public function getAuthorUz(): string
    {
        return $this->author_uz;
    }
    public function getAuthorRu(): string
    {
        return $this->author_ru;
    }

    public function getContentOz(): string
    {
        return $this->content_oz;
    }
    public function getContentUz(): string
    {
        return $this->content_uz;
    }
    public function getContentRu(): string
    {
        return $this->content_ru;
    }

    public function getEnabled(): bool
    {
        return $this->enabled;
    }
    public function getCreatedAt(): string
    {
        return $this->created_at;
    }
}
