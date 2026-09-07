<?php

namespace App\Core\Filters\Enums;

use App\Core\Helpers\PageSizeHelper;
use App\Http\Requests\Enums\EnumProductTagFilterRequest;
use App\Http\Requests\Enums\EnumProductTypeFilterRequest;
use App\Models\Enums\EnumProductType;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class EnumProductTypeSearchFilter
{
    /**
     * @param EnumProductTypeFilterRequest $enumProductTypeFilterRequest
     * @return LengthAwarePaginator
     */
    public static function search(EnumProductTypeFilterRequest $enumProductTypeFilterRequest): LengthAwarePaginator
    {
        $query = EnumProductType::query();

        $enumProductTypeFilterRequest->whenFilled('name_uz', function ($value) use ($query) {
            $query->where('name_uz', 'ilike', '%' . $value . '%');
        });

        $enumProductTypeFilterRequest->whenFilled('name_oz', function ($value) use ($query) {
            $query->where('name_oz', 'ilike', '%' . $value . '%');
        });

        $enumProductTypeFilterRequest->whenFilled('name_ru', function ($value) use ($query) {
            $query->where('name_ru', 'ilike', '%' . $value . '%');
        });

        $enumProductTypeFilterRequest->whenFilled('enabled', function ($value) use ($query) {
            $query->where('enabled', $value);
        });

        $enumProductTypeFilterRequest->whenFilled('created_at', function ($value) use ($query) {
            $query->whereDate(DB::raw('created_at::date'), date('Y-m-d', strtotime($value)));
        });

        $query->orderByDesc('id');

        return $query->paginate(PageSizeHelper::getPageSize($enumProductTypeFilterRequest));
    }
}
