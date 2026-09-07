<?php

namespace App\Core\Search;

use App\Core\Helpers\PageSizeHelper;
use App\Http\Requests\Report\UserRegisterSearchRequest;
use App\Models\Users\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

class UserRegisterSearch extends Model
{
    /**
     * @param UserRegisterSearchRequest $userRegisterSearchRequest
     * @return LengthAwarePaginator
     */
    public static function search(UserRegisterSearchRequest $userRegisterSearchRequest): LengthAwarePaginator
    {
        $query = User::query()
            ->from('users AS u')
            ->selectRaw(
                "
                CONCAT(f.path,'/',f.file_name) AS picture,
                CONCAT(su.first_name,' ',su.last_name,' ',su.middle_name) AS full_name,
                u.username,
                su.birth_date,
                u.email,
                su.phone,
                su.current_address,
                u.created_at,
                u.status",
            )
            ->leftJoin('social_users AS su', 'u.social_user_id', '=', 'su.id')
            ->leftJoin('files AS f', 'su.file_id', '=', 'f.id');

        $userRegisterSearchRequest->whenFilled('email', function ($value) use ($query) {
            $query->where('u.email', 'ilike', '%' . $value . '%');
        });

        $userRegisterSearchRequest->whenFilled('full_name', function ($value) use ($query) {
            $query->orWhere('su.first_name', 'ilike', '%' . $value . '%')
                ->orWhere('su.last_name', 'ilike', '%' . $value . '%')
                ->orWhere('su.middle_name', 'ilike', '%' . $value . '%');
        });

        $userRegisterSearchRequest->whenFilled('username', function ($value) use ($query) {
            $query->orWhere('u.username', 'ilike', '%' . $value . '%');
        });

        $userRegisterSearchRequest->whenFilled('status', function ($value) use ($query) {
            $query->where('u.status', $value);
        });

        $userRegisterSearchRequest->whenFilled('from_date', function ($value) use ($query) {
            $query->whereDate('u.created_at', '>=', date('Y-m-d', strtotime($value)));
        });
        $userRegisterSearchRequest->whenFilled('to_date', function ($value) use ($query) {
            $query->whereDate('u.created_at', '<=', date('Y-m-d', strtotime($value)));
        });

        $query->orderByDesc('u.id');

        return $query->paginate(PageSizeHelper::getPageSize($userRegisterSearchRequest));
    }
}
