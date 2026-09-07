<?php

namespace App\Models\Enums;

use App\Models\Users\UserInterest;
use Database\Factories\ProductGenreFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * @property int|null $id
 * @property string $name_oz
 * @property string $name_uz
 * @property string $name_ru
 * @property boolean $enabled
 *
 * @property-read UserInterest $interest
 */
class EnumProductGenre extends Model
{
    use HasFactory;
    protected $table = 'enum_product_genres';

    protected $fillable = [
        'name_oz',
        'name_uz',
        'name_ru',
        'enabled',
    ];

    /**
     * @return MorphOne
     */
    public function interest(): MorphOne
    {
        return $this->morphOne(UserInterest::class, 'model');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
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

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): void
    {
        $this->enabled = $enabled;
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
     * @return ProductGenreFactory|Factory
     */
    public static function factory($count = null, $state = []): ProductGenreFactory|Factory
    {
        return ProductGenreFactory::new();
    }
}
