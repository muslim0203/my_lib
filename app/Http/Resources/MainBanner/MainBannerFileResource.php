<?php

namespace App\Http\Resources\MainBanner;

use App\Http\Resources\FileManager\FileViewResource;
use App\Models\MainBanner\MainBannerFiles;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin MainBannerFiles
 */
class MainBannerFileResource extends JsonResource
{
    public function toArray(Request $request): array|FileViewResource
    {
        return new FileViewResource($this->file);
    }
}
