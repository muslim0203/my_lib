<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int|null $id
 * @property string $title_oz
 * @property string $title_uz
 * @property string $title_ru
 * @property string $content_oz
 * @property string $content_uz
 * @property string $content_ru
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $created_at
 * @property boolean $enabled
 * @property-read CompanyFile $companyFile
 */

class Company extends Model
{
    use HasFactory;

    protected $table = 'company';

    protected $fillable = [
        'title_oz',
        'title_uz',
        'title_ru',
        'content_oz',
        'content_uz',
        'content_ru',
        'email',
        'phone',
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

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
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

    public function companyFile(): BelongsTo
    {
        return $this->belongsTo(CompanyFile::class, 'id', 'company_id');
    }
}
