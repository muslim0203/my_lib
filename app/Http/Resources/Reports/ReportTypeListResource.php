<?php

namespace App\Http\Resources\Reports;

use App\Core\Helpers\Lang\LanguageHelper;
use App\Models\Reports\ReportType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin ReportType
 */
class ReportTypeListResource extends JsonResource
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
            'title' => $this->{LanguageHelper::getTitle()}
        ];
    }
}
