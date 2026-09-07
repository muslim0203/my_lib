<?php

namespace App\Core\Repository\Product;

use App\Core\Enums\Pay\PaymentTypeEnum;
use App\Models\Products\Product;
use App\Models\Products\ProductsOrder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class ProductsOrderRepository
{
    /**
     * @param ProductsOrder $productsOrder
     * @param array $params
     * @return void
     */
    public function save(ProductsOrder $productsOrder, array $params = []): void
    {
        if (!$productsOrder->save($params)) {
            throw new \RuntimeException(__('client.Saving error!'));
        }
    }

    /**
     * @param int $product_id
     * @param int $user_id
     * @return bool
     */
    public function existsProductOrder(int $product_id, int $user_id): bool
    {
        return ProductsOrder::query()->where('product_id', $product_id)
            ->where('customer_id', $user_id)
            ->exists();
    }

    /**
     * Foydalanuvchining mahsulot kontentiga haqiqiy huquqi bormi.
     *
     * Order mavjudligining o'zi yetarli emas: pullik mahsulot uchun order
     * to'lov tizimi (payme/click) orqali yaratilgan bo'lishi shart. Bu
     * eskirgan yoki noto'g'ri yaratilgan `free` orderlarni ham bloklaydi.
     *
     * @param Product $product
     * @param int $user_id
     * @return bool
     */
    public function hasEntitlement(Product $product, int $user_id): bool
    {
        $query = ProductsOrder::query()
            ->where('product_id', $product->getId())
            ->where('customer_id', $user_id)
            ->where('enabled', true);

        if (!$product->isFree()) {
            $query->whereIn('payment_type', [
                PaymentTypeEnum::TYPE_PAYME->value,
                PaymentTypeEnum::TYPE_CLICK->value,
            ]);
        }

        return $query->exists();
    }

    /**
     * @param int $customer_id
     * @return LengthAwarePaginator
     */
    public function findAllByCustomerId(int $customer_id)
    {
        return ProductsOrder::query()->where('customer_id', $customer_id)->where('enabled', true)
            ->paginate();
    }
    public function totalSum(int $author_id)
    {
        return ProductsOrder::query()
            ->selectRaw("author_id,
       sum(merchant_money_amount) filter ( where status = 'new' )       as new_sum,
       sum(merchant_money_amount) filter ( where status = 'process' )   as process_sum,
       sum(merchant_money_amount) filter ( where status = 'processed' ) as processed_sum")
            ->where('author_id', $author_id)
            ->where('enabled', true)
            ->whereNot('payment_type', PaymentTypeEnum::TYPE_FREE->value)
            ->groupBy('author_id')
            ->first();
    }
}
