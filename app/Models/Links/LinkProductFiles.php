<?php

namespace App\Models\Links;

use App\Core\Helpers\Traits\HasCompositePrimaryKey;
use App\Models\Files\File;
use App\Models\Products\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $product_id
 * @property int $file_id
 * @property boolean $enabled
 *
 * @property-read Product $product
 * @property-read File $file
 */
class LinkProductFiles extends Model
{
    use HasCompositePrimaryKey;

    protected $primaryKey = [
        'product_id',
        'file_id'
    ];
    public $incrementing = false;

    protected $fillable = [
        'product_id',
        'file_id',
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
    public function file(): HasOne
    {
        return $this->hasOne(File::class, 'id', 'file_id');
    }

    public function getProductId(): int
    {
        return $this->product_id;
    }

    public function setProductId(int $product_id): void
    {
        $this->product_id = $product_id;
    }

    public function getFileId(): int
    {
        return $this->file_id;
    }

    public function setFileId(int $file_id): void
    {
        $this->file_id = $file_id;
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
