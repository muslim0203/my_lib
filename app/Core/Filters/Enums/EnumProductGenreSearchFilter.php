<?php

namespace App\Core\Filters\Enums;

use App\Core\Helpers\PageSizeHelper;
use App\Http\Requests\Enums\EnumProductGenreFilterRequest;
use App\Models\Enums\EnumProductGenre;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class EnumProductGenreSearchFilter
{
    /**
     * @param EnumProductGenreFilterRequest $enumProductGenreFilterRequest
     * @return LengthAwarePaginator
     */
    public static function search(EnumProductGenreFilterRequest $enumProductGenreFilterRequest): LengthAwarePaginator
    {
        $query = EnumProductGenre::query();

        $enumProductGenreFilterRequest->whenFilled('name_uz', function ($value) use ($query) {
            $query->where('name_uz', 'ilike', '%' . $value . '%');
        });

        $enumProductGenreFilterRequest->whenFilled('name_oz', function ($value) use ($query) {
            $query->where('name_oz', 'ilike', '%' . $value . '%');
        });

        $enumProductGenreFilterRequest->whenFilled('name_ru', function ($value) use ($query) {
            $query->where('name_ru', 'ilike', '%' . $value . '%');
        });

        $enumProductGenreFilterRequest->whenFilled('enabled', function ($value) use ($query) {
            $query->where('enabled', $value);
        });

        $enumProductGenreFilterRequest->whenFilled('created_at', function ($value) use ($query) {
            $query->whereDate(DB::raw('created_at::date'), date('Y-m-d', strtotime($value)));
        });

        $query->orderByDesc('id');

        return $query->paginate(PageSizeHelper::getPageSize($enumProductGenreFilterRequest));
    }
}
