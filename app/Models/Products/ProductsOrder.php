<?php

namespace App\Models\Products;

use App\Models\Users\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Laravel\Sanctum\Contracts\HasAbilities;

/**
 * @property int $id
 * @property int $product_id
 * @property int $author_id
 * @property int $customer_id
 * @property string $payment_type
 * @property string $order_price Sotuv umumiy narxi
 * @property string $merchant_money_amount Sotuvchi oladigan summa miqdori
 * @property int|null $payment_system_service_percentage To'lov tizimlarini har bir tanzaksiyadan oladigan ulushi %da
 * @property string|null $payment_system_service_money_amount To'lov tizimlarini ulushi pul ko'rinishda
 * @property string $web_service_and_payment_system_money_amount Sayt va tranzaksiya uchun ushlab qolinyotgan umumiy summa
 * @property string|null $web_service_price_percentage Saytni ulushi % da
 * @property string|null $web_service_money_amount Saytni ulushi pul shaklda
 * @property string $transaction_id
 * @property boolean $enabled
 * @property string $status
 *
 * @property-read User $customer
 * @property-read Product $product
 */
class ProductsOrder extends Model
{
    protected $fillable = [
        'product_id',
        'customer_id',
        'payment_type',
        'transaction_id',
        'enabled',
        'author_id',
        'status',
        'order_price',
        'merchant_money_amount',
        'web_service_price_percentage',
        'web_service_money_amount',
        'payment_system_service_percentage',
        'payment_system_service_money_amount',
        'web_service_and_payment_system_money_amount',
    ];

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
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

    public function getPaymentSystemServicePercentage(): ?int
    {
        return $this->payment_system_service_percentage;
    }

    public function setPaymentSystemServicePercentage(?int $payment_system_service_percentage): void
    {
        $this->payment_system_service_percentage = $payment_system_service_percentage;
    }

    public function getPaymentSystemServiceMoneyAmount(): ?string
    {
        return $this->payment_system_service_money_amount;
    }

    public function setPaymentSystemServiceMoneyAmount(?string $payment_system_service_money_amount): void
    {
        $this->payment_system_service_money_amount = $payment_system_service_money_amount;
    }

    public function getAuthorId(): int
    {
        return $this->author_id;
    }

    public function setAuthorId(int $author_id): void
    {
        $this->author_id = $author_id;
    }

    public function getCustomerId(): int
    {
        return $this->customer_id;
    }

    public function setCustomerId(int $customer_id): void
    {
        $this->customer_id = $customer_id;
    }

    public function getPaymentType(): string
    {
        return $this->payment_type;
    }

    public function setPaymentType(string $payment_type): void
    {
        $this->payment_type = $payment_type;
    }

    public function getOrderPrice(): string
    {
        return $this->order_price;
    }

    public function setOrderPrice(string $order_price): void
    {
        $this->order_price = $order_price;
    }

    public function getMerchantMoneyAmount(): string
    {
        return $this->merchant_money_amount;
    }

    public function setMerchantMoneyAmount(string $merchant_money_amount): void
    {
        $this->merchant_money_amount = $merchant_money_amount;
    }

    public function getWebServiceAndPaymentSystemMoneyAmount(): string
    {
        return $this->web_service_and_payment_system_money_amount;
    }

    public function setWebServiceAndPaymentSystemMoneyAmount(string $web_service_and_payment_system_money_amount): void
    {
        $this->web_service_and_payment_system_money_amount = $web_service_and_payment_system_money_amount;
    }

    public function getWebServicePricePercentage(): ?string
    {
        return $this->web_service_price_percentage;
    }

    public function setWebServicePricePercentage(?string $web_service_price_percentage = null): void
    {
        $this->web_service_price_percentage = $web_service_price_percentage;
    }

    public function getWebServiceMoneyAmount(): ?string
    {
        return $this->web_service_money_amount;
    }

    public function setWebServiceMoneyAmount(?string $web_service_money_amount = null): void
    {
        $this->web_service_money_amount = $web_service_money_amount;
    }

    public function getTransactionId(): string
    {
        return $this->transaction_id;
    }

    public function setTransactionId(string $transaction_id): void
    {
        $this->transaction_id = $transaction_id;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): void
    {
        $this->enabled = $enabled;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function product(): HasOne
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    /**
     * @return BelongsTo
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
