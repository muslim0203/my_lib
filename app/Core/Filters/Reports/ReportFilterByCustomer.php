<?php

namespace App\Core\Filters\Reports;

use App\Core\Enums\Pay\PaymentTypeEnum;
use App\Core\Helpers\Lang\LanguageHelper;
use App\Models\Users\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class ReportFilterByCustomer
{
    /**
     * @param FormRequest $formRequest
     * @return LengthAwarePaginator
     */
    public static function search(FormRequest $formRequest): LengthAwarePaginator
    {
        $free = PaymentTypeEnum::TYPE_FREE->value;
        $title = LanguageHelper::getTitle();

        $sqlQuery = "with report as (
                                    select po.customer_id,
                                        count(po.*) as amount_books,
                                        SUM(po.merchant_money_amount) as merchant_price_amount,
                                        json_agg(
                                            json_build_object('id', p.id, 'title', p.$title)
                                        ) as products
                                        from products_orders po
                                        inner join products p on p.id = po.product_id
                                        where po.payment_type != '{$free}' and po.enabled %s %s %s
                                        group by po.customer_id
                                        order by amount_books desc
                                    ),
                         list as (select su.first_name || ' ' || su.last_name as customer_full_name,
                                         r.*
                                  from users u
                                       inner join report r on r.customer_id = u.id
                                       inner join social_users su on su.id = u.social_user_id)";

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
            $queryWhereFirst = "and date(po.created_at) >= '{$value}' ";
        });

        $queryWhereSecond = '';
        $formRequest->whenFilled('to_date', function ($value) use (&$queryWhereSecond) {
            $queryWhereSecond = "and date(po.created_at) <= '{$value}' ";
        });

        $sqlQuery = sprintf($sqlQuery, "and po.author_id = {$user->getId()} ", $queryWhereFirst, $queryWhereSecond);
        $totalQuery = sprintf($totalQuery, "and po.author_id = {$user->getId()} ", $queryWhereFirst, $queryWhereSecond);

        return PaginationFilter::wrap($sqlQuery, $totalQuery, $formRequest);
    }
}
