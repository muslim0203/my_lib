<?php

namespace App\Http\Resources\Notification;

use App\Core\Helpers\Lang\LanguageHelper;
use App\Models\Notifications\Notification;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Notification
 */
class NotificationListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->getId(),
            'message' => $this->{LanguageHelper::getMessage()},
            'created_at' => $this->getCreatedAt(),
            'enabled' => $this->isEnabled()
        ];
    }
}
