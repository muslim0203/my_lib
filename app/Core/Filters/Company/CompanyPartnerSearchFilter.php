<?php

namespace App\Core\Filters\Company;

use App\Core\Helpers\PageSizeHelper;
use App\Http\Requests\Company\CompanyPartnerFilterRequest;
use App\Models\Company\CompanyPartner;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class CompanyPartnerSearchFilter
{
    /**
     * @param CompanyPartnerFilterRequest $companyPartnerFilterRequest
     * @return LengthAwarePaginator
     */
    public static function search(CompanyPartnerFilterRequest $companyPartnerFilterRequest): LengthAwarePaginator
    {
        $query = CompanyPartner::query();

        $companyPartnerFilterRequest->whenFilled('name_uz', function ($value) use ($query) {
            $query->where('name_uz', 'ilike', '%' . $value . '%');
        });

        $companyPartnerFilterRequest->whenFilled('name_oz', function ($value) use ($query) {
            $query->where('name_oz', 'ilike', '%' . $value . '%');
        });

        $companyPartnerFilterRequest->whenFilled('name_ru', function ($value) use ($query) {
            $query->where('name_ru', 'ilike', '%' . $value . '%');
        });

        $companyPartnerFilterRequest->whenFilled('enabled', function ($value) use ($query) {
            $query->where('enabled', $value);
        });

        $companyPartnerFilterRequest->whenFilled('created_at', function ($value) use ($query) {
            $query->whereDate(DB::raw('created_at::date'), date('Y-m-d', strtotime($value)));
        });

        $query->orderByDesc('id');

        return $query->paginate(PageSizeHelper::getPageSize($companyPartnerFilterRequest));
    }
}
