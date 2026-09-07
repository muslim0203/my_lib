<?php

namespace App\Http\Controllers\Api\Pay;

use App\Core\Enums\Pay\PaymeMethodsEnum;
use App\Core\Helpers\Response\Success;
use App\Core\Services\Pay\Contracts\PaymentInterface;
use App\Http\Requests\Pay\PaymeRequest;
use App\Http\Requests\Payment\PaymeRedirectUrlFormRequest;
use App\Http\Resources\Orders\PaymePaymentResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class PaymeController extends Controller
{
    public function __construct(
        protected PaymentInterface $paymentService
    )
    {
    }

    /**
     * @param PaymeRedirectUrlFormRequest $paymeRedirectUrlFormRequest
     * @return JsonResponse
     */
    public function getRedirectUrl(PaymeRedirectUrlFormRequest $paymeRedirectUrlFormRequest): JsonResponse
    {
        return Success::send('Redirect url.', [
            'redirect_url' => $this->paymentService->getRedirectUrl($paymeRedirectUrlFormRequest)
        ]);
    }


    /**
     * @param PaymeRequest $paymeRequest
     * @return JsonResponse
     */
    public function pay(PaymeRequest $paymeRequest): JsonResponse
    {
        $response = $this->paymentService->handle($paymeRequest);

        if (isset($response['error'])) {
            return response()->json($response);
        }

        if (isset($response['allow'])) {
            return response()->json(['result' => $response]);
        }

        if ($paymeRequest->post('method') === PaymeMethodsEnum::GET_STATEMENT->value) {
            $response = ['transactions' => PaymePaymentResource::collection($response)];
        }

        return response()->json([
            'result' => $response,
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function check(Request $request): JsonResponse
    {
        $transactionId = $request->input('transaction_id');

        return response()
            ->json($this->paymentService->checkTransaction($transactionId));
    }

    /**
     * @param int $id
     * @return array
     */
    public function generateQr(int $id): array
    {
        return $this->paymentService->generateQr($id);
    }
}
