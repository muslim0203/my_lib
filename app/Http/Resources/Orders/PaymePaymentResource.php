<?php

namespace App\Http\Resources\Orders;

use App\Models\Orders\PaymePayment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin PaymePayment
 */
class PaymePaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->getTransactionId(),
            'time' => intval($this->getTransactionTime()),
            'amount' => intval($this->getAmount()),
            'account' => [
                'order_id' => $this->getId()
            ],
            'create_time' => intval($this->getTransactionCreateTime()),
            'perform_time' => intval($this->getTransactionPerformTime()),
            'cancel_time' => intval($this->getTransactionCancelTime()),
            'transaction' => $this->getTransactionNo(),
            'state' => intval($this->getState()),
            'reason' => $this->getCancelReason() === null ? null : intval($this->getCancelReason()),
            'receivers' => null
        ];
    }
}
