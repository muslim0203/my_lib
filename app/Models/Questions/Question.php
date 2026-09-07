<?php

namespace App\Models\Questions;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int|null $id
 * @property string $title_oz
 * @property string $title_uz
 * @property string $title_ru
 * @property integer $sort
 * @property string|null $created_at
 * @property boolean $enabled
 * @property-read QuestionAnswer $questionAnswer
 */
class Question extends Model
{
    use HasFactory;

    protected $table = 'questions';

    protected $fillable = [
        'title_oz',
        'title_uz',
        'title_ru',
        'sort',
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

    public function getTitleOz(): string
    {
        return $this->title_oz;
    }

    public function setTitleOz(string $title_oz): void
    {
        $this->title_oz = $title_oz;
    }

    public function getTitleUz(): string
    {
        return $this->title_uz;
    }

    public function setTitleUz(string $title_uz): void
    {
        $this->title_uz = $title_uz;
    }

    public function getTitleRu(): string
    {
        return $this->title_ru;
    }

    public function setTitleRu(string $title_ru): void
    {
        $this->title_ru = $title_ru;
    }

    public function getSort(): int
    {
        return $this->sort;
    }

    public function setSort(int $sort): void
    {
        $this->sort = $sort;
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

    public function questionAnswer(): BelongsTo
    {
        return $this->belongsTo(QuestionAnswer::class,'id','question_id');
    }
}
