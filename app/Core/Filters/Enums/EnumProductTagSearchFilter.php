<?php

namespace App\Core\Filters\Enums;

use App\Core\Helpers\PageSizeHelper;
use App\Http\Requests\Enums\EnumProductTagFilterRequest;
use App\Models\Enums\EnumProductTag;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class EnumProductTagSearchFilter
{
    /**
     * @param EnumProductTagFilterRequest $enumProductTagFilterRequest
     * @return LengthAwarePaginator
     */
    public static function search(EnumProductTagFilterRequest $enumProductTagFilterRequest): LengthAwarePaginator
    {
        $query = EnumProductTag::query();

        $enumProductTagFilterRequest->whenFilled('name_uz', function ($value) use ($query) {
            $query->where('name_uz', 'ilike', '%' . $value . '%');
        });

        $enumProductTagFilterRequest->whenFilled('name_oz', function ($value) use ($query) {
            $query->where('name_oz', 'ilike', '%' . $value . '%');
        });

        $enumProductTagFilterRequest->whenFilled('name_ru', function ($value) use ($query) {
            $query->where('name_ru', 'ilike', '%' . $value . '%');
        });

        $enumProductTagFilterRequest->whenFilled('enabled', function ($value) use ($query) {
            $query->where('enabled', $value);
        });

        $enumProductTagFilterRequest->whenFilled('created_at', function ($value) use ($query) {
            $query->whereDate(DB::raw('created_at::date'), date('Y-m-d', strtotime($value)));
        });

        $query->orderByDesc('id');

        return $query->paginate(PageSizeHelper::getPageSize($enumProductTagFilterRequest));
    }
}
