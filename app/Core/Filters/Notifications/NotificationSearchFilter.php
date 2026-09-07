<?php

namespace App\Core\Filters\Notifications;

use App\Core\Helpers\PageSizeHelper;
use App\Http\Requests\Notifications\NotificationByAuthorSearchRequest;
use App\Models\Notifications\Notification;
use App\Models\Users\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class NotificationSearchFilter
{
    /**
     * @param NotificationByAuthorSearchRequest $notificationByAuthorSearchRequest
     * @return LengthAwarePaginator
     */
    public static function search(
        NotificationByAuthorSearchRequest $notificationByAuthorSearchRequest
    ): LengthAwarePaginator
    {
        $notification = Notification::query();

        if (!Auth::check()) {
            abort(401, __('client.User is not authentication'));
        }

        /**
         * @var User $user
         */
        $user = Auth::user();

        $notification->where('user_id', $user->getId());
        $notification->orWhereNull('user_id');

        return $notification->paginate(PageSizeHelper::getPageSize($notificationByAuthorSearchRequest));
    }
}
