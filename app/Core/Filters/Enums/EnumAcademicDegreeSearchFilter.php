<?php

namespace App\Core\Filters\Enums;

use App\Core\Helpers\PageSizeHelper;
use App\Http\Requests\Enums\EnumAcademicDegreeFilterRequest;
use App\Http\Requests\Enums\EnumProductTagFilterRequest;
use App\Models\Enums\EnumAcademicDegree;
use App\Models\Enums\EnumProductTag;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class EnumAcademicDegreeSearchFilter
{
    /**
     * @param EnumAcademicDegreeFilterRequest $enumAcademicDegreeFilterRequest
     * @return LengthAwarePaginator
     */
    public static function search(EnumAcademicDegreeFilterRequest $enumAcademicDegreeFilterRequest): LengthAwarePaginator
    {
        $query = EnumAcademicDegree::query();

        $enumAcademicDegreeFilterRequest->whenFilled('name_uz', function ($value) use ($query) {
            $query->where('name_uz', 'ilike', '%' . $value . '%');
        });

        $enumAcademicDegreeFilterRequest->whenFilled('name_oz', function ($value) use ($query) {
            $query->where('name_oz', 'ilike', '%' . $value . '%');
        });

        $enumAcademicDegreeFilterRequest->whenFilled('name_ru', function ($value) use ($query) {
            $query->where('name_ru', 'ilike', '%' . $value . '%');
        });

        $enumAcademicDegreeFilterRequest->whenFilled('enabled', function ($value) use ($query) {
            $query->where('enabled', $value);
        });

        $enumAcademicDegreeFilterRequest->whenFilled('created_at', function ($value) use ($query) {
            $query->whereDate(DB::raw('created_at::date'), date('Y-m-d', strtotime($value)));
        });

        $query->orderByDesc('id');

        return $query->paginate(PageSizeHelper::getPageSize($enumAcademicDegreeFilterRequest));
    }
}
