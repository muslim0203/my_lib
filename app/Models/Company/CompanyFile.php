<?php

namespace App\Models\Company;

use App\Models\Files\File;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int|null $id
 * @property int $company_id
 * @property int $file_id
 * @property string $file_name
 * @property string $title_oz
 * @property string $title_uz
 * @property string $title_ru
 * @property string|null $created_at
 * @property boolean $enabled
 *
 * @property-read Company $company
 * @property-read File $file
 */
class CompanyFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'file_id',
        'file_name',
        'title_oz',
        'title_uz',
        'title_ru',
        'enabled',
    ];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getCompanyId(): int
    {
        return $this->company_id;
    }

    public function setCompanyId(int $company_id): void
    {
        $this->company_id = $company_id;
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

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): void
    {
        $this->enabled = $enabled;
    }

    /**
     * @return BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class);
    }
    public function getCreatedAt(): string
    {
        return $this->created_at;
    }
}
