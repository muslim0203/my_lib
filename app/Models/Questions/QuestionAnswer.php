<?php

namespace App\Models\Questions;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int|null $id
 * @property string $content_oz
 * @property string $content_uz
 * @property string $content_ru
 * @property string|null $created_at
 * @property boolean $enabled
 * @property Question $question
 */
class QuestionAnswer extends Model
{
    use HasFactory;

    protected $table = 'questions_answers';

    protected $fillable = [
        'question_id',
        'content_oz',
        'content_uz',
        'content_ru',
        'enabled',
        'created_at',
        'updated_at'
    ];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getContentOz(): string
    {
        return $this->content_oz;
    }

    public function setContentOz(string $content_oz): void
    {
        $this->content_oz = $content_oz;
    }

    public function getContentUz(): string
    {
        return $this->content_uz;
    }

    public function setContentUz(string $content_uz): void
    {
        $this->content_uz = $content_uz;
    }

    public function getContentRu(): string
    {
        return $this->content_ru;
    }

    public function setContentRu(string $content_ru): void
    {
        $this->content_ru = $content_ru;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): void
    {
        $this->enabled = $enabled;
    }
    public function getCreatedAt(): string
    {
        return $this->created_at;
    }
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}
