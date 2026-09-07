<?php

namespace App\Core\Filters\Product;

use App\Core\Helpers\PageSizeHelper;
use App\Http\Requests\Products\ProductFilterRequest;
use App\Models\Company\Company;
use App\Models\Products\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ProductSearchFilter
{
    /**
     * @param ProductFilterRequest $productFilterRequest
     * @return LengthAwarePaginator
     */
    public static function search(ProductFilterRequest $productFilterRequest): LengthAwarePaginator
    {
        $query = Product::query();

        $productFilterRequest->whenFilled('title_uz', function ($value) use ($query) {
            $query->where('title_uz', 'ilike', '%' . $value . '%');
        });

        $productFilterRequest->whenFilled('title_oz', function ($value) use ($query) {
            $query->where('title_oz', 'ilike', '%' . $value . '%');
        });

        $productFilterRequest->whenFilled('title_ru', function ($value) use ($query) {
            $query->where('title_ru', 'ilike', '%' . $value . '%');
        });

        $productFilterRequest->whenFilled('enabled', function ($value) use ($query) {
            $query->where('enabled', $value);
        });

        $productFilterRequest->whenFilled('created_at', function ($value) use ($query) {
            $query->where(DB::raw('created_at::date'), date('Y-m-d', strtotime($value)));
        });

        $query->orderByDesc('id');

        return $query->paginate(PageSizeHelper::getPageSize($productFilterRequest));
    }
}
