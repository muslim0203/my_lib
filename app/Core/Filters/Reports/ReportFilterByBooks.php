<?php

namespace App\Core\Filters\Reports;

use App\Core\Enums\Pay\PaymentTypeEnum;
use App\Core\Enums\Reports\ReportTypeEnum;
use App\Core\Helpers\Lang\LanguageHelper;
use App\Models\Users\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class ReportFilterByBooks
{
    /**
     * @param FormRequest $formRequest
     * @return LengthAwarePaginator
     */
    public static function search(FormRequest $formRequest): LengthAwarePaginator
    {
        if (!Auth::check()) {
            abort(__('client.User is not logged in.'));
        }

        /**
         * @var User $user
         */
        $user = Auth::user();

        $sort = $formRequest->post('report_type_id') === ReportTypeEnum::BOOKS_LOT_BOUGHT->value ? 'desc' : 'asc';
        $free = PaymentTypeEnum::TYPE_FREE->value;
        $title = LanguageHelper::getTitle();

        if ($formRequest->post('report_type_id') === ReportTypeEnum::BOOKS_FREE->value) {
            $sqlQuery = "
                with report as (
                    select
                        product_id,
                        count(*) as amount_sold
                    from products_orders
                    where enabled and payment_type = '{$free}' %s %s %s
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
                                       where enabled = true and payment_type != '{$free}' %s %s %s) and p.author_id = {$user->getId()} and p.price_value is not null
                )
            ";
        } else {
            $sqlQuery = "
                with report as (select product_id,
                                       count(*) as amount_sold,
                                       sum(merchant_money_amount) as merchant_money_amount
                                from products_orders
                                where enabled and payment_type != '{$free}' %s %s %s
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
