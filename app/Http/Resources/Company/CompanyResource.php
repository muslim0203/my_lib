<?php

namespace App\Http\Resources\Company;

use App\Core\Helpers\Lang\LanguageHelper;
use App\Models\Company\Company;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Company
 */
class CompanyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->getId(),
            'title' => $this->{LanguageHelper::getTitle()},
            'content' => $this->{LanguageHelper::getContent()},
            'email' => $this->email,
            'phone' => $this->phone,
            'company_file' => $this->companyFile?->file?->path . '/' . $this->companyFile?->file?->file_name,
            'company_file_name' => $this->companyFile?->file_name,
            'company_file_title' => $this->companyFile?->{LanguageHelper::getTitle()}
        ];
    }
}
