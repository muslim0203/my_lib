<?php

namespace App\Models\Company;

use App\Models\Files\File;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int|null $id
 * @property int $company_id
 * @property string $name_oz
 * @property string $name_uz
 * @property string $name_ru
 * @property string $website_link
 * @property integer $logo_id
 * @property string|null $created_at
 * @property boolean $enabled
 *
 * @property-read Company $company
 * @property-read File $logo
 */
class CompanyPartner extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'name_oz',
        'name_uz',
        'name_ru',
        'website_link',
        'logo_id',
        'enabled'
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

    public function getNameOz(): string
    {
        return $this->name_oz;
    }

    public function setNameOz(string $name_oz): void
    {
        $this->name_oz = $name_oz;
    }

    public function getNameUz(): string
    {
        return $this->name_uz;
    }

    public function setNameUz(string $name_uz): void
    {
        $this->name_uz = $name_uz;
    }

    public function getNameRu(): string
    {
        return $this->name_ru;
    }

    public function setNameRu(string $name_ru): void
    {
        $this->name_ru = $name_ru;
    }

    public function getWebsiteLink(): string
    {
        return $this->website_link;
    }

    public function setWebsiteLink(string $website_link): void
    {
        $this->website_link = $website_link;
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
    public function logo(): BelongsTo
    {
        return $this->belongsTo(File::class);
    }
    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    public function getLogoId(): int
    {
        return $this->logo_id;
    }

    public function setLogoId(int $logo_id): void
    {
        $this->logo_id = $logo_id;
    }
}
