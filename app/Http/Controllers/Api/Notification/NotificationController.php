<?php

namespace App\Http\Controllers\Api\Notification;

use App\Core\Filters\Notifications\NotificationSearchFilter;
use App\Http\Requests\Notifications\NotificationByAuthorSearchRequest;
use App\Http\Resources\Notification\NotificationListResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;

class NotificationController extends Controller
{
    /**
     * @param NotificationByAuthorSearchRequest $notificationByAuthorSearchRequest
     * @return AnonymousResourceCollection
     */
    public function list(
        NotificationByAuthorSearchRequest $notificationByAuthorSearchRequest
    ): AnonymousResourceCollection
    {
        return NotificationListResource::collection(NotificationSearchFilter::search($notificationByAuthorSearchRequest));
    }
}
