<?php

namespace App\Models\Links;

use App\Core\Helpers\ModelTraits\HasCompositePrimaryKey;
use App\Models\Enums\EnumProductGenre;
use App\Models\Products\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int|null $id
 * @property int $product_id
 * @property int $genre_id
 * @property boolean $enabled
 *
 * @property-read Product $product
 * @property-read EnumProductGenre $genre
 */
class LinkProductGenre extends Model
{
    use HasCompositePrimaryKey;

    protected $primaryKey = ['product_id', 'genre_id'];
    public $incrementing = false;

    protected $fillable = [
        'product_id',
        'genre_id',
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
    public function genre(): BelongsTo
    {
        return $this->belongsTo(EnumProductGenre::class);
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

    public function getGenreId(): int
    {
        return $this->genre_id;
    }

    public function setGenreId(int $genre_id): void
    {
        $this->genre_id = $genre_id;
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
