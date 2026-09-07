<?php

namespace App\Core\Filters\Company;

use App\Core\Helpers\PageSizeHelper;
use App\Http\Requests\Company\CompanySocialNetworkFilterRequest;
use App\Models\Company\CompanySocialNetwork;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class CompanySocialNetworkSearchFilter
{
    /**
     * @param CompanySocialNetworkFilterRequest $companySocialNetworkFilterRequest
     * @return LengthAwarePaginator
     */
    public static function search(CompanySocialNetworkFilterRequest $companySocialNetworkFilterRequest): LengthAwarePaginator
    {
        $query = CompanySocialNetwork::query();

        $companySocialNetworkFilterRequest->whenFilled('name_uz', function ($value) use ($query) {
            $query->where('name_uz', 'ilike', '%' . $value . '%');
        });

        $companySocialNetworkFilterRequest->whenFilled('name_oz', function ($value) use ($query) {
            $query->where('name_oz', 'ilike', '%' . $value . '%');
        });

        $companySocialNetworkFilterRequest->whenFilled('name_ru', function ($value) use ($query) {
            $query->where('name_ru', 'ilike', '%' . $value . '%');
        });

        $companySocialNetworkFilterRequest->whenFilled('enabled', function ($value) use ($query) {
            $query->where('enabled', $value);
        });

        $companySocialNetworkFilterRequest->whenFilled('created_at', function ($value) use ($query) {
            $query->whereDate(DB::raw('created_at::date'), date('Y-m-d', strtotime($value)));
        });

        $query->orderByDesc('id');

        return $query->paginate(PageSizeHelper::getPageSize($companySocialNetworkFilterRequest));
    }
}
