<?php

namespace App\Core\Filters\Author;

use App\Core\Helpers\PageSizeHelper;
use App\Http\Requests\Authors\AuthorSearchRequest;
use App\Models\Authors\Author;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AuthorSearchFilter
{
    /**
     * @param AuthorSearchRequest $authorSearchRequest
     * @return LengthAwarePaginator
     */
    public static function search(AuthorSearchRequest $authorSearchRequest): LengthAwarePaginator
    {
        $query = Author::query();

        return $query->paginate(PageSizeHelper::getPageSize($authorSearchRequest));
    }
}
