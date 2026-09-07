<?php

namespace App\Core\Services\Reports;

use App\Core\Enums\Reports\ReportTypeEnum;
use App\Core\Filters\Product\ProductsOrderFilterByAuthor;
use App\Core\Filters\Reports\ReportFilterByBenefit;
use App\Core\Filters\Reports\ReportFilterByBooks;
use App\Core\Filters\Reports\ReportFilterByCustomer;
use App\Core\Repository\Product\ProductsOrderRepository;
use App\Core\Services\Reports\Contracts\ReportInterface;
use App\Http\Requests\Reports\PurchaseFormRequest;
use App\Models\Products\ProductsOrder;
use App\Models\Users\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class ReportService implements ReportInterface
{
    /**
     * @param ProductsOrderRepository $productsOrderRepository
     */
    public function __construct(
        protected ProductsOrderRepository $productsOrderRepository
    )
    {
    }

    /**
     * @param PurchaseFormRequest $purchaseFormRequest
     * @return LengthAwarePaginator
     */
    public function purchaseStatistics(PurchaseFormRequest $purchaseFormRequest): LengthAwarePaginator
    {
        return match (intval($purchaseFormRequest->post('report_type_id'))) {
            ReportTypeEnum::USERS_LOT_BOUGHT->value => ReportFilterByCustomer::search($purchaseFormRequest),
            ReportTypeEnum::BOOKS_LOT_BOUGHT->value,
            ReportTypeEnum::BOOKS_LOT_BENEFIT_BOUGHT->value,
            ReportTypeEnum::BOOKS_LOW_BENEFIT_BOUGHT->value => ReportFilterByBenefit::search($purchaseFormRequest),
            ReportTypeEnum::BOOKS_LOW_BOUGHT->value,
            ReportTypeEnum::BOOKS_NOT_BOUGHT->value,
            ReportTypeEnum::BOOKS_FREE->value => ReportFilterByBooks::search($purchaseFormRequest),
            default => ProductsOrderFilterByAuthor::search($purchaseFormRequest)
        };
    }

    /**
     * @return ProductsOrder|array
     */
    public function payList(): ProductsOrder|array
    {
        if (!Auth::check()) {
            abort(__('client.User is not logged in.'));
        }

        /**
         * @var User $user
         */
        $user = Auth::user();

        return $this->productsOrderRepository->totalSum($user->getId()) ?? [];
    }
}
