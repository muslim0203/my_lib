<?php

namespace App\Models\MainBanner;

use App\Core\Helpers\Traits\HasCompositePrimaryKey;
use App\Models\Files\File;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

/**
 * App\Core\Entities\MainBanner
 *
 * @property int $id
 * @property int $main_banner_id
 * @property int $file_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read File $file
 * **/
class MainBannerFiles extends Model
{
    use HasCompositePrimaryKey;

    protected $table = 'main_banner_files';
    protected $fillable = [
        'main_banner_id',
        'file_id',
    ];
    /**
     * @return Builder
     */
    public static function query(): Builder
    {
        return parent::query();
    }

    public function mainBanner(): HasMany
    {
        return $this->hasMany(MainBanner::class,'main_banner_id');
    }

    public function file(): HasOne
    {
        return $this->hasOne(File::class, 'id', 'file_id');
    }

    public function setBannerId(int $banner_id): void
    {
        $this->main_banner_id = $banner_id;
    }

    public function setFileId(int $file_id): void
    {
        $this->file_id = $file_id;
    }
    public function getId(): int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?string
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): ?string
    {
        return $this->updated_at;
    }

}


