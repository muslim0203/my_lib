<?php

namespace App\Core\Services\Pay\Contracts;

use App\Http\Requests\Pay\PaymeRequest;
use App\Http\Requests\Payment\PaymeRedirectUrlFormRequest;

interface PaymentInterface
{
    public function handle(PaymeRequest $paymeRequest);

    public function checkPerformTransaction(PaymeRequest $paymeRequest): array;

    public function createTransaction(PaymeRequest $paymeRequest);

    public function performTransaction(PaymeRequest $paymeRequest);

    public function checkTransaction(PaymeRequest $paymeRequest);

    public function cancelTransaction(PaymeRequest $paymeRequest);

    public function getStatement(PaymeRequest $paymeRequest): array;

    public function getRedirectUrl(PaymeRedirectUrlFormRequest $paymeRedirectUrlFormRequest): ?string;

    public function generateQr(int $id): array;
}
