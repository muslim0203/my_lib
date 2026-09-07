<?php

namespace App\Core\Filters\Author;

use App\Core\Helpers\PageSizeHelper;
use App\Models\Authors\AuthorComments;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Foundation\Http\FormRequest;

class AuthorCommentSearchFilter
{
    /**
     * @param FormRequest $formRequest
     * @param int $author_id
     * @return LengthAwarePaginator
     */
    public static function search(FormRequest $formRequest, int $author_id): LengthAwarePaginator
    {
        $query = AuthorComments::query()
            ->whereNull('parent_id');

        $query->where('author_id', $author_id)
            ->where('enabled', '=',true);

        return $query->paginate(PageSizeHelper::getPageSize($formRequest));
    }
}
