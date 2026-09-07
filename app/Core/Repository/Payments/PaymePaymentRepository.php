<?php

namespace App\Core\Repository\Payments;

use App\Models\Orders\PaymePayment;
use Illuminate\Database\Eloquent\Builder;

class PaymePaymentRepository
{
    /**
     * @param int $productId
     * @param int $clientId
     * @return Builder|PaymePayment|null
     */
    public function findByProductIdAndClickId(int $productId, int $clientId): PaymePayment|Builder|null
    {
        return PaymePayment::query()
            ->where('product_id', $productId)
            ->where('client_id', $clientId)
            ->first();
    }

    /**
     * @param int $from
     * @param int $to
     * @return PaymePayment[]
     */
    public function findAllFromAndToCreateTime(int $from, int $to): array
    {
        return PaymePayment::query()
            ->whereBetween('transaction_create_time', [$from, $to])
            ->get()
            ->all();
    }

    /**
     * @param PaymePayment $order
     * @param array $params
     * @return void
     */
    public function save(PaymePayment $order, array $params = []): void
    {
        if (!$order->save($params)) {
            throw new \RuntimeException(__('client.PaymePayment save error'));
        }
    }

    /**
     * @param string $transactionId
     * @return Builder|PaymePayment
     */
    public function getByTransactionId(string $transactionId): PaymePayment|Builder
    {
        return PaymePayment::query()
            ->where('transaction_id', $transactionId)
            ->firstOrFail();
    }

    /**
     * @param int $id
     * @return bool
     */
    public function checkOrder(int $id): bool
    {
        return PaymePayment::query()
            ->where('id', $id)
            ->exists();
    }

    /**
     * @param int $id
     * @return PaymePayment|null
     */
    public function find(int $id): ?PaymePayment
    {
        return PaymePayment::query()
            ->where('id', $id)
            ->first();
    }

    /**
     * @param int $id
     * @return Builder|PaymePayment
     */
    public function get(int $id): PaymePayment|Builder
    {
        return PaymePayment::query()
            ->where('id', $id)
            ->firstOrFail();
    }
}
