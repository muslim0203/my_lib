<?php

namespace App\Models\Orders;

use App\Core\Enums\Pay\PaymePaymentStateEnum;
use App\Models\Products\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $product_id
 * @property int $client_id
 * @property string $amount
 * @property string|null $transaction_id
 * @property string|null $transaction_time
 * @property string|null $transaction_no
 * @property string|null $transaction_create_time
 * @property string|null $transaction_perform_time
 * @property string|null $transaction_cancel_time
 * @property string|null $cancel_reason
 * @property string $state
 *
 * @property-read Product $product
 */
class PaymePayment extends Model
{
    protected $fillable = [
        'product_id',
        'client_id',
        'amount',
        'transaction_id',
        'transaction_time',
        'transaction_no',
        'state',
        'transaction_create_time',
        'cancel_reason',
        'transaction_perform_time',
        'transaction_cancel_time',
        'created_at',
        'updated_at'
    ];

    public function getCancelReason(): ?string
    {
        return $this->cancel_reason;
    }

    public function setCancelReason(?string $cancel_reason): void
    {
        $this->cancel_reason = $cancel_reason;
    }

    /**
     * @return bool
     */
    public function isEmptyCreateTime(): bool
    {
        return empty($this->getTransactionCreateTime());
    }

    /**
     * @return bool
     */
    public function isEmptyPerformTime(): bool
    {
        return empty($this->getTransactionPerformTime());
    }

    /**
     * @return bool
     */
    public function isEmptyTransactionId(): bool
    {
        return !empty($this->getTransactionId());
    }

    public function getTransactionCreateTime(): ?string
    {
        return $this->transaction_create_time;
    }

    public function setTransactionCreateTime(?string $transaction_create_time): void
    {
        $this->transaction_create_time = $transaction_create_time;
    }

    public function getTransactionPerformTime(): ?string
    {
        return $this->transaction_perform_time;
    }

    public function setTransactionPerformTime(?string $transaction_perform_time): void
    {
        $this->transaction_perform_time = $transaction_perform_time;
    }

    public function getTransactionCancelTime(): ?string
    {
        return $this->transaction_cancel_time;
    }

    public function setTransactionCancelTime(?string $transaction_cancel_time): void
    {
        $this->transaction_cancel_time = $transaction_cancel_time;
    }

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

    public function getClientId(): int
    {
        return $this->client_id;
    }

    public function setClientId(int $client_id): void
    {
        $this->client_id = $client_id;
    }

    public function getAmount(): string
    {
        return $this->amount;
    }

    public function setAmount(string $amount): void
    {
        $this->amount = $amount;
    }

    public function getTransactionId(): ?string
    {
        return $this->transaction_id;
    }

    public function setTransactionId(?string $transaction_id): void
    {
        $this->transaction_id = $transaction_id;
    }

    public function getTransactionTime(): ?string
    {
        return $this->transaction_time;
    }

    public function setTransactionTime(?string $transaction_time): void
    {
        $this->transaction_time = $transaction_time;
    }

    public function getTransactionNo(): ?string
    {
        return $this->transaction_no;
    }

    public function setTransactionNo(?string $transaction_no): void
    {
        $this->transaction_no = $transaction_no;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function setState(string $state): void
    {
        $this->state = $state;
    }

    /**
     * @return bool
     */
    public function isFinished(): bool
    {
        return $this->state === PaymePaymentStateEnum::PERFORM_TRANSACTION->value;
    }

    /**
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

}
