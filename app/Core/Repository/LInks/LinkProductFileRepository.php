<?php

namespace App\Core\Repository\LInks;

use App\Models\Links\LinkProductFiles;

class LinkProductFileRepository
{
    /**
     * @param LinkProductFiles $linkProductFile
     * @param array $options
     * @return void
     */
    public function save(LinkProductFiles $linkProductFile, array $options = []): void
    {
        if (!$linkProductFile->save($options)) {
            throw new \RuntimeException(__('client.Save error'));
        }
    }

    public function exists(int $product_id): bool
    {
        return LinkProductFiles::query()
            ->where('product_id', $product_id)
            ->exists();
    }

    public function findProductFileList(int $product_id)
    {
        return LinkProductFiles::query()
            ->where('product_id', $product_id)
            ->where('enabled', true)
            ->get();
    }
}
