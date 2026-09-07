<?php

namespace App\Models\Enums;

use App\Core\Helpers\Lang\LanguageHelper;
use Database\Factories\CategoryFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property int|null $parent_id
 * @property string $name_uz
 * @property string $name_oz
 * @property string $name_ru
 * @property boolean $enabled
 * @property boolean $has_extra_column_require
 * @property string $icon
 * @property int|null $sort
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property-read self|null $parent
 */
class EnumCategories extends Model
{
    use HasFactory;

    protected $table = 'enum_categories';

    protected $fillable = [
        'name_oz',
        'name_uz',
        'name_ru',
        'icon',
        'sort',
        'parent_id',
        'enabled',
        'created_by',
        'updated_by',
        'has_extra_column_require'
    ];

    public function isHasExtraColumnRequire(): bool
    {
        return $this->has_extra_column_require;
    }

    public function setHasExtraColumnRequire(bool $has_extra_column_require): void
    {
        $this->has_extra_column_require = $has_extra_column_require;
    }

    public static function create(
        string $name_uz,
        string $name_oz,
        string $name_ru,
        int    $parent_id,
        string $icon,
        int    $sort
    ): EnumCategories
    {
        $object = new self;
        $object->name_uz = $name_uz;
        $object->name_oz = $name_oz;
        $object->name_ru = $name_ru;
        $object->icon = $icon;
        $object->sort = $sort;
        $object->parent_id = $parent_id;
        return $object;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getParentId()
    {
        return $this->parent_id;
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(EnumCategories::class);
    }

    public function setParentId(?int $parent_id): void
    {
        $this->parent_id = $parent_id;
    }

    public function getNameUz(): string
    {
        return $this->name_uz;
    }

    public function setNameUz(string $name_uz): void
    {
        $this->name_uz = $name_uz;
    }

    public function getNameOz(): string
    {
        return $this->name_oz;
    }

    public function setNameOz(string $name_oz): void
    {
        $this->name_oz = $name_oz;
    }

    public function getNameRu(): string
    {
        return $this->name_ru;
    }

    public function getIcon(): string|null
    {
        return $this->icon;
    }

    public function setNameRu(string $name_ru): void
    {
        $this->name_ru = $name_ru;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): void
    {
        $this->enabled = $enabled;
    }

    public function getCreatedBy(): ?int
    {
        return $this->created_by;
    }

    public function setCreatedBy(?int $created_by): void
    {
        $this->created_by = $created_by;
    }

    public function getUpdatedBy(): ?int
    {
        return $this->updated_by;
    }

    public function setUpdatedBy(?int $updated_by): void
    {
        $this->updated_by = $updated_by;
    }

    public function getCreatedAt(): ?string
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): ?string
    {
        return $this->updated_at;
    }

    /**
     * @param $count
     * @param $state
     * @return CategoryFactory|Factory
     */
    public static function factory($count = null, $state = []): CategoryFactory|Factory
    {
        return CategoryFactory::new();
    }
}
