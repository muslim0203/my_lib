<?php

namespace App\Models\Products;

use App\Core\Enums\ProductPriceTypeEnum;
use App\Models\Enums\EnumCategories;
use App\Models\Enums\EnumProductGenre;
use App\Models\Enums\EnumProductStatus;
use App\Models\Enums\EnumProductTag;
use App\Models\Files\File;
use App\Models\Links\LinkProductCategories;
use App\Models\Links\LinkProductGenre;
use App\Models\Links\LinkProductTag;
use App\Models\Links\LinkProductType;
use App\Models\Links\LinkUserProduct;
use App\Models\Steps\ProcessStep;
use App\Models\Users\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property integer|null $id
 * @property string $title_oz
 * @property string $title_uz
 * @property string $title_ru
 * @property string $description_oz
 * @property string $description_uz
 * @property string $description_ru
 * @property integer $author_id
 * @property integer|null $request_id
 * @property integer $status_id
 * @property integer $step_id
 * @property integer|null $parent_id
 * @property integer|null $views_count
 * @property string $size
 * @property integer $wrapper_file_id
 * @property string $wrapper_file_name
 * @property integer $source_file_id
 * @property string $source_file_name
 * @property string $state
 * @property integer|null $price_id
 * @property integer $price_type_id
 * @property integer $confirm_author_id
 * @property string $last_updated_date
 * @property string $create_date
 * @property string $extra_authors
 * @property string $price_value
 * @property integer $discount_id
 * @property integer|null $licence_file_id
 * @property string|null $licence_date
 * @property string|null $licence_code
 * @property string|null $amount_given_to_seller
 * @property boolean $is_deleted
 * @property boolean $has_audio_file
 * @property boolean $is_download
 *
 * @property-read EnumProductStatus|null $status
 * @property-read ProcessStep|null $step
 * @property-read self|null $parent
 * @property-read File $wrapperFile
 * @property-read File $sourceFile
 * @property-read ProductPrice|null $price
 * @property-read ProductPriceType|null $priceType
 * @property-read User|null $author
 * @property-read User|null $confirmAuthor
 * @property-read ProductDiscount|null $discount
 * @property-read ProductAssessment[]|Collection $assessments
 * @property-read LinkProductType[] $productType
 * @property-read LinkProductGenre[] $productGenre
 * @property-read LinkUserProduct $userProduct
 */
class Product extends Model
{
    /**
     * @return void
     */
    protected static function boot(): void
    {
        parent::boot();

        self::creating(function ($model) {
            $title = !empty($model->title_oz)
                ? $model->title_oz
                : (!empty($model->title_ru) ? $model->title_ru : (!empty($model->title_uz) ? $model->title_uz : ''));

            $description = !empty($model->description_oz)
                ? $model->description_oz
                : (!empty($model->description_ru) ? $model->description_ru : (!empty($model->description_uz) ? $model->description_uz : ''));


            if (empty($model->title_oz)) {
                $model->title_oz = $title;
            }

            if (empty($model->title_uz)) {
                $model->title_uz = $title;
            }

            if (empty($model->title_ru)) {
                $model->title_ru = $title;
            }

            if (empty($model->description_oz)) {
                $model->description_oz = $description;
            }

            if (empty($model->description_ru)) {
                $model->description_ru = $description;
            }

            if (empty($model->description_uz)) {
                $model->description_uz = $description;
            }
        });
    }

    protected $fillable = [
        'is_download',
        'request_id',
        'title_oz',
        'title_uz',
        'title_ru',
        'description_oz',
        'description_uz',
        'description_ru',
        'author_id',
        'status_id',
        'step_id',
        'parent_id',
        'size',
        'wrapper_file_id',
        'wrapper_file_name',
        'source_file_id',
        'source_file_name',
        'state',
        'price_id',
        'price_type_id',
        'confirm_author_id',
        'last_updated_date',
        'create_date',
        'extra_authors',
        'price_value',
        'discount_id',
        'views_count',
        'is_deleted',
        'has_audio_file',
        'licence_file_id',
        'licence_date',
        'licence_code',
        'amount_given_to_seller'
    ];

    public function isIsDownload(): bool
    {
        return $this->is_download;
    }

    public function setIsDownload(bool $is_download): void
    {
        $this->is_download = $is_download;
    }

    public function getLicenceCode(): ?string
    {
        return $this->licence_code;
    }

    public function setLicenceCode(?string $licence_code): void
    {
        $this->licence_code = $licence_code;
    }

    public function getLicenceFileId(): ?int
    {
        return $this->licence_file_id;
    }

    public function setLicenceFileId(?int $licence_file_id): void
    {
        $this->licence_file_id = $licence_file_id;
    }

    public function getLicenceDate(): ?string
    {
        return $this->licence_date;
    }

    public function setLicenceDate(?string $licence_date): void
    {
        $this->licence_date = $licence_date;
    }


    public function isHasAudioFile(): bool
    {
        return $this->has_audio_file;
    }

