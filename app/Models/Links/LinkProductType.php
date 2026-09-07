<?php

namespace App\Models\Links;

use App\Core\Helpers\Traits\HasCompositePrimaryKey;
use App\Models\Enums\EnumProductType;
use App\Models\Products\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $product_id
 * @property int $type_id
 * @property boolean $enabled
 *
 * @property-read Product $product
 * @property-read EnumProductType $type
 */
class LinkProductType extends Model
{
    use HasCompositePrimaryKey;

    protected $primaryKey = [
        'product_id',
        'type_id'
    ];
    public $incrementing = false;

    protected $fillable = [
        'product_id',
        'type_id',
        'enabled'
    ];

    /**
     * @return HasOne
     */
    public function product(): HasOne
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    /**
     * @return HasOne
     */
    public function type(): HasOne
    {
        return $this->hasOne(EnumProductType::class, 'id', 'type_id');
    }

    public function getProductId(): int
    {
        return $this->product_id;
    }

    public function setProductId(int $product_id): void
    {
        $this->product_id = $product_id;
    }

    public function getTypeId(): int
    {
        return $this->type_id;
    }

    public function setTypeId(int $type_id): void
    {
        $this->type_id = $type_id;
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
