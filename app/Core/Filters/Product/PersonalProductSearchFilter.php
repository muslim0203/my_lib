<?php

namespace App\Core\Filters\Product;

use App\Core\Helpers\PageSizeHelper;
use App\Models\Products\Product;
use App\Models\Users\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class PersonalProductSearchFilter
{
    /**
     * @param FormRequest $formRequest
     * @return LengthAwarePaginator
     */
    public static function search(FormRequest $formRequest): LengthAwarePaginator
    {
        $query = Product::query();

        /**
         * @var User $user
         */
        $user = Auth::user();

        $query->where('author_id', $user->getId());

        return $query->paginate(PageSizeHelper::getPageSize($formRequest));
    }
}
