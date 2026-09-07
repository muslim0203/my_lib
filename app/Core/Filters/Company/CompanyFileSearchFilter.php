<?php

namespace App\Core\Filters\Company;

use App\Core\Helpers\PageSizeHelper;
use App\Http\Requests\Company\CompanyFileFilterRequest;
use App\Models\Company\CompanyFile;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class CompanyFileSearchFilter
{
    /**
     * @param CompanyFileFilterRequest $companyFileFilterRequest
     * @return LengthAwarePaginator
     */
    public static function search(CompanyFileFilterRequest $companyFileFilterRequest): LengthAwarePaginator
    {
        $query = CompanyFile::query();

        $companyFileFilterRequest->whenFilled('title_uz', function ($value) use ($query) {
            $query->where('title_uz', 'ilike', '%' . $value . '%');
        });

        $companyFileFilterRequest->whenFilled('title_oz', function ($value) use ($query) {
            $query->where('title_oz', 'ilike', '%' . $value . '%');
        });

        $companyFileFilterRequest->whenFilled('title_ru', function ($value) use ($query) {
            $query->where('title_ru', 'ilike', '%' . $value . '%');
        });

        $companyFileFilterRequest->whenFilled('enabled', function ($value) use ($query) {
            $query->where('enabled', $value);
        });

        $companyFileFilterRequest->whenFilled('created_at', function ($value) use ($query) {
            $query->whereDate(DB::raw('created_at::date'), date('Y-m-d', strtotime($value)));
        });

        $query->orderByDesc('id');

        return $query->paginate(PageSizeHelper::getPageSize($companyFileFilterRequest));
    }
}
