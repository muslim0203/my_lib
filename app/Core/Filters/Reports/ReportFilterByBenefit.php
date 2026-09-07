<?php

namespace App\Core\Filters\Reports;

use App\Core\Enums\Pay\PaymentTypeEnum;
use App\Core\Enums\Reports\ReportTypeEnum;
use App\Core\Helpers\Lang\LanguageHelper;
use App\Models\Users\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ReportFilterByBenefit
{
    /**
     * @param FormRequest $formRequest
     * @return LengthAwarePaginator
     */
    public static function search(FormRequest $formRequest): LengthAwarePaginator
    {
        if (!Auth::check()) {
            abort(ResponseAlias::HTTP_UNAUTHORIZED, __('client.User is not logged in.'));
        }

        /**
         * @var User $user
         */
        $user = Auth::user();

        $sort =
            $formRequest->post('report_type_id') === ReportTypeEnum::BOOKS_LOT_BENEFIT_BOUGHT->value
                ? 'desc'
                : (
            $formRequest->post('report_type_id') === ReportTypeEnum::BOOKS_LOW_BENEFIT_BOUGHT->value
                ? 'asc'
                : ($formRequest->post('report_type_id') === ReportTypeEnum::BOOKS_LOT_BOUGHT->value ? 'desc' : 'asc')
            );

        // $title is a COLUMN IDENTIFIER and therefore cannot be bound; it is
        // allow-listed against App\Core\Enums\LanguageEnum in LanguageHelper.
        $title = LanguageHelper::getTitle();

        $sqlQuery = "
            with report as (select product_id,
                                   SUM(merchant_money_amount) as merchant_price_amount,
                                   count(*) as amount_sold
                            from products_orders
                            where enabled = true and payment_type != :free %s %s %s
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

        $bindings = [
            'free'      => PaymentTypeEnum::TYPE_FREE->value,
            'author_id' => $user->getId(),
        ];

        $queryWhereFirst = '';
        $formRequest->whenFilled('from_date', function ($value) use (&$queryWhereFirst, &$bindings) {
            if (!is_scalar($value)) {
                return;
            }

            $queryWhereFirst = 'and date(created_at) >= :from_date ';
            $bindings['from_date'] = (string) $value;
        });

        $queryWhereSecond = '';
        $formRequest->whenFilled('to_date', function ($value) use (&$queryWhereSecond, &$bindings) {
            if (!is_scalar($value)) {
                return;
            }

            $queryWhereSecond = 'and date(created_at) <= :to_date ';
            $bindings['to_date'] = (string) $value;
        });

        $sqlQuery = sprintf($sqlQuery, 'and author_id = :author_id ', $queryWhereFirst, $queryWhereSecond);
        $totalQuery = sprintf($totalQuery, 'and author_id = :author_id ', $queryWhereFirst, $queryWhereSecond);

        return PaginationFilter::wrap($sqlQuery, $totalQuery, $formRequest, $bindings);
    }
}
