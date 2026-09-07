<?php

namespace App\Core\Filters\Reports;

use App\Core\Enums\Pay\PaymentTypeEnum;
use App\Core\Enums\Reports\ReportTypeEnum;
use App\Core\Helpers\Lang\LanguageHelper;
use App\Models\Users\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class ReportFilterByBenefit
{
    /**
     * @param FormRequest $formRequest
     * @return LengthAwarePaginator
     */
    public static function search(FormRequest $formRequest): LengthAwarePaginator
    {
        $sort =
            $formRequest->post('report_type_id') === ReportTypeEnum::BOOKS_LOT_BENEFIT_BOUGHT->value
                ? 'desc'
                : (
            $formRequest->post('report_type_id') === ReportTypeEnum::BOOKS_LOW_BENEFIT_BOUGHT->value
                ? 'asc'
                : ($formRequest->post('report_type_id') === ReportTypeEnum::BOOKS_LOT_BOUGHT->value ? 'desc' : 'asc')
            );

        $free = PaymentTypeEnum::TYPE_FREE->value;
        $title = LanguageHelper::getTitle();

        $sqlQuery = "
            with report as (select product_id,
                                   SUM(merchant_money_amount) as merchant_price_amount,
                                   count(*) as amount_sold
                            from products_orders
                            where enabled = true and payment_type != '{$free}' %s %s %s
                            group by product_id
                            order by merchant_price_amount {$sort}),
                 list as (select f.path || '/' || f.file_name as path,
                                 f.id as file_id,
                                 p.wrapper_file_name,
                                 p.$title as title,
                                 p.id as product_id,
                                 r.merchant_price_amount,
                                 r.amount_sold
                          from products p
                                   inner join report r on r.product_id = p.id
                                   inner join files f on f.id = p.wrapper_file_id)
        ";

        $totalQuery = $sqlQuery . " select count(*) as total from list";
        $sqlQuery .= "select * from list";

        if (!Auth::check()) {
            abort(__('client.User is not logged in.'));
        }

        /**
         * @var User $user
         */
        $user = Auth::user();


        $queryWhereFirst = '';
        $formRequest->whenFilled('from_date', function ($value) use (&$queryWhereFirst) {
            $queryWhereFirst = "and date(created_at) >= '{$value}' ";
        });

        $queryWhereSecond = '';
        $formRequest->whenFilled('to_date', function ($value) use (&$queryWhereSecond) {
            $queryWhereSecond = "and date(created_at) <= '{$value}' ";
        });

        $sqlQuery = sprintf($sqlQuery, "and author_id = {$user->getId()} ", $queryWhereFirst, $queryWhereSecond);
        $totalQuery = sprintf($totalQuery, "and author_id = {$user->getId()} ", $queryWhereFirst, $queryWhereSecond);

        return PaginationFilter::wrap($sqlQuery, $totalQuery, $formRequest);

    }
}
