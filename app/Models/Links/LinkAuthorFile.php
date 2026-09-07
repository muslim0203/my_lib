<?php

namespace App\Models\Links;

use App\Core\Helpers\ModelTraits\HasCompositePrimaryKey;
use App\Models\Authors\Author;
use App\Models\Enums\EnumFileType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $author_id
 * @property int $file_id
 * @property int $file_type_id
 * @property string $file_name
 * @property boolean $enabled
 */
class LinkAuthorFile extends Model
{
    use HasCompositePrimaryKey;
    protected $primaryKey = ['author_id', 'file_id'];
    public $incrementing = false;

    protected $fillable = [
        'author_id',
        'file_id',
        'file_type_id',
        'file_name',
        'enabled'
    ];

    public function getAuthorId(): int
    {
        return $this->author_id;
    }

    public function setAuthorId(int $author_id): void
    {
        $this->author_id = $author_id;
    }

    public function getFileId(): int
    {
        return $this->file_id;
    }

    public function setFileId(int $file_id): void
    {
        $this->file_id = $file_id;
    }

    public function getFileName(): string
    {
        return $this->file_name;
    }

    public function setFileName(string $file_name): void
    {
        $this->file_name = $file_name;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): void
    {
        $this->enabled = $enabled;
    }

    public function getFileTypeId(): int
    {
        return $this->file_type_id;
    }

    public function setFileTypeId(int $file_type_id): void
    {
        $this->file_type_id = $file_type_id;
    }

    /**
     * @return BelongsTo
     */
    public function fileType(): BelongsTo
    {
        return $this->belongsTo(EnumFileType::class);
    }

    /**
     * @return BelongsTo
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }
}
