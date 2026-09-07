<?php

namespace App\Core\Filters\Product;

use App\Core\Enums\ProductPriceTypeEnum;
use App\Core\Enums\Products\ProductCategoryEnum;
use App\Core\Helpers\PageSizeHelper;
use App\Models\Enums\EnumProductGenre;
use App\Models\Enums\EnumProductType;
use App\Models\Products\Product;
use App\Models\Users\User;
use App\Models\Users\UserInterest;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductListSearchFilter
{
    /**
     * @param FormRequest $formRequest
     * @return LengthAwarePaginator
     */
    public static function search(FormRequest $formRequest): LengthAwarePaginator
    {
        $query = Product::query()
            ->selectRaw('id, title_oz, title_uz, title_ru, author_id, status_id, wrapper_file_id, state, discount_id, price_value, price_id, price_type_id, views_count, has_audio_file, is_download')
            ->from('products as p');

        /**
         * @var User $user
         */
        $user = Auth::user();

        $formRequest->whenFilled('title', function ($value) use ($query) {

            $value = trim(strip_tags($value));

            $query->where('title_oz', 'ilike', '%' . $value . '%')
                ->orWhere('title_uz', 'ilike', '%' . $value . '%')
                ->orWhere('title_ru', 'ilike', '%' . $value . '%');

        });

        $formRequest->whenFilled('category', function ($value) use ($user, $query) {

            if ($value === ProductCategoryEnum::_RECOMMEND_FOR_YOU->value) {

                if (!empty($user->interests)) {
                    $types = [];
                    $genres = [];

                    foreach ($user->interests as $interest) {

                        /**
                         * @var UserInterest $interest
                         */

                        if ($interest->model->getTable() === (new EnumProductType())->getTable()) {
                            $types[] = $interest->model->getId();
                        }

                        if ($interest->model->getTable() === (new EnumProductGenre())->getTable()) {
                            $genres[] = $interest->model->getId();
                        }
                    }

                    if (!empty($types)) {
                        $query->join(
                            'link_product_types as lpt',
                            'lpt.product_id',
                            '=',
                            'p.id')
                            ->whereIn('lpt.type_id', $types);
                    }

                    if (!empty($genres)) {
                        $query->join(
                            'link_product_genres as lpg',
                            'lpg.product_id',
                            '=',
                            'p.id')
                            ->whereIn('lpg.genre_id', $genres);
                    }

                }

            } else if ($value === ProductCategoryEnum::_NEW->value) {

                $time = Carbon::now()->subDays(30)->format('Y-m-d');

                $query->whereDate(DB::raw('p.created_at::date'), '>', $time);

            } else if ($value === ProductCategoryEnum::_DISCOUNT->value) {

                $query->whereNotNull('p.discount_id');

            } else if ($value === ProductCategoryEnum::_FREE->value) {

                $query->whereNull('p.price_value');

            } else if ($value === ProductCategoryEnum::_SCIENTIFIC_ARTICLES->value) {

                $query->whereNull('p.price_value');

            } else if ($value === ProductCategoryEnum::_TOP->value) {

                $query->where('price_type_id', ProductPriceTypeEnum::EXPRESS->value);

            }

        });

        $formRequest->whenFilled('category_id', function ($value) use ($query) {
            $query->join('link_product_categories as lpc', 'lpc.product_id', 'p.id')
                ->where('lpc.category_id', $value);
        });

        $query->where('p.is_deleted', false);

        $query->groupByRaw('p.id')
            ->orderByDesc('p.id');

        return $query->paginate(PageSizeHelper::getPageSize($formRequest));
    }
}
