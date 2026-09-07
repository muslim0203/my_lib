<?php

namespace App\Core\Repository\Payments;

use App\Models\Orders\ClickPayment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ClickPaymentRepository
{
    /**
     * @param ClickPayment $ordersClick
     * @param array $params
     * @return void
     */
    public function save(ClickPayment $ordersClick, array $params = []): void
    {
        if (!$ordersClick->save($params)) {
            throw new \RuntimeException(__('client.Save error'));
        }
    }

    /**
     * @param int $clickTransId
     * @return Builder|ClickPayment|null
     */
    public function findByClickTransId(int $clickTransId): ClickPayment|Builder|null
    {
        return ClickPayment::query()
            ->where('click_trans_id', $clickTransId)
            ->first();
    }

    /**
     * @param int $id
     * @return Builder|ClickPayment|null
     */
    public function findById(int $id): ClickPayment|Builder|null
    {
        return ClickPayment::query()
            ->where('id', $id)
            ->first();
    }

    /**
     * @param int $userId
     * @param int $productId
     * @return ClickPayment|Builder|null
     */
    public function findByUserIdAndProductId(int $userId, int $productId): ClickPayment|Builder|null
    {
        return ClickPayment::query()
            ->where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();
    }
}
