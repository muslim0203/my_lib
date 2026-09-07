<?php

namespace App\Http\Controllers\Api\Reports;

use App\Core\Helpers\Response\Success;
use App\Core\Services\Reports\Contracts\ReportInterface;
use App\Http\Requests\Reports\PurchaseFormRequest;
use App\Http\Resources\Reports\PurchaseListResource;
use App\Http\Resources\Reports\ReportTypeListResource;
use App\Models\Reports\ReportType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Routing\Controller;

class ReportController extends Controller
{
    public function __construct(
        protected ReportInterface $reportService
    )
    {
    }

    /**
     * @return JsonResponse
     */
    public function payList(): JsonResponse
    {
        return Success::send('Pay list', $this->reportService->payList());
    }

    /**
     * @param PurchaseFormRequest $purchaseFormRequest
     * @return AnonymousResourceCollection|LengthAwarePaginator
     */
    public function purchaseStatistics(PurchaseFormRequest $purchaseFormRequest): LengthAwarePaginator|AnonymousResourceCollection
    {
        if (!empty($purchaseFormRequest->post('report_type_id'))) {
            return $this->reportService->purchaseStatistics($purchaseFormRequest);
        }

        return PurchaseListResource::collection($this->reportService->purchaseStatistics($purchaseFormRequest));
    }

    /**
     * @return AnonymousResourceCollection
     */
    public function typeList(): AnonymousResourceCollection
    {
        return ReportTypeListResource::collection(ReportType::query()->where('enabled', true)->where('is_show_front', true)->get()->all());
    }
}
