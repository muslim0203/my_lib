<?php

namespace App\Core\Services\Reports\Contracts;

use App\Http\Requests\Reports\PurchaseFormRequest;
use Illuminate\Pagination\LengthAwarePaginator;

interface ReportInterface
{
    public function purchaseStatistics(PurchaseFormRequest $purchaseFormRequest): LengthAwarePaginator;

    public function payList();
}
