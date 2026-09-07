<?php

namespace App\Models\Authority;

use App\Models\Files\File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int|null $id
 * @property int $authority_id
 * @property int $file_id
 * @property string $file_name
 * @property boolean $enabled
 *
 * @property-read File $file
 * @property-read Authority $authority
 */
class AuthorityProfileFile extends Model
{
    protected $fillable = [
        'authority_id',
        'file_id',
        'file_name',
        'enabled'
    ];

    /**
     * @return BelongsTo
     */
    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getAuthorityId(): int
    {
        return $this->authority_id;
    }

    public function setAuthorityId(int $authority_id): void
    {
        $this->authority_id = $authority_id;
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
}
