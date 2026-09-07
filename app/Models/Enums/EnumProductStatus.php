<?php

namespace App\Models\Enums;

use Database\Factories\ProductStatusFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int|null $id
 * @property string $name_oz
 * @property string $name_uz
 * @property string $name_ru
 * @property string $code
 * @property boolean $enabled
 */
class EnumProductStatus extends Model
{
    use HasFactory;

    protected $table = 'enum_product_status';

    protected $fillable = [
        'name_oz',
        'name_uz',
        'name_ru',
        'code',
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
     * @return ProductStatusFactory|Factory
     */
    public static function factory($count = null, $state = []): ProductStatusFactory|Factory
    {
        return ProductStatusFactory::new();
    }
}
