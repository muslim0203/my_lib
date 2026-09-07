<?php

namespace App\Core\Services\Pay;

use App\Core\Enums\Pay\ClickActionCodeEnum;
use App\Core\Enums\Pay\ClickErrorCodeEnum;
use App\Core\Enums\Pay\PaymentTypeEnum;
use App\Core\Repository\Payments\ClickPaymentRepository;
use App\Core\Repository\Product\ProductRepository;
use App\Core\Repository\Product\ProductsOrderRepository;
use App\Core\Services\Pay\Contracts\ClickContract;
use App\Core\Services\Pay\Helpers\ClickResult;
use App\Core\Services\Product\ProductsOrderService;
use App\Http\Requests\Payment\ClickRedirectUrlFormRequest;
use App\Http\Requests\Payment\ClickTransferPaymentCompleteFormRequest;
use App\Http\Requests\Payment\ClickTransferPaymentPrepareFormRequest;
use App\Models\Orders\ClickPayment;
use App\Models\Users\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ClickService implements ClickContract
{

    /**
     * @param ClickRedirectUrlFormRequest $clickRedirectUrlFormRequest
     * @return string
     */
    public function getRedirectUrl(ClickRedirectUrlFormRequest $clickRedirectUrlFormRequest): string
    {
        /**
         * @var User $user
         */
        $user = Auth::user();

        /**
         * @var ProductsOrderRepository $productsOrderRepository
         */
        $productsOrderRepository = app(ProductsOrderRepository::class);
        if ($productsOrderRepository->existsProductOrder(intval($clickRedirectUrlFormRequest->post('product_id')), $user->getId())) {
            abort(400, __('client.Already exists order'));
        }

        /**
         * @var ClickPaymentRepository $clickPaymentRepository
         */
        $clickPaymentRepository = app(ClickPaymentRepository::class);

        /**
         * @var ProductRepository $productRepository
         */
        $productRepository = app(ProductRepository::class);
        $product = $productRepository->getById(intval($clickRedirectUrlFormRequest->post('product_id')));
        $price = $product->getDiscountPrice();

        if (empty($price) || empty($product->getPriceValue())) {
            abort(400, __('client.Product price is not defined'));
        }

        $clickPayment = $clickPaymentRepository->findByUserIdAndProductId($user->id, $product->getId());

        if (empty($clickPayment)) {
            $clickPayment = new ClickPayment();
            $clickPayment->setServiceId(config('click.service_id'));
            $clickPayment->setProductId($product->getId());
            $clickPayment->setUserId($user->getId());
            $clickPayment->setAmount((string)$price);
        } elseif (!$clickPayment->isConfirmPay()) {
            $clickPayment->setServiceId(config('click.service_id'));
            $clickPayment->setProductId($product->getId());
            $clickPayment->setUserId($user->getId());
            $clickPayment->setAmount((string)$price);
        } else {
            abort(403, __('client.Payment already confirmed'));
        }


        $clickPaymentRepository->save($clickPayment);

        return config('click.base_url') . http_build_query([
                'service_id' => config('click.service_id'),
                'merchant_id' => config('click.merchant_id'),
                'amount' => $clickPayment->getAmount(),
                'transaction_param' => $clickPayment->getId()
            ]);
    }


    /**
     * @param ClickTransferPaymentPrepareFormRequest $clickTransferPaymentPrepareFormRequest
     * @return ClickResult
     */
    public function preparePayment(ClickTransferPaymentPrepareFormRequest $clickTransferPaymentPrepareFormRequest): ClickResult
    {
        $params = $clickTransferPaymentPrepareFormRequest->validated();
        Log::info('Click preparePayment', self::redact($clickTransferPaymentPrepareFormRequest->validated()));

        /**
         * @var ClickPaymentRepository $clickPaymentRepository
         */
        $clickPaymentRepository = app(ClickPaymentRepository::class);

        $clickPayment = $clickPaymentRepository->findById(intval($params['merchant_trans_id']));

        if (empty($clickPayment)) {
            return ClickResult::userNotFound();
        }

        if (intval($clickPayment->getAmount()) !== intval($params['amount'])) {
            return ClickResult::incorrectAmount();
        }

        if ($clickPayment->getClickTransId() === intval($params['click_trans_id'])) {
            return ClickResult::alreadyPaid();
        }

        $serviceId = (string)config('click.service_id');

        // Sozlanmagan holatda intval(null) === 0 bo'lardi va service_id=0
        // yuborgan so'rov bu tekshiruvdan o'tib ketardi.
        if ($serviceId === '' || intval($serviceId) !== intval($params['service_id'])) {
            return ClickResult::transactionCancelled();
        }

        if (!$this->isMatchSignKey($params)) {
            return ClickResult::signInFailed();
        }

        if (!empty($clickPayment->getAction())) {
            return ClickResult::actionNotFound();
        }

        $clickPayment->setClickTransId(intval($params['click_trans_id']));
        $clickPayment->setClickPaydocId(intval($params['click_paydoc_id']));
        $clickPayment->setAction($params['action']);
        $clickPayment->setError($params['error']);
        $clickPayment->setErrorNotice($params['error_note']);
        $clickPayment->setSignTime($params['sign_time']);
        $clickPayment->setSignString($params['sign_string']);
        $clickPaymentRepository->save($clickPayment);

        $product = $clickPayment->product;

        /**
         * @var ProductsOrderService $productsOrderService
         */
        $productsOrderService = app(ProductsOrderService::class);
        $productsOrderService->create(
            $clickPayment->getProductId(),
            PaymentTypeEnum::TYPE_CLICK->value,
            (string)$clickPayment->getClickTransId(),
            $clickPayment->getUserId(),
            $clickPayment->getAmount(),
            (string)$product->getPriceMerchant(),
            $product->priceType->getPercentage()
        );

        return new ClickResult(
            error: ClickErrorCodeEnum::CODE_SUCCESS->value,
            clickTransId: $clickPayment->getClickTransId(),
            merchantTransId: $clickPayment->getId(),
            merchantPrepareId: $clickPayment->getId()
        );
    }

    /**
     * @param ClickTransferPaymentCompleteFormRequest $clickTransferPaymentCompleteFormRequest
     * @return ClickResult
     */
    public function completePayment(ClickTransferPaymentCompleteFormRequest $clickTransferPaymentCompleteFormRequest): ClickResult
    {
        $params = $clickTransferPaymentCompleteFormRequest->validated();
        Log::info('Click completePayment', self::redact($params));

        /**
         * @var ClickPaymentRepository $clickPaymentRepository
         */
        $clickPaymentRepository = app(ClickPaymentRepository::class);
        $clickPayment = $clickPaymentRepository->findById(intval($params['merchant_trans_id']));

        if (empty($clickPayment)) {
            return ClickResult::userNotFound();
        }

        if ($clickPayment->getClickTransId() !== intval($params['click_trans_id'])) {
            return ClickResult::paymentNotFound();
        }

        if ($clickPayment->getAction() !== ClickActionCodeEnum::CODE_PREPARE->value) {
            return ClickResult::actionNotFound();
        }

        if ($clickPayment->getId() !== intval($params['merchant_trans_id'])) {
            return ClickResult::paymentNotFound();
        }

        if ($clickPayment->getId() !== intval($params['merchant_prepare_id'])) {
            return ClickResult::paymentNotFound();
        }

        if (!$this->isMatchSignKey($params)) {
            return ClickResult::signInFailed();
        }

        $clickPayment->setAction(intval($params['action']));
        $clickPayment->setError(intval($params['error']));
        $clickPayment->setSignTime($params['sign_time']);
        $clickPayment->setSignString($params['sign_string']);
        $clickPaymentRepository->save($clickPayment);

        return new ClickResult(
            error: ClickErrorCodeEnum::CODE_SUCCESS->value,
            clickTransId: $clickPayment->getClickTransId(),
            merchantTransId: $clickPayment->getId(),
            merchantConfirmId: $clickPayment->getId()
        );
    }

    /**
     * @param array $params
     * @return bool
     */
    /**
     * Logga yozishdan oldin imzoni olib tashlaydi.
     *
     * `sign_string` sirdan hosil qilingan qiymat, shuning uchun u
     * loglarda saqlanmaydi.
     *
     * @param array $params
     * @return array
     */
    protected static function redact(array $params): array
    {
        if (array_key_exists('sign_string', $params)) {
            $params['sign_string'] = '[redacted]';
        }

        return $params;
    }

    protected function isMatchSignKey(array $params): bool
    {
        $secretKey = (string)config('click.secret_key');

        // Sozlanmagan integratsiya hech qachon "to'g'ri imzo" bermaydi.
        // Bo'sh sir bilan md5 hisoblansa, algoritmni bilgan har kim
        // haqiqiy imzoni o'zi yasay olardi va soxta callback orqali
        // pullik mahsulotni ochib olardi.
        if ($secretKey === '') {
            Log::error('Click: secret_key sozlanmagan, callback rad etildi.');

            return false;
        }

        // MD5 provayder talabi, shuning uchun algoritm o'zgartirilmaydi.
        // Solishtirish esa vaqt bo'yicha xavfsiz bajariladi.
        $expected = md5(join('', [
            $params['click_trans_id'],
            $params['service_id'],
            $secretKey,
            $params['merchant_trans_id'],
            $params['merchant_prepare_id'] ?? '',
            $params['amount'],
            $params['action'],
            $params['sign_time']
        ]));

        return hash_equals($expected, (string)($params['sign_string'] ?? ''));
    }
}
