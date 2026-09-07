<?php

namespace App\Core\Filters\Reports;

use App\Core\Enums\Pay\PaymentTypeEnum;
use App\Core\Helpers\Lang\LanguageHelper;
use App\Models\Users\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ReportFilterByCustomer
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

        // $title is a COLUMN IDENTIFIER and therefore cannot be bound; it is
        // allow-listed against App\Core\Enums\LanguageEnum in LanguageHelper.
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
                                        where po.payment_type != :free and po.enabled %s %s %s
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

        $bindings = [
            'free'      => PaymentTypeEnum::TYPE_FREE->value,
            'author_id' => $user->getId(),
        ];

        $queryWhereFirst = '';
        $formRequest->whenFilled('from_date', function ($value) use (&$queryWhereFirst, &$bindings) {
            if (!is_scalar($value)) {
                return;
            }

            $queryWhereFirst = 'and date(po.created_at) >= :from_date ';
            $bindings['from_date'] = (string) $value;
        });

        $queryWhereSecond = '';
        $formRequest->whenFilled('to_date', function ($value) use (&$queryWhereSecond, &$bindings) {
            if (!is_scalar($value)) {
                return;
            }

            $queryWhereSecond = 'and date(po.created_at) <= :to_date ';
            $bindings['to_date'] = (string) $value;
        });

        $sqlQuery = sprintf($sqlQuery, 'and po.author_id = :author_id ', $queryWhereFirst, $queryWhereSecond);
        $totalQuery = sprintf($totalQuery, 'and po.author_id = :author_id ', $queryWhereFirst, $queryWhereSecond);

        return PaginationFilter::wrap($sqlQuery, $totalQuery, $formRequest, $bindings);
    }
}
