<?php

namespace App\Core\Filters\Request;

use App\Core\Helpers\PageSizeHelper;
use App\Http\Requests\Requests\RequestSearchFilterForm;
use App\Models\Request\Request;
use App\Models\Users\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class RequestSearchFilter
{
    /**
     * @param RequestSearchFilterForm $requestSearchFilterForm
     * @param int|null $type
     * @param bool $isAdmin
     * @return LengthAwarePaginator
     */
    public static function search(RequestSearchFilterForm $requestSearchFilterForm, ?int $type = null, bool $isAdmin = false): LengthAwarePaginator
    {
        $query = Request::query();

        if (!is_null($type)) {
            $query->where('request_type_id', $type);
        }

        $requestSearchFilterForm->whenFilled('status', function ($value) use ($query) {
            $query->where('status', $value);
        });

        $requestSearchFilterForm->whenFilled('request_type_id', function ($value) use ($query) {
            $query->where('request_type_id', $value);
        });

        if (Auth::check()) {
            /**
             * @var User $user
             */
            $user = Auth::user();

            if (!$isAdmin) {
                $query->where('author_id', $user->getId());
            }

        }

        $query->orderByDesc('id');

        return $query->paginate(PageSizeHelper::getPageSize($requestSearchFilterForm));
    }
}