    public function setHasAudioFile(bool $has_audio_file): void
    {
        $this->has_audio_file = $has_audio_file;
    }

    public function getRequestId(): ?int
    {
        return $this->request_id;
    }

    public function setRequestId(?int $request_id): void
    {
        $this->request_id = $request_id;
    }

    public function isIsDeleted(): bool
    {
        return $this->is_deleted;
    }

    public function setIsDeleted(bool $is_deleted): void
    {
        $this->is_deleted = $is_deleted;
    }

    public function productGenre(): BelongsToMany
    {
//        return $this->hasMany(LinkProductGenre::class, 'product_id', 'id');
        return $this->belongsToMany(EnumProductGenre::class, 'link_product_genres', 'product_id', 'genre_id');
    }

    public function productCategory(): BelongsToMany
    {
//        return $this->hasMany(LinkProductCategories::class, 'product_id', 'id');
        return $this->belongsToMany(EnumCategories::class, 'link_product_categories', 'product_id', 'category_id');
    }

    /**
     * @return BelongsToMany
     */
    public function productTag(): BelongsToMany
    {
        return $this->belongsToMany(EnumProductTag::class, 'link_product_tags', 'product_id', 'tag_id');
    }

    /**
     * @return HasMany
     */
    public function productType(): HasMany
    {
        return $this->hasMany(LinkProductType::class, 'product_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function assessments(): HasMany
    {
        return $this->hasMany(ProductAssessment::class, 'product_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(EnumProductStatus::class);
    }

    /**
     * @return BelongsTo
     */
    public function step(): BelongsTo
    {
        return $this->belongsTo(ProcessStep::class);
    }

    /**
     * @return BelongsTo
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class);
    }

    /**
     * @return BelongsTo
     */
    public function wrapperFile(): BelongsTo
    {
        return $this->belongsTo(File::class);
    }

    /**
     * @return BelongsTo
     */
    public function sourceFile(): BelongsTo
    {
        return $this->belongsTo(File::class);
    }

    /**
     * @return BelongsTo
     */
    public function price(): BelongsTo
    {
        return $this->belongsTo(ProductPrice::class);
    }

    /**
     * @return BelongsTo
     */
    public function priceType(): BelongsTo
    {
        return $this->belongsTo(ProductPriceType::class);
    }

    /**
     * @return BelongsTo
     */
    public function confirmAuthor(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getTitleOz(): string
    {
        return $this->title_oz;
    }

    public function setTitleOz(string $title_oz): void
    {
        $this->title_oz = $title_oz;
    }

    public function getTitleUz(): string
    {
        return $this->title_uz;
    }

    public function setTitleUz(string $title_uz): void
    {
        $this->title_uz = $title_uz;
    }

    public function getTitleRu(): string
    {
        return $this->title_ru;
    }

    public function setTitleRu(string $title_ru): void
    {
        $this->title_ru = $title_ru;
    }

    public function getDescriptionOz(): string
    {
        return $this->description_oz;
    }

    public function setDescriptionOz(string $description_oz): void
    {
        $this->description_oz = $description_oz;
    }

    public function getDescriptionUz(): string
    {
        return $this->description_uz;
    }

    public function setDescriptionUz(string $description_uz): void
    {
        $this->description_uz = $description_uz;
    }

    public function getDescriptionRu(): string
    {
        return $this->description_ru;
    }

    public function setDescriptionRu(string $description_ru): void
    {
        $this->description_ru = $description_ru;
    }

    public function getAuthorId(): int
    {
        return $this->author_id;
    }

    public function setAuthorId(int $author_id): void
    {
        $this->author_id = $author_id;
    }

    public function getStatusId(): int
    {
        return $this->status_id;
    }

    public function setStatusId(int $status_id): void
    {
        $this->status_id = $status_id;
    }

    public function getStepId(): int
    {
        return $this->step_id;
    }

    public function setStepId(int $step_id): void
    {
        $this->step_id = $step_id;
    }

    public function getParentId(): ?int
    {
        return $this->parent_id;
    }

    public function setParentId(?int $parent_id): void
    {
        $this->parent_id = $parent_id;
    }

    public function getViewsCount(): ?int
    {
        return $this->views_count;
    }

    public function setViewsCount(?int $views_count): void
    {
        $this->views_count = $views_count;
    }

    public function getSize(): string
    {
        return $this->size;
    }

    public function setSize(string $size): void
    {
        $this->size = $size;
    }

    public function getWrapperFileId(): int
    {
        return $this->wrapper_file_id;
    }

    public function setWrapperFileId(int $wrapper_file_id): void
    {
        $this->wrapper_file_id = $wrapper_file_id;
    }

    public function getWrapperFileName(): string
    {
        return $this->wrapper_file_name;
    }

    public function setWrapperFileName(string $wrapper_file_name): void
    {
        $this->wrapper_file_name = $wrapper_file_name;
    }

    public function getSourceFileId(): int
    {
        return $this->source_file_id;
    }

    public function setSourceFileId(int $source_file_id): void
    {
        $this->source_file_id = $source_file_id;
    }

    public function getSourceFileName(): string
    {
        return $this->source_file_name;
    }

    public function setSourceFileName(string $source_file_name): void
    {
        $this->source_file_name = $source_file_name;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function setState(string $state): void
    {
        $this->state = $state;
    }

    public function getPriceId(): ?int
    {
        return $this->price_id;
    }

    public function setPriceId(?int $price_id): void
    {
        $this->price_id = $price_id;
    }

    public function getPriceTypeId(): int
    {
        return $this->price_type_id;
    }

    public function setPriceTypeId(int $price_type_id): void
    {
        $this->price_type_id = $price_type_id;
    }

    public function getConfirmAuthorId(): int
    {
        return $this->confirm_author_id;
    }

    public function setConfirmAuthorId(int $confirm_author_id): void
    {
        $this->confirm_author_id = $confirm_author_id;
    }

    public function getLastUpdatedDate(): ?string
    {
        return $this->last_updated_date;
    }

    public function setLastUpdatedDate(string $last_updated_date): void
    {
        $this->last_updated_date = $last_updated_date;
    }

    public function getAmountGivenToSeller(): ?string
    {
        return $this->amount_given_to_seller;
    }

    public function setAmountGivenToSeller(?string $amount_given_to_seller): void
    {
        $this->amount_given_to_seller = $amount_given_to_seller;
    }

    public function getCreateDate(): ?string
    {
        return $this->create_date;
    }

    public function setCreateDate(string $create_date): void
    {
        $this->create_date = $create_date;
    }

    public function getExtraAuthors(): string
    {
        return $this->extra_authors;
    }

    public function setExtraAuthors(string $extra_authors): void
    {
        $this->extra_authors = $extra_authors;
    }

    public function getPriceValue(bool $isSum = false): ?string
    {
        if ($isSum) {
            return strstr($this->price_value, '.', true);
        }
        return $this->price_value;
    }

    public function setPriceValue(string $price_value): void
    {
        $this->price_value = $price_value;
    }

    /**
     * Mahsulot haqiqatan bepulmi. Bepul egallash va pullik kontentga ruxsat
     * berish qarorlari uchun yagona manba.
     *
     * @return bool
     */
    public function isFree(): bool
    {
        $priceValue = $this->getPriceValue();

        if ($priceValue === null || trim((string) $priceValue) === '') {
            return true;
        }

        if (!is_numeric($priceValue)) {
            return false;
        }

        return (float) $priceValue <= 0.0;
    }

    public function getDiscountId(): ?int
    {
        return $this->discount_id;
    }

    public function setDiscountId(int $discount_id): void
    {
        $this->discount_id = $discount_id;
    }

    /**
     * @return BelongsTo
     */
    public function discount(): BelongsTo
    {
        return $this->belongsTo(ProductDiscount::class);
    }

    public function getCreatedAt(): ?string
    {
        return $this->created_at;
    }

    /**
     * @return BelongsTo
     */
    public function userProduct(): BelongsTo
    {
        return $this->belongsTo(LinkUserProduct::class, 'product_id', 'id');
    }

    /**
     * @return float|int
     */
    public function getDiscountPrice(): float|int
    {
        if (empty($this->getPriceValue())) {
            abort(400, __('client.Price is not found'));
        }

        $discount = $this->discount;

        if (empty($discount)) {
            return intval($this->getPriceValue());
        }

        if (
            !empty($discount->from_expire_at)
            && empty($discount->to_expire_at)
            && ($discount->from_expire_at <= date('Y-m-d'))
        ) {
            return (int)$this->getPriceValue() - ceil(($this->discount->getDiscount() * (int)$this->getPriceValue()) / 100);
        }

        if (
            !empty($discount->to_expire_at)
            && empty($discount->from_expire_at)
            && ($discount->to_expire_at >= date('Y-m-d'))
        ) {
            return (int)$this->getPriceValue() - ceil(($this->discount->getDiscount() * (int)$this->getPriceValue()) / 100);
        }

        if (
            !empty($discount->from_expire_at)
            && !empty($discount->to_expire_at)
            && ($discount->from_expire_at <= date('Y-m-d') && $discount->to_expire_at >= date('Y-m-d'))
        ) {
            return (int)$this->getPriceValue() - ceil(($this->discount->getDiscount() * (int)$this->getPriceValue()) / 100);
        }

        if (
            empty($discount->from_expire_at)
            && empty($discount->to_expire_at)
        ) {
            return (int)$this->getPriceValue() - ceil(($this->discount->getDiscount() * (int)$this->getPriceValue()) / 100);
        }

        return intval($this->getPriceValue());
    }

    /**
     * @return float|int
     */
    public function getPriceMerchant(): float|int
    {
        return $this->getDiscountPrice() * ($this->priceType->getPercentage() / 100);
    }
}
