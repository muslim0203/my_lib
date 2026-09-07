<?php

namespace App\Core\Repository\MainBanner;

use App\Models\MainBanner\MainBannerFiles;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class MainBannerFileRepository
{
    public function get(int $id): Builder|MainBannerFiles
    {
        return MainBannerFiles::query()
            ->where('id', $id)
            ->firstOrFail();
    }

    /**
     * @param MainBannerFiles $mainBannerFile
     * @return void
     */
    public function save(MainBannerFiles $mainBannerFile): void
    {
        if (!$mainBannerFile->save()) {
            throw new \RuntimeException(__('client.Main Banner Files save error'));
        }
    }
    public function findOne()
    {
        return MainBannerFiles::query()
            ->limit(1)
            ->get();
    }

    public function findBannerFileList(int $banner_id): Collection|array
    {
        return MainBannerFiles::query()
            ->where('main_banner_id', $banner_id)
            ->get();
    }
}
