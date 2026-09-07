<?php

namespace App\Core\Search;

use App\Core\Helpers\Lang\LanguageHelper;
use App\Core\Helpers\PageSizeHelper;
use App\Http\Requests\Report\ProductOrderSearchRequest;
use App\Models\Products\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProductOrderSearch extends Model
{
    /**
     * @param ProductOrderSearchRequest $productOrderSearchRequest
     * @return LengthAwarePaginator
     */
    public static function search(ProductOrderSearchRequest $productOrderSearchRequest): LengthAwarePaginator
    {
        $title = LanguageHelper::getTitle();

        $query = Product::query()
            ->from('products AS p')
            ->selectRaw(
                "
                p.{$title} AS title,
                CONCAT(f.path,'/',f.file_name) AS wrapper_file,
                CONCAT(su.first_name,' ',su.last_name,' ',su.middle_name) AS author,
                po.payment_type,
                p.id,
                po.order_price,
                po.merchant_money_amount,
                po.web_service_money_amount,
                po.web_service_and_payment_system_money_amount,
                po.payment_system_service_percentage,
                po.payment_system_service_money_amount,
                po.web_service_money_amount,
                po.created_at",
            )
            ->join('products_orders AS po', 'po.product_id', '=', 'p.id')
            ->leftJoin('files AS f', 'p.wrapper_file_id', '=', 'f.id')
            ->leftJoin('users AS u', 'po.author_id', '=', 'u.id')
            ->leftJoin('social_users AS su', 'su.id', '=', 'u.social_user_id')
            ->with(['productGenre', 'productCategory', 'productTag']);

        $productOrderSearchRequest->whenFilled('genre_id', function ($value) use ($query) {
            $query->whereExists(function ($query) use ($value) {
                $query->select(DB::raw(1))
                    ->from('link_product_genres')
                    ->whereRaw('p.id = link_product_genres.product_id')
                    ->where('link_product_genres.genre_id', $value);
            });
        });

        $productOrderSearchRequest->whenFilled('category_id', function ($value) use ($query) {
            $query->whereExists(function ($query) use ($value) {
                $query->select(DB::raw(1))
                    ->from('link_product_categories')
                    ->whereRaw('p.id = link_product_categories.product_id')
                    ->where('link_product_categories.category_id', $value);
            });
        });

        $productOrderSearchRequest->whenFilled('tag_id', function ($value) use ($query) {
            $query->whereExists(function ($query) use ($value) {
                $query->select(DB::raw(1))
                    ->from('link_product_tags')
                    ->whereRaw('p.id = link_product_tags.product_id')
                    ->where('link_product_tags.tag_id', $value);
            });
        });

        $productOrderSearchRequest->whenFilled('title', function ($value) use ($query) {
            $query
                ->orWhere('p.title_oz', 'ilike', '%' . $value . '%')
                ->orWhere('p.title_uz', 'ilike', '%' . $value . '%')
                ->orWhere('p.title_ru', 'ilike', '%' . $value . '%');
        });

        $productOrderSearchRequest->whenFilled('from_date', function ($value) use ($query) {
            $query->whereDate('po.created_at', '>=', date('Y-m-d', strtotime($value)));
        });
        $productOrderSearchRequest->whenFilled('to_date', function ($value) use ($query) {
            $query->whereDate('po.created_at', '<=', date('Y-m-d', strtotime($value)));
        });


        $query->orderByDesc('po.created_at');

        return $query->paginate(PageSizeHelper::getPageSize($productOrderSearchRequest));
    }

    public static function searchTopBuyers(ProductOrderSearchRequest $productOrderSearchRequest): LengthAwarePaginator
    {
        $query = DB::table('products_orders as po')
            ->join('products as p', 'p.id', '=', 'po.product_id')
            ->join('users as u', 'u.id', '=', 'po.customer_id')
            ->leftJoin('social_users as su', 'su.id', '=', 'u.social_user_id')
            ->selectRaw("
                            po.customer_id,
                            json_agg(
                                json_build_object(
                                    'product_id', po.product_id,
                                    'title', p.title_oz,
                                    'created_at', po.created_at,
                                    'price_amount', po.order_price,
                                    'merchant_price_amount', po.merchant_money_amount,
                                    'merchant_status', po.status
                                )
                            ) as books_detail,
                            COUNT(*) as books_amount,
                            CONCAT(su.first_name, ' ', su.last_name, ' ', su.middle_name) as customer_full_name
                    ")
            ->where('po.payment_type', '!=', 'free')
            ->groupBy('po.customer_id', 'su.first_name', 'su.last_name', 'su.middle_name')
            ->orderByDesc('books_amount');

        $productOrderSearchRequest->whenFilled('full_name', function ($value) use ($query) {
            $query
                ->orWhere('customer_full_name', 'ilike', '%' . $value . '%');
        });

        $productOrderSearchRequest->whenFilled('from_date', function ($value) use ($query) {
            $query->whereDate('po.created_at', '>=', date('Y-m-d', strtotime($value)));
        });
        $productOrderSearchRequest->whenFilled('to_date', function ($value) use ($query) {
            $query->whereDate('po.created_at', '<=', date('Y-m-d', strtotime($value)));
        });

        return $query->paginate(PageSizeHelper::getPageSize($productOrderSearchRequest));
    }

    public static function searchTopProducts(ProductOrderSearchRequest $productOrderSearchRequest): LengthAwarePaginator
    {
        $title = LanguageHelper::getTitle();
        $report = DB::table('products_orders as po')
            ->select(
                'po.product_id',
                DB::raw('COUNT(*) as customer_count'),
                DB::raw("SUM(po.order_price) as order_price"),
                DB::raw("SUM(po.merchant_money_amount) as merchant_money_amount"),
                DB::raw("po.web_service_price_percentage as web_service_price_percentage"),
                DB::raw("SUM(po.web_service_and_payment_system_money_amount) AS web_service_and_payment_system_money_amount"),
            )
            ->join('users as u', 'u.id', '=', 'po.customer_id')
            ->leftJoin('social_users as su', 'su.id', '=', 'u.social_user_id')
            ->where('po.payment_type', '!=', 'free');

        $productOrderSearchRequest->whenFilled('from_date', function ($value) use ($report) {
            $report->whereDate('po.created_at', '>=', date('Y-m-d', strtotime($value)));
        });
        $productOrderSearchRequest->whenFilled('to_date', function ($value) use ($report) {
            $report->whereDate('po.created_at', '<=', date('Y-m-d', strtotime($value)));
        });
            $report->groupBy(['po.product_id','po.web_service_price_percentage']);

        $query = DB::table('products as p')
            ->select(
                "p.{$title} as title",
                DB::raw("CONCAT(f.path,'/',f.file_name) AS wrapper_file"),
                'r.product_id',
                'r.customer_count',
                'r.order_price',
                'r.merchant_money_amount',
                'r.web_service_price_percentage',
                'r.web_service_and_payment_system_money_amount',
            )
            ->leftJoin('files AS f', 'p.wrapper_file_id', '=', 'f.id')
            ->joinSub($report, 'r', function ($join) {
                $join->on('p.id', '=', 'r.product_id');
            })
            ->orderBy('r.customer_count', 'desc');

        return $query->paginate(PageSizeHelper::getPageSize($productOrderSearchRequest));
    }
}
