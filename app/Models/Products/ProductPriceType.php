<?php

namespace App\Models\Products;

use Database\Factories\ProductPriceTypeFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int|null $id
 * @property string $name_oz
 * @property string $name_uz
 * @property string $name_ru
 * @property string $content_oz
 * @property string $content_uz
 * @property string $content_ru
 * @property boolean $enabled
 * @property int $percentage
 */
class ProductPriceType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_oz',
        'name_uz',
        'name_ru',
        'content_oz',
        'content_uz',
        'content_ru',
        'enabled',
        'percentage'
    ];

    public function getPercentage(): int
    {
        return $this->percentage;
    }

    public function setPercentage(int $percentage): void
    {
        $this->percentage = $percentage;
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

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): void
    {
        $this->enabled = $enabled;
    }

    /**
     * @param $count
     * @param $state
     * @return ProductPriceTypeFactory|Factory
     */
    public static function factory($count = null, $state = []): ProductPriceTypeFactory|Factory
    {
        return ProductPriceTypeFactory::new();
    }
}
