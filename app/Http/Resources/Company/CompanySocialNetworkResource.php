<?php

namespace App\Http\Resources\Company;

use App\Core\Helpers\Lang\LanguageHelper;
use App\Models\Company\CompanySocialNetwork;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin CompanySocialNetwork
 */
class CompanySocialNetworkResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->{LanguageHelper::getName()},
            'link' => $this->link,
            'logo' => $this->logo->path .'/' . $this->logo->file_name
        ];
    }
}
