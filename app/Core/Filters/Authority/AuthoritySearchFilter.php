<?php

namespace App\Core\Filters\Authority;

use App\Core\Helpers\PageSizeHelper;
use App\Http\Requests\Authority\AuthoritySearchFilterRequest;
use App\Models\Authority\Authority;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AuthoritySearchFilter
{
    /**
     * @param AuthoritySearchFilterRequest $authoritySearchFilterRequest
     * @return LengthAwarePaginator
     */
    public static function search(AuthoritySearchFilterRequest $authoritySearchFilterRequest): LengthAwarePaginator
    {
        $query = Authority::query();

        return $query->paginate(PageSizeHelper::getPageSize($authoritySearchFilterRequest));
    }
}
