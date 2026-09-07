<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int|null $id
 * @property int $product_id
 * @property int $discount
 * @property string $from_expire_at
 * @property string $to_expire_at
 * @property boolean $enabled
 *
 * @property-read Product $product
 */
class ProductDiscount extends Model
{
    protected $fillable = [
        'product_id',
        'discount',
        'from_expire_at',
        'to_expire_at',
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

    public function getDiscount(): int
    {
        return $this->discount;
    }

    public function setDiscount(int $discount): void
    {
        $this->discount = $discount;
    }

    public function getFromExpireAt(): string
    {
        return $this->from_expire_at;
    }

    public function setFromExpireAt(string $from_expire_at): void
    {
        $this->from_expire_at = $from_expire_at;
    }

    public function getToExpireAt(): string
    {
        return $this->to_expire_at;
    }

    public function setToExpireAt(string $to_expire_at): void
    {
        $this->to_expire_at = $to_expire_at;
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
