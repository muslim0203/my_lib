<?php

namespace App\Http\Resources\Company;

use App\Core\Helpers\Lang\LanguageHelper;
use App\Models\Company\CompanyPartner;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin CompanyPartner
 */
class CompanyPartnerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->{LanguageHelper::getName()},
            'website_link' => $this->website_link,
            'logo' => $this->logo?->path .'/' . $this->logo?->file_name
        ];
    }
}
