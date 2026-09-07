<?php

namespace App\Models\Links;

use App\Core\Helpers\ModelTraits\HasCompositePrimaryKey;
use App\Models\Enums\EnumCategories;
use App\Models\Products\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $product_id
 * @property int $category_id
 * @property boolean $enabled
 *
 * @property-read Product $product
 * @property-read EnumCategories $category
 */
class LinkProductCategories extends Model
{
    use HasCompositePrimaryKey;

    protected $table = 'link_product_categories';

    /**
     * Kompozit birlamchi kalit (HasCompositePrimaryKey trait).
     *
     * @var array<int, string>
     */
    protected $primaryKey = ['product_id', 'category_id'];
    public $incrementing = false;

    protected $fillable = [
        'product_id',
        'category_id',
        'enabled'
    ];

    /**
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(EnumCategories::class);
    }

    public function getProductId(): int
    {
        return $this->product_id;
    }

    public function setProductId(int $product_id): void
    {
        $this->product_id = $product_id;
    }

    public function getCategoryId(): int
    {
        return $this->category_id;
    }

    public function setCategoryId(int $category_id): void
    {
        $this->category_id = $category_id;
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
