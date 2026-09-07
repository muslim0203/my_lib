<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int|null $id
 * @property string $content_oz
 * @property string $content_uz
 * @property string $content_ru
 * @property integer $price_type_id
 * @property boolean $enabled
 *
 * @property-read ProductPriceType $priceType
 */
class ProductPriceTypeComment extends Model
{
    protected $fillable = [
        'content_oz',
        'content_uz',
        'content_ru',
        'price_type_id',
        'enabled'
    ];

    /**
     * @return BelongsTo
     */
    public function priceType(): BelongsTo
    {
        return $this->belongsTo(ProductPriceType::class);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
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

    public function getPriceTypeId(): int
    {
        return $this->price_type_id;
    }

    public function setPriceTypeId(int $price_type_id): void
    {
        $this->price_type_id = $price_type_id;
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
