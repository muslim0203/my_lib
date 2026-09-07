<?php

namespace App\Models\MainBanner;

use App\Core\Helpers\Traits\HasCompositePrimaryKey;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * App\Core\Entities\Docs\Docs
 *
 * @property int $id
 * @property string $name_uz
 * @property string $name_ru
 * @property string $name_oz
 * @property string $content_uz
 * @property string $content_ru
 * @property string $content_oz
 * @property string $link
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int $status
 * @property boolean $enabled
 * @property boolean $is_view_content
 * @property-read MainBannerFiles $mainBannerFile
 */
class MainBanner extends BaseModel
{
    use HasCompositePrimaryKey;

    public array $banner_files = [];

    protected $table = 'main_banners';
    protected $fillable = [
        'name_uz',
        'name_ru',
        'name_oz',
        'content_uz',
        'content_ru',
        'content_oz',
        'link',
        'enabled',
        'is_view_content',
        'created_at',
        'updated_at'
    ];

    /**
     * @return Builder
     */
    public static function query(): Builder
    {
        return parent::query();
    }

    public function mainBannerFile(): HasMany
    {
        return $this->hasMany(MainBannerFiles::class,'main_banner_id','id');
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameUz(): string
    {
        return $this->name_uz;
    }

    public function getNameOz(): string
    {
        return $this->name_oz;
    }
    public function getNameRu(): string
    {
        return $this->name_ru;
    }

    public function getContentUz(): string
    {
        return $this->content_uz;
    }

    public function getContentOz(): string
    {
        return $this->content_oz;
    }
    public function getContentRu(): string
    {
        return $this->content_ru;
    }

    public function getLink(): string
    {
        return $this->link;
    }
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function getCreatedAt(): ?string
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): ?string
    {
        return $this->updated_at;
    }

    public function getIsViewContent(): bool
    {
        return $this->is_view_content;
    }

}

