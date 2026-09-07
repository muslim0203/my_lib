<?php

namespace App\Core\Services\Pay;

use App\Core\Enums\Pay\PaymeMethodsEnum;
use App\Core\Enums\Pay\PaymentTypeEnum;
use App\Core\Enums\Pay\PaymePaymentStateEnum;
use App\Core\Enums\Pay\PayPercentageServiceEnum;
use App\Core\Repository\Payments\PaymePaymentRepository;
use App\Core\Repository\Product\ProductRepository;
use App\Core\Repository\Product\ProductsOrderRepository;
use App\Core\Services\Pay\Contracts\PaymentInterface;
use App\Core\Services\Product\ProductsOrderService;
use App\Http\Requests\Pay\PaymeRequest;
use App\Http\Requests\Payment\PaymeRedirectUrlFormRequest;
use App\Models\Orders\PaymePayment;
use App\Models\Users\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaymeService implements PaymentInterface
{
    /**
     * @param $merchantId
     * @param $login
     * @param $key
     * @param $url
     * @param string|null $method
     */
    public function __construct(
        protected         $merchantId,
        protected         $login,
        protected         $key,
        protected         $url,
        protected ?string $method = null
    )
    {
    }

    /**
     * @param PaymeRequest $paymeRequest
     * @return array|false[]|true[]
     */
    public function handle(PaymeRequest $paymeRequest): array
    {
        return match ($paymeRequest->post('method')) {
            PaymeMethodsEnum::CHECK_PERFORM_TRANSACTION->value => $this->checkPerformTransaction($paymeRequest),
            PaymeMethodsEnum::PERFORM_TRANSACTION->value => $this->performTransaction($paymeRequest),
            PaymeMethodsEnum::CREATE_TRANSACTION->value => $this->createTransaction($paymeRequest),
            PaymeMethodsEnum::CHECK_TRANSACTION->value => $this->checkTransaction($paymeRequest),
            PaymeMethodsEnum::CANCEL_TRANSACTION->value => $this->cancelTransaction($paymeRequest),
            PaymeMethodsEnum::GET_STATEMENT->value => $this->getStatement($paymeRequest),
            default => []
        };
    }


    /**
     * @param PaymeRequest $paymeRequest
     * @return array
     */
    public function createTransaction(PaymeRequest $paymeRequest): array
    {
        $request = $paymeRequest->validated();
        $orderId = $request['params']['account']['order_id'];

        if (!is_numeric($orderId)) {
            $code = -31099;
        } else {
            /**
             * @var PaymePaymentRepository $orderRepository
             */
            $orderRepository = app(PaymePaymentRepository::class);
            $order = $orderRepository->find($orderId);

            if (!empty($order)) {

                $amount = intval($request['params']['amount']) / 100;

                if (intval($amount) !== intval($order->getAmount())) {
                    $code = -31001;
                    $message = "Amount error";
                } else {

                    if ($order->isEmptyCreateTime()) {
                        $create_time = round(microtime(true) * 1000);
                        $transactionNo = $order->getId() . time();
                        $order->setTransactionId($request['params']['id']);
                        $order->setTransactionTime((string)$request['params']['time']);
                        $order->setTransactionNo($transactionNo);
                        $order->setTransactionCreateTime((string)$create_time);
                        $order->setState(PaymePaymentStateEnum::CREATE_TRANSACTION->value);
                        $orderRepository->save($order);
                    } else {
                        $create_time = $order->getTransactionCreateTime();
                    }

                    if ($order->getTransactionId() === (string)$request['params']['id']) {
                        return [
                            'create_time' => (int)$create_time,
                            'transaction' => $order->getTransactionNo(),
                            'state' => (int)$order->getState()
                        ];
                    } else {
                        $code = -31099;
                        $message = "Transaction already created";
                    }

                }

            }
        }

        return [
            'jsonrpc' => $paymeRequest->post('jsonrpc'),
            'id' => $paymeRequest->post('id'),
            'error' => [
                'code' => empty($code) ? -31099 : $code,
                'message' => empty($message) ? "PaymePayment {$orderId} not found" : $message
            ]
        ];
    }

    /**
     * @param PaymeRequest $paymeRequest
     * @return array
     */
    public function checkTransaction(PaymeRequest $paymeRequest): array
    {
        $request = $paymeRequest->validated();
        $transactionId = (string)$request['params']['id'];
        /**
         * @var PaymePaymentRepository $orderRepository
         */
        $orderRepository = app(PaymePaymentRepository::class);
        try {
            $order = $orderRepository->getByTransactionId($transactionId);
        } catch (ModelNotFoundException $e) {
            return [
                'jsonrpc' => $paymeRequest->post('jsonrpc'),
                'id' => $paymeRequest->post('id'),
                'error' => [
                    'code' => -31099,
                    'message' => "PaymePayment {$transactionId} not found"
                ]
            ];
        }

        return [
            'create_time' => (int)$order->getTransactionCreateTime(),
            'perform_time' => (int)$order->getTransactionPerformTime(),
            'cancel_time' => (int)$order->getTransactionCancelTime(),
            'transaction' => $order->getTransactionNo(),
            'state' => (int)$order->getState(),
            'reason' => $order->getCancelReason() === null ? null : intval($order->getCancelReason()),
        ];
    }

    /**
     * @param PaymeRequest $paymeRequest
     * @return array
     */
    public function cancelTransaction(PaymeRequest $paymeRequest): array
    {
        $request = $paymeRequest->validated();
        $transactionId = (string)$request['params']['id'];

        /**
         * @var PaymePaymentRepository $orderRepository
         */
        $orderRepository = app(PaymePaymentRepository::class);
        $order = $orderRepository->getByTransactionId($transactionId);

        if (
            !in_array($order->getState(), [
                PaymePaymentStateEnum::CANCEL_TRANSACTION->value,
                PaymePaymentStateEnum::CLOSED_TRANSACTION->value,
            ], true)
        ) {
            $cancelTransaction = $request['params']['reason'] == 3 ? '-1' : '-2';
            $order->setState($cancelTransaction);
            $order->setCancelReason($request['params']['reason']);
            $order->setTransactionCancelTime((string)round(microtime(true) * 1000));
            if ($cancelTransaction === '-1') {
                $order->setTransactionPerformTime('0');
            }
            $orderRepository->save($order);
        }

        return [
            'transaction' => $order->getTransactionNo(),
            'state' => (int)$order->getState(),
            'cancel_time' => (int)$order->getTransactionCancelTime()
        ];
    }

    public function getStatement(PaymeRequest $paymeRequest): array
    {
        $request = $paymeRequest->validated();
        $params = $request['params'];

        /**
         * @var PaymePaymentRepository $orderRepository
         */
        $orderRepository = app(PaymePaymentRepository::class);

        return $orderRepository->findAllFromAndToCreateTime((int)$params['from'], (int)$params['to']);
    }

    /**
     * @param PaymeRequest $paymeRequest
     * @return array
     */
    public function checkPerformTransaction(PaymeRequest $paymeRequest): array
    {
        $request = $paymeRequest->validated();
        $orderId = $request['params']['account']['order_id'];

        if (!is_numeric($orderId)) {
            $code = -31099;
        } else {
            /**
             * @var PaymePaymentRepository $orderRepository
             */
            $orderRepository = app(PaymePaymentRepository::class);
            $order = $orderRepository->find($orderId);

            if (!empty($order)) {
                $amount = intval($request['params']['amount']) / 100;

                if (intval($order->getAmount()) === intval($amount)) {
                    return ['allow' => true];
                }

                $code = -31001;
                $message = "Amount error";
            }
        }

        return [
            'jsonrpc' => $paymeRequest->post('jsonrpc'),
            'id' => $paymeRequest->post('id'),
            'error' => [
                'code' => empty($code) ? -31099 : $code,
                'message' => empty($message) ? "PaymePayment {$orderId} not found" : $message
            ]
        ];
    }

    /**
     * @param PaymeRequest $paymeRequest
     * @return array
     */
    public function performTransaction(PaymeRequest $paymeRequest): array
    {
        $request = $paymeRequest->validated();
        $transactionId = $request['params']['id'];

        /**
         * @var PaymePaymentRepository $orderRepository
         */
        $orderRepository = app(PaymePaymentRepository::class);
        $order = $orderRepository->getByTransactionId($transactionId);

        if ($order->isEmptyPerformTime()) {
            $perform_time = round(microtime(true) * 1000);
            $order->setState(PaymePaymentStateEnum::PERFORM_TRANSACTION->value);
            $order->setTransactionPerformTime((string)$perform_time);
            $orderRepository->save($order);

            $product = $order->product;

            /**
             * @var ProductsOrderService $productsOrderService
             */
            $productsOrderService = app(ProductsOrderService::class);

            $productsOrderService->create(
                $order->getProductId(),
                PaymentTypeEnum::TYPE_PAYME->value,
                $order->getTransactionId(),
                $order->getClientId(),
                $order->getAmount(),
                (string)$product->getPriceMerchant(),
                $product->priceType->getPercentage(),
                PayPercentageServiceEnum::PAYME_PERCENTAGE->value
            );
        } else {
            $perform_time = $order->getTransactionPerformTime();
        }

        return [
            'transaction' => $order->getTransactionNo(),
            'perform_time' => (int)$perform_time,
            'state' => (int)$order->getState()
        ];
    }

    /**
     * @param PaymeRedirectUrlFormRequest $paymeRedirectUrlFormRequest
     * @return string|null
     */
    public function getRedirectUrl(PaymeRedirectUrlFormRequest $paymeRedirectUrlFormRequest): ?string
    {
        if (Auth::check()) {
            $order = $this->orderInsertOrUpdate($paymeRedirectUrlFormRequest->integer('product_id'));

            /**
             * @var User $user
             */
            $user = Auth::user();
            /**
             * @var ProductsOrderRepository $productsOrderRepository
             */
            $productsOrderRepository = app(ProductsOrderRepository::class);

            if ($productsOrderRepository->existsProductOrder($order->getProductId(), $user->getId())) {
                abort(400, __('client.Already exists product order'));
            }

            $amount = intval($order->getAmount()) * 100;
            return $this->url . '/' . base64_encode("m={$this->merchantId};ac.order_id={$order->getId()};a={$amount}");
        }

        return null;
    }

    /**
     * @param string|null $method
     * @return void
     */
    public function setMethod(?string $method = null): void
    {
        $this->method = $method;
    }

    /**
     * @param int $id
     * @return array
     */
    public function generateQr(int $id): array
    {
        $order = $this->orderInsertOrUpdate($id);

        return [
            'merchant' => $this->merchantId,
            'amount' => intval($order->getAmount()) * 100,
            'order_id' => $order->getId(),
        ];
    }

    /**
     * @param int $product_id
     * @return PaymePayment|Builder|null
     */
    protected function orderInsertOrUpdate(int $product_id): PaymePayment|Builder|null
    {
        /**
         * @var User $user
         */
        $user = Auth::user();
        /**
         * @var ProductRepository $productRepository
         */
        $productRepository = app(ProductRepository::class);
        $product = $productRepository->getById($product_id);
        $price = intval($product->getDiscountPrice());

        /**
         * @var PaymePaymentRepository $orderRepository
         */
        $orderRepository = app(PaymePaymentRepository::class);
        $order = $orderRepository->findByProductIdAndClickId($product->getId(), $user->getId());

        if (!empty($order->state) && ($order->getState() === PaymePaymentStateEnum::PERFORM_TRANSACTION->value)) {
            abort(400, __('client.Transaction already created!'));
        }

        if (empty($order)) {
            $order = new PaymePayment();
            $order->setProductId($product->getId());
            $order->setAmount((string)$price);
            $order->setClientId($user->getId());
            $order->setState(PaymePaymentStateEnum::CHECK_PERFORM_TRANSACTION->value);
        } else if (!$order->isFinished()) {
            $order->setState(PaymePaymentStateEnum::CHECK_PERFORM_TRANSACTION->value);
            $order->setAmount((string)$price);
            $order->setTransactionCancelTime(null);
            $order->setTransactionPerformTime(null);
            $order->setTransactionCreateTime(null);
            $order->setCancelReason(null);
            $order->setTransactionNo(null);
            $order->setTransactionId(null);
            $order->setTransactionTime(null);
            $order->setCreatedAt(now());
            $order->setUpdatedAt(now());
        }


        $orderRepository->save($order);

        return $order;
    }
}
