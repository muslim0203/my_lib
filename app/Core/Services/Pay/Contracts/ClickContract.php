<?php

namespace App\Core\Services\Pay\Contracts;

use App\Core\Services\Pay\Helpers\ClickResult;
use App\Http\Requests\Payment\ClickRedirectUrlFormRequest;
use App\Http\Requests\Payment\ClickTransferPaymentCompleteFormRequest;
use App\Http\Requests\Payment\ClickTransferPaymentPrepareFormRequest;

interface ClickContract
{
    public function getRedirectUrl(ClickRedirectUrlFormRequest $clickRedirectUrlFormRequest): string;

    public function preparePayment(ClickTransferPaymentPrepareFormRequest $clickTransferPaymentPrepareFormRequest): ClickResult;

    public function completePayment(ClickTransferPaymentCompleteFormRequest $clickTransferPaymentCompleteFormRequest): ClickResult;
}
