<?php

namespace App\Models\Links;

use App\Core\Helpers\Traits\HasCompositePrimaryKey;
use App\Models\Products\Product;
use App\Models\Users\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $user_id
 * @property int $product_id
 * @property boolean $enabled
 *
 * @property-read User $user
 * @property-read Product $product
 */
class LinkUserProduct extends Model
{
    use HasCompositePrimaryKey;

    protected $primaryKey = ['user_id', 'product_id'];
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'product_id',
        'enabled'
    ];

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): void
    {
        $this->user_id = $user_id;
    }

    public function getProductId(): int
    {
        return $this->product_id;
    }

    public function setProductId(int $product_id): void
    {
        $this->product_id = $product_id;
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
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
