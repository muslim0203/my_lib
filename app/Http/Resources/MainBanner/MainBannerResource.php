<?php

namespace App\Http\Resources\MainBanner;

use App\Core\Helpers\Lang\LanguageHelper;
use App\Core\Repository\MainBanner\MainBannerFileRepository;
use App\Models\MainBanner\MainBanner;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin MainBanner
 */
class MainBannerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /**
         * @var MainBannerFileRepository $mainBannerFileRepository
         */
        $mainBannerFileRepository = app(MainBannerFileRepository::class);
        return [
            'id' => $this->getId(),
            'name' => $this->{LanguageHelper::getName()},
            'content' => $this->{LanguageHelper::getContent()},
            'link' => $this->link,
            'is_view_content' => $this->is_view_content,
            'mainBannerFiles' => MainBannerFileResource::collection($mainBannerFileRepository->findBannerFileList($this->getId())),
        ];
    }
}
