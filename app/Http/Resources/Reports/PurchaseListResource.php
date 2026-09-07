<?php

namespace App\Http\Resources\Reports;

use App\Core\Enums\Pay\PaymentTypeEnum;
use App\Core\Helpers\Lang\LanguageHelper;
use App\Core\Repository\Payments\ClickPaymentRepository;
use App\Core\Repository\Payments\PaymePaymentRepository;
use App\Http\Resources\FileManager\FileViewResource;
use App\Models\Products\ProductsOrder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin ProductsOrder
 */
class PurchaseListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $amount = 0;

        if ($this->getPaymentType() === PaymentTypeEnum::TYPE_CLICK->value) {
            /**
             * @var ClickPaymentRepository $clickPaymentRepository
             */
            $clickPaymentRepository = app(ClickPaymentRepository::class);

            $clickPayment = $clickPaymentRepository->findByClickTransId($this->getTransactionId());

            $amount = $clickPayment->getAmount() ?? 0;
        } else if ($this->getPaymentType() === PaymentTypeEnum::TYPE_PAYME->value) {
            /**
             * @var PaymePaymentRepository $paymePaymentRepository
             */
            $paymePaymentRepository = app(PaymePaymentRepository::class);
            $paymePayment = $paymePaymentRepository->getByTransactionId($this->getTransactionId());

            $amount = $paymePayment->getAmount() ?? 0;
        }

        return [
            'id' => $this->getId(),
            'product_id' => $this->getProductId(),
            'title' => $this->product->{LanguageHelper::getTitle()},
            'payment_type' => $this->getPaymentType(),
            'transaction_id' => $this->getTransactionId(),
            'customer' => $this->customer->socialUser?->getFullName(),
            'price_amount' => $amount,
            'merchant_price_amount' => $this->getMerchantMoneyAmount(),
            'created_at' => date('Y-m-d H:i:s', strtotime($this->created_at)),
            'enabled' => $this->isEnabled() ? __('client.Active') : __('client.Inactive'),
            'merchant_status' => $this->getStatus(),
            'wrapper_file' => new FileViewResource($this->product->wrapperFile)
        ];
    }
}
