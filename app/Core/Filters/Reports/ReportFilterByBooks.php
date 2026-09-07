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

class ReportFilterByBooks
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

        $sort = $formRequest->post('report_type_id') === ReportTypeEnum::BOOKS_LOT_BOUGHT->value ? 'desc' : 'asc';

        // $title is a COLUMN IDENTIFIER and therefore cannot be bound; it is
        // allow-listed against App\Core\Enums\LanguageEnum in LanguageHelper.
        $title = LanguageHelper::getTitle();

        $bindings = [
            'free'      => PaymentTypeEnum::TYPE_FREE->value,
            'author_id' => $user->getId(),
        ];

        if ($formRequest->post('report_type_id') === ReportTypeEnum::BOOKS_FREE->value) {
            $sqlQuery = "
                with report as (
                    select
                        product_id,
                        count(*) as amount_sold
                    from products_orders
                    where enabled and payment_type = :free %s %s %s
                    group by product_id
                    order by amount_sold desc
                ),
                    list as (
                        select
                            f.path || '/' || f.file_name as path,
                            f.id as file_id,
                            p.id as product_id,
                            p.{$title} as title,
                            r.amount_sold as amount_sold
                        from products p
                        inner join report r on r.product_id = p.id
                        inner join files f on f.id = p.wrapper_file_id
                    )
            ";
        } else if ($formRequest->post('report_type_id') === ReportTypeEnum::BOOKS_NOT_BOUGHT->value) {
            // A second, distinct placeholder name is required: PDO does not
            // reliably accept the same named parameter twice in one statement.
            $bindings['author_id_outer'] = $user->getId();

            $sqlQuery = "
                with list as (
                    select
                        p.id as product_id,
                        p.{$title} as title,
                        p.price_value as price_amount,
                        p.discount_id,
                        pd.discount,
                        case
                            when p.discount_id is null then 0
                            else round((pd.discount::numeric * p.price_value::numeric) / 100, 2)
                        end as merchant_price_amount,
                        case
                            when p.discount_id is null then 0
                            else round((p.price_value::numeric - ((pd.discount::numeric * p.price_value::numeric) / 100)), 2)
                        end as discount_price_amount,
                        f.id as file_id,
                        f.path || '/' || f.file_name as path,
                        p.wrapper_file_name
                    from products p
                    inner join files f on f.id = p.wrapper_file_id
                    left join product_discounts pd on pd.id = p.discount_id
                    where p.id not in (
                                       select
                                            product_id
                                       from products_orders
                                       where enabled = true and payment_type != :free %s %s %s) and p.author_id = :author_id_outer and p.price_value is not null
                )
            ";
        } else {
            $sqlQuery = "
                with report as (select product_id,
                                       count(*) as amount_sold,
                                       sum(merchant_money_amount) as merchant_money_amount
                                from products_orders
                                where enabled and payment_type != :free %s %s %s
                                group by product_id
                                order by amount_sold {$sort}),
                     list as (select f.path || '/' || f.file_name as path,
                                     f.id as file_id,
                                     p.wrapper_file_name,
                                     p.$title as title,
                                     p.id as product_id,
                                     r.amount_sold,
                                     r.merchant_money_amount
                              from products p
                                       inner join report r on r.product_id = p.id
                                       inner join files f on f.id = p.wrapper_file_id)
        ";
        }

        $totalQuery = $sqlQuery . " select count(*) as total from list";
        $sqlQuery .= "select * from list";

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
