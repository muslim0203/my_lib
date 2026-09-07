<?php

namespace App\Core\Filters\Company;

use App\Core\Helpers\PageSizeHelper;
use App\Http\Requests\Company\CompanyFilterRequest;
use App\Models\Company\Company;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class CompanySearchFilter
{
    /**
     * @param CompanyFilterRequest $companyFilterRequest
     * @return LengthAwarePaginator
     */
    public static function search(CompanyFilterRequest $companyFilterRequest): LengthAwarePaginator
    {
        $query = Company::query();

        $companyFilterRequest->whenFilled('title_uz', function ($value) use ($query) {
            $query->where('title_uz', 'ilike', '%' . $value . '%');
        });

        $companyFilterRequest->whenFilled('title_oz', function ($value) use ($query) {
            $query->where('title_oz', 'ilike', '%' . $value . '%');
        });

        $companyFilterRequest->whenFilled('title_ru', function ($value) use ($query) {
            $query->where('title_ru', 'ilike', '%' . $value . '%');
        });

        $companyFilterRequest->whenFilled('enabled', function ($value) use ($query) {
            $query->where('enabled', $value);
        });

        $companyFilterRequest->whenFilled('created_at', function ($value) use ($query) {
            $query->whereDate(DB::raw('created_at::date'), date('Y-m-d', strtotime($value)));
        });

        $query->orderByDesc('id');

        return $query->paginate(PageSizeHelper::getPageSize($companyFilterRequest));
    }
}
