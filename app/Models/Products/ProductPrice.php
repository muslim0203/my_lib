<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $product_id
 * @property int $price
 * @property int $percentage
 * @property boolean $enabled
 *
 * @property-read Product $product
 */
class ProductPrice extends Model
{
    protected $fillable = [
        'product_id',
        'price',
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

    /**
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getProductId(): int
    {
        return $this->product_id;
    }

    public function setProductId(int $product_id): void
    {
        $this->product_id = $product_id;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function setPrice(int $price): void
    {
        $this->price = $price;
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
