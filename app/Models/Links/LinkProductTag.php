<?php

namespace App\Models\Links;

use App\Core\Helpers\ModelTraits\HasCompositePrimaryKey;
use App\Models\Enums\EnumProductTag;
use App\Models\Products\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int|null $id
 * @property int $product_id
 * @property int $tag_id
 * @property boolean $enabled
 *
 * @property-read Product $product
 * @property-read EnumProductTag $tag
 */
class LinkProductTag extends Model
{
    use HasCompositePrimaryKey;

    /**
     * Kompozit birlamchi kalit (HasCompositePrimaryKey trait).
     *
     * @var array<int, string>
     */
    protected $primaryKey = ['product_id', 'tag_id'];
    public $incrementing = false;

    protected $fillable = [
        'product_id',
        'tag_id',
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
    public function tag(): BelongsTo
    {
        return $this->belongsTo(EnumProductTag::class);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getProductId(): int
    {
        return $this->product_id;
    }

    public function setProductId(int $product_id): void
    {
        $this->product_id = $product_id;
    }

    public function getTagId(): int
    {
        return $this->tag_id;
    }

    public function setTagId(int $tag_id): void
    {
        $this->tag_id = $tag_id;
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
