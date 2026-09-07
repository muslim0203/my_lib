<?php

namespace App\Core\Filters\Enums;

use App\Core\Helpers\PageSizeHelper;
use App\Http\Requests\Enums\EnumCategoriesFilterRequest;
use App\Models\Enums\EnumCategories;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class EnumCategoriesSearchFilter
{
    /**
     * @param EnumCategoriesFilterRequest $enumCategoriesFilterRequest
     * @return LengthAwarePaginator
     */
    public static function search(EnumCategoriesFilterRequest $enumCategoriesFilterRequest): LengthAwarePaginator
    {
        $query = EnumCategories::query();

        $enumCategoriesFilterRequest->whenFilled('name_uz', function ($value) use ($query) {
            $query->where('name_uz', 'ilike', '%' . $value . '%');
        });

        $enumCategoriesFilterRequest->whenFilled('name_oz', function ($value) use ($query) {
            $query->where('name_oz', 'ilike', '%' . $value . '%');
        });

        $enumCategoriesFilterRequest->whenFilled('name_ru', function ($value) use ($query) {
            $query->where('name_ru', 'ilike', '%' . $value . '%');
        });

        $enumCategoriesFilterRequest->whenFilled('enabled', function ($value) use ($query) {
            $query->where('enabled', $value);
        });

        $enumCategoriesFilterRequest->whenFilled('created_at', function ($value) use ($query) {
            $query->whereDate(DB::raw('created_at::date'), date('Y-m-d', strtotime($value)));
        });

        $query->orderByDesc('id');

        return $query->paginate(PageSizeHelper::getPageSize($enumCategoriesFilterRequest));
    }
}
