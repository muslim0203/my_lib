<?php

namespace App\Core\Services\MainBanner;

use App\Core\Helpers\Transaction;
use App\Core\Repository\MainBanner\MainBannerFileRepository;
use App\Core\Repository\MainBanner\MainBannerRepository;
use App\Core\Services\FileManager\Interface\FileManagerInterface;
use App\Models\MainBanner\MainBanner;
use App\Models\MainBanner\MainBannerFiles;

class MainBannerService
{
    public function __construct(
        protected MainBannerRepository     $mainBannerRepository,
        protected MainBannerFileRepository $bannerFileRepository,
        protected Transaction              $transaction,
        protected FileManagerInterface     $fileManagerService
    )
    {
    }

    public function create(MainBanner $model,$files)
    {
        return $this->transaction->wrap(function () use ($model,$files) {

            $this->mainBannerRepository->save($model);
            if(!empty($files)) {
                foreach ($files as $item) {
                    $file = $this->fileManagerService->image($item);
                    MainBannerFiles::create([
                        'main_banner_id' => $model->id,
                        'file_id' => $file->id
                    ]);
                }
            }
        });
    }


    public function update(MainBanner $model,$files)
    {
        return $this->transaction->wrap(function () use ($model,$files) {

            $this->mainBannerRepository->save($model);

            if(!empty($files)) {

                $this->autoDeleted($model->id);

                foreach ($files as $item) {
                    $file = $this->fileManagerService->image($item);
                    MainBannerFiles::create([
                        'main_banner_id' => $model->id,
                        'file_id' => $file->id
                    ]);
                }
            }
        });
    }

    public function autoDeleted(int $id): void
    {
        foreach (MainBannerFiles::query()->where('main_banner_id', $id)->get() as $item) {
            $item->delete();
        }
    }
}
