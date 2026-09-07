<?php

namespace App\Core\Filters\Enums;

use App\Core\Helpers\PageSizeHelper;
use App\Http\Requests\Enums\EnumEducationTypeFilterRequest;
use App\Models\Enums\EnumEducationType;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class EnumEducationTypeSearchFilter
{
    /**
     * @param EnumEducationTypeFilterRequest $educationTypeFilterRequest
     * @return LengthAwarePaginator
     */
    public static function search(EnumEducationTypeFilterRequest $educationTypeFilterRequest): LengthAwarePaginator
    {
        $query = EnumEducationType::query();

        $educationTypeFilterRequest->whenFilled('name_uz', function ($value) use ($query) {
            $query->where('name_uz', 'ilike', '%' . $value . '%');
        });

        $educationTypeFilterRequest->whenFilled('name_oz', function ($value) use ($query) {
            $query->where('name_oz', 'ilike', '%' . $value . '%');
        });

        $educationTypeFilterRequest->whenFilled('name_ru', function ($value) use ($query) {
            $query->where('name_ru', 'ilike', '%' . $value . '%');
        });

        $educationTypeFilterRequest->whenFilled('enabled', function ($value) use ($query) {
            $query->where('enabled', $value);
        });

        $educationTypeFilterRequest->whenFilled('created_at', function ($value) use ($query) {
            $query->whereDate(DB::raw('created_at::date'), date('Y-m-d', strtotime($value)));
        });

        $query->orderByDesc('id');

        return $query->paginate(PageSizeHelper::getPageSize($educationTypeFilterRequest));
    }
}
