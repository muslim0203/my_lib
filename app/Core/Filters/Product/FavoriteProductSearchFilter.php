<?php

namespace App\Core\Filters\Product;

use App\Models\Products\Product;
use Illuminate\Foundation\Http\FormRequest;

class FavoriteProductSearchFilter
{
    /**
     * @param FormRequest $formRequest
     * @return array
     */
    public static function search(FormRequest $formRequest): array
    {
        $query = Product::query();

        $query->whereIn('id', $formRequest->post('ids'));

        return $query->get()->all();
    }
}
