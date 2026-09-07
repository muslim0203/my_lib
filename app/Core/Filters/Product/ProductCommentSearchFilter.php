<?php

namespace App\Core\Filters\Product;

use App\Core\Helpers\PageSizeHelper;
use App\Models\Products\ProductComment;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Foundation\Http\FormRequest;

class ProductCommentSearchFilter
{
    /**
     * @param FormRequest $formRequest
     * @param int $product_id
     * @return LengthAwarePaginator
     */
    public static function search(FormRequest $formRequest, ?int $product_id): LengthAwarePaginator
    {
        $query = ProductComment::query()
            ->whereNull('parent_id');

        $query->where('product_id', $product_id)
            ->where('enabled', true);

        return $query->paginate(PageSizeHelper::getPageSize($formRequest));
    }
}
