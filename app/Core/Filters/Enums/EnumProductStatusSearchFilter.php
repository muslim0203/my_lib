<?php

namespace App\Core\Filters\Enums;

use App\Core\Helpers\PageSizeHelper;
use App\Http\Requests\Enums\EnumProductStatusFilterRequest;
use App\Models\Enums\EnumProductStatus;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class EnumProductStatusSearchFilter
{
    /**
     * @param EnumProductStatusFilterRequest $enumProductStatusFilterRequest
     * @return LengthAwarePaginator
     */
    public static function search(EnumProductStatusFilterRequest $enumProductStatusFilterRequest): LengthAwarePaginator
    {
        $query = EnumProductStatus::query();

        $enumProductStatusFilterRequest->whenFilled('name_uz', function ($value) use ($query) {
            $query->where('name_uz', 'ilike', '%' . $value . '%');
        });

        $enumProductStatusFilterRequest->whenFilled('name_oz', function ($value) use ($query) {
            $query->where('name_oz', 'ilike', '%' . $value . '%');
        });

        $enumProductStatusFilterRequest->whenFilled('name_ru', function ($value) use ($query) {
            $query->where('name_ru', 'ilike', '%' . $value . '%');
        });

        $enumProductStatusFilterRequest->whenFilled('enabled', function ($value) use ($query) {
            $query->where('enabled', $value);
        });

        $enumProductStatusFilterRequest->whenFilled('created_at', function ($value) use ($query) {
            $query->whereDate(DB::raw('created_at::date'), date('Y-m-d', strtotime($value)));
        });

        $query->orderByDesc('id');

        return $query->paginate(PageSizeHelper::getPageSize($enumProductStatusFilterRequest));
    }
}
