<?php

namespace App\Core\Services\Product;

use App\Core\Enums\ProductPriceTypeEnum;
use App\Core\Repository\Product\ProductRepository;
use App\Core\Repository\Product\ProductsOrderRepository;
use App\Models\Products\ProductsOrder;

class ProductsOrderService
{
    public function __construct(
        protected ProductsOrderRepository $productsOrderRepository
    )
    {
    }

    /**
     * @param int $product_id
     * @param string $payment_type
     * @param string $transaction_id
     * @param int $user_id
     * @param string $order_price
     * @param string $merchant_money_amount
     * @param int $web_service_price_percentage
     * @param int|null $payment_system_service_percentage
     * @return void
     */
    public function create(
        int    $product_id,
        string $payment_type,
        string $transaction_id,
        int    $user_id,
        string $order_price,
        string $merchant_money_amount,
        int    $web_service_price_percentage,
        ?int   $payment_system_service_percentage = null
    ): void
    {
        /**
         * @var ProductRepository $productRepository
         */
        $productRepository = app(ProductRepository::class);
        $product = $productRepository->getById($product_id);

        if ($this->productsOrderRepository->existsProductOrder($product_id, $user_id)) {
            abort(400, __('client.Product Order Already Exists'));
        }


        $paymentSystemMoney = null;
        $incomeMoneyAmount = $order_price;
        if (!empty($payment_system_service_percentage)) {
            if ($web_service_price_percentage === ProductPriceTypeEnum::EXPRESS->value) {
                // payment system calculate
                $paymentSystemMoney = ($order_price * ($payment_system_service_percentage)) / 100;
                $incomeMoneyAmount -= $paymentSystemMoney;
            }
        }

        $webServiceMoney = null;
        if (!empty($web_service_price_percentage)) {
            // web service calculate
            $webServiceMoney = ($order_price * ($web_service_price_percentage)) / 100;
            $merchant_money_amount = $incomeMoneyAmount - $webServiceMoney;
        }


        $webServiceAndPaymentSystemMoneyAmount = $order_price - $merchant_money_amount;

        $productsOrder = new ProductsOrder();
        $productsOrder->setOrderPrice($order_price);
        $productsOrder->setProductId($product_id);
        $productsOrder->setPaymentType($payment_type);
        $productsOrder->setTransactionId($transaction_id);
        $productsOrder->setCustomerId($user_id);
        $productsOrder->setAuthorId($product->getAuthorId());
        $productsOrder->setMerchantMoneyAmount($merchant_money_amount);
        $productsOrder->setWebServicePricePercentage($web_service_price_percentage);
        $productsOrder->setWebServiceMoneyAmount($webServiceMoney);
        $productsOrder->setPaymentSystemServicePercentage($payment_system_service_percentage);
        $productsOrder->setPaymentSystemServiceMoneyAmount($paymentSystemMoney);
        $productsOrder->setWebServiceAndPaymentSystemMoneyAmount($webServiceAndPaymentSystemMoneyAmount);
        $this->productsOrderRepository->save($productsOrder);

    }
}
