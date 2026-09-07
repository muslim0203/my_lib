<?php

namespace App\Core\Filters\Enums;

use App\Core\Helpers\PageSizeHelper;
use App\Http\Requests\Enums\EnumAcademicPositionFilterRequest;
use App\Models\Enums\EnumAcademicPosition;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class EnumAcademicPositionSearchFilter
{
    /**
     * @param EnumAcademicPositionFilterRequest $enumAcademicPositionFilterRequest
     * @return LengthAwarePaginator
     */
    public static function search(EnumAcademicPositionFilterRequest $enumAcademicPositionFilterRequest): LengthAwarePaginator
    {
        $query = EnumAcademicPosition::query();

        $enumAcademicPositionFilterRequest->whenFilled('name_uz', function ($value) use ($query) {
            $query->where('name_uz', 'ilike', '%' . $value . '%');
        });

        $enumAcademicPositionFilterRequest->whenFilled('name_oz', function ($value) use ($query) {
            $query->where('name_oz', 'ilike', '%' . $value . '%');
        });

        $enumAcademicPositionFilterRequest->whenFilled('name_ru', function ($value) use ($query) {
            $query->where('name_ru', 'ilike', '%' . $value . '%');
        });

        $enumAcademicPositionFilterRequest->whenFilled('enabled', function ($value) use ($query) {
            $query->where('enabled', $value);
        });

        $enumAcademicPositionFilterRequest->whenFilled('created_at', function ($value) use ($query) {
            $query->whereDate(DB::raw('created_at::date'), date('Y-m-d', strtotime($value)));
        });

        $query->orderByDesc('id');

        return $query->paginate(PageSizeHelper::getPageSize($enumAcademicPositionFilterRequest));
    }
}
