<?php

namespace App\Http\Controllers\Api\Pay;

use App\Core\Helpers\Response\Success;
use App\Core\Services\Pay\Contracts\ClickContract;
use App\Core\Services\Pay\Helpers\ClickResult;
use App\Http\Requests\Payment\ClickRedirectUrlFormRequest;
use App\Http\Requests\Payment\ClickTransferPaymentCompleteFormRequest;
use App\Http\Requests\Payment\ClickTransferPaymentPrepareFormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ClickController extends Controller
{
    public function __construct(
        protected ClickContract $clickContract
    )
    {
    }

    /**
     * @param ClickRedirectUrlFormRequest $clickRedirectUrlFormRequest
     * @return JsonResponse
     */
    public function getRedirectUrl(ClickRedirectUrlFormRequest $clickRedirectUrlFormRequest): JsonResponse
    {
        return Success::send('Redirect url.', [
            'redirect_url' => $this->clickContract->getRedirectUrl($clickRedirectUrlFormRequest)
        ]);
    }

    /**
     * @param ClickTransferPaymentPrepareFormRequest $clickTransferPaymentPrepareFormRequest
     * @return ClickResult
     */
    public function preparePayment(ClickTransferPaymentPrepareFormRequest $clickTransferPaymentPrepareFormRequest): ClickResult
    {
        return $this->clickContract->preparePayment($clickTransferPaymentPrepareFormRequest);
    }

    public function completePayment(ClickTransferPaymentCompleteFormRequest $clickTransferPaymentCompleteFormRequest): ClickResult
    {
        return $this->clickContract->completePayment($clickTransferPaymentCompleteFormRequest);
    }

    /**
     * @param ClickRedirectUrlFormRequest $clickRedirectUrlFormRequest
     * @return JsonResponse
     */
    public function generateQrCode(ClickRedirectUrlFormRequest $clickRedirectUrlFormRequest): JsonResponse
    {
        $base64QRCode = base64_encode(QrCode::format('png')->size(300)->generate($this->clickContract->getRedirectUrl($clickRedirectUrlFormRequest)));

        return Success::send('Qr code generate', [
            'data' => 'data:image/png;base64,' . $base64QRCode,
        ]);
    }
}
