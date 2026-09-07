<?php

namespace App\Core\Filters\MainBanner;

use App\Core\Helpers\PageSizeHelper;
use App\Http\Requests\Company\CompanyFilterRequest;
use App\Http\Requests\MainBanner\MainBannerFilterRequest;
use App\Models\Company\Company;
use App\Models\MainBanner\MainBanner;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class MainBannerSearchFilter
{
    /**
     * @param MainBannerFilterRequest $mainBannerFilterRequest
     * @return LengthAwarePaginator
     */
    public static function search(MainBannerFilterRequest $mainBannerFilterRequest): LengthAwarePaginator
    {
        $query = MainBanner::query();

        $mainBannerFilterRequest->whenFilled('name_uz', function ($value) use ($query) {
            $query->where('name_uz', 'ilike', '%' . $value . '%');
        });

        $mainBannerFilterRequest->whenFilled('name_oz', function ($value) use ($query) {
            $query->where('name_oz', 'ilike', '%' . $value . '%');
        });

        $mainBannerFilterRequest->whenFilled('name_uz', function ($value) use ($query) {
            $query->where('name_uz', 'ilike', '%' . $value . '%');
        });

        $mainBannerFilterRequest->whenFilled('enabled', function ($value) use ($query) {
            $query->where('enabled', $value);
        });

        $mainBannerFilterRequest->whenFilled('created_at', function ($value) use ($query) {
            $query->whereDate(DB::raw('created_at::date'), date('Y-m-d', strtotime($value)));
        });

        $query->orderByDesc('id');

        return $query->paginate(PageSizeHelper::getPageSize($mainBannerFilterRequest));
    }
}
