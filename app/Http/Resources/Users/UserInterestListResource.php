<?php

namespace App\Http\Resources\Users;

use App\Core\Helpers\Lang\LanguageHelper;
use App\Models\Users\UserInterest;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin UserInterest
 */
class UserInterestListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $model = $this->model;

        return [
            'id' => $model->id,
            'model_type' => $model->getTable(),
            'name' => $model->{LanguageHelper::getName()}
        ];
    }
}
