<?php

namespace App\Models\Orders;

use App\Models\Products\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int|null $click_trans_id
 * @property int $service_id
 * @property int|null $click_paydoc_id
 * @property int $product_id
 * @property int $user_id
 * @property string $amount
 * @property int|null $action
 * @property int|null $error
 * @property string|null $error_note
 * @property string|null $sign_time
 * @property string|null $sign_string
 * @property int|null $merchant_confirm_id
 *
 * @property-read Product $product
 */
class ClickPayment extends Model
{
    protected $fillable = [
        'click_trans_id',
        'service_id',
        'click_paydoc_id',
        'product_id',
        'user_id',
        'amount',
        'action',
        'error',
        'error_note',
        'sign_time',
        'sign_string',
        'merchant_confirm_id'
    ];

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getClickTransId(): ?int
    {
        return $this->click_trans_id;
    }

    public function setClickTransId(?int $click_trans_id): void
    {
        $this->click_trans_id = $click_trans_id;
    }

    public function getServiceId(): int
    {
        return $this->service_id;
    }

    public function setServiceId(int $service_id): void
    {
        $this->service_id = $service_id;
    }

    public function getClickPaydocId(): ?int
    {
        return $this->click_paydoc_id;
    }

    public function setClickPaydocId(?int $click_paydoc_id): void
    {
        $this->click_paydoc_id = $click_paydoc_id;
    }

    public function getProductId(): int
    {
        return $this->product_id;
    }

    public function setProductId(int $product_id): void
    {
        $this->product_id = $product_id;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): void
    {
        $this->user_id = $user_id;
    }

    public function getAmount(): string
    {
        return $this->amount;
    }

    public function setAmount(string $amount): void
    {
        $this->amount = $amount;
    }

    public function getAction(): ?int
    {
        return $this->action;
    }

    public function setAction(?int $action): void
    {
        $this->action = $action;
    }

    public function getError(): ?int
    {
        return $this->error;
    }

    public function setError(?int $error): void
    {
        $this->error = $error;
    }

    public function getErrorNote(): ?string
    {
        return $this->error_note;
    }

    public function setErrorNotice(?string $error_note): void
    {
        $this->error_note = $error_note;
    }

    public function getSignTime(): ?string
    {
        return $this->sign_time;
    }

    public function setSignTime(?string $sign_time): void
    {
        $this->sign_time = $sign_time;
    }

    public function getSignString(): ?string
    {
        return $this->sign_string;
    }

    public function setSignString(?string $sign_string): void
    {
        $this->sign_string = $sign_string;
    }

    public function getMerchantConfirmId(): ?int
    {
        return $this->merchant_confirm_id;
    }

    public function setMerchantConfirmId(?int $merchant_confirm_id): void
    {
        $this->merchant_confirm_id = $merchant_confirm_id;
    }

    /**
     * @return bool
     */
    public function isConfirmPay(): bool
    {
        return !empty($this->merchant_confirm_id);
    }

    /**
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
