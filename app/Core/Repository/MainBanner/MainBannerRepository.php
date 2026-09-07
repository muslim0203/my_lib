<?php

namespace App\Core\Repository\MainBanner;

use App\Core\Helpers\Lang\LanguageHelper;
use App\Models\MainBanner\MainBanner;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class MainBannerRepository
{
    public function get(int $id): Builder|MainBanner
    {
        return MainBanner::query()
            ->where('id', $id)
            ->firstOrFail();
    }

    /**
     * @param MainBanner $mainBanner
     * @return void
     */
    public function save(MainBanner $mainBanner): void
    {
        if (!$mainBanner->save()) {
            throw new \RuntimeException(__('client.Main Banner save error'));
        }
    }

    public function findAll(): Collection|array
    {
        return MainBanner::query()
            ->select(['id', LanguageHelper::getName() . ' AS name',LanguageHelper::getContent() . ' AS content'])
            ->where(['enabled' => true])
            ->get();
    }

    public function findOne()
    {
        return MainBanner::query()
            ->where(['enabled' => true])
            ->orderBy('updated_at', 'desc')
            ->limit(1)
            ->get();
    }
}
