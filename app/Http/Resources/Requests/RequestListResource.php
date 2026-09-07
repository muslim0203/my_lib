<?php

namespace App\Http\Resources\Requests;

use App\Core\Helpers\Lang\LanguageHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Request\Request
 */
class RequestListResource extends JsonResource
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
            'step' => $this->step?->{LanguageHelper::getName()},
            'status' => $this->status,
            'comment' => $this->getComment(),
            'request_type_id' => $this->getRequestTypeId(),
            'request_type' => $this->requestType?->{LanguageHelper::getName()},
            'created_at' => $this->created_at
        ];
    }
}
