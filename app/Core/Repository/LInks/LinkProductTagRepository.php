<?php

namespace App\Core\Repository\LInks;

use App\Models\Links\LinkProductTag;

class LinkProductTagRepository
{
    /**
     * @param LinkProductTag $linkProductTag
     * @param array $options
     * @return void
     */
    public function save(LinkProductTag $linkProductTag, array $options = []): void
    {
        if (!$linkProductTag->save($options)) {
            throw new \RuntimeException(__('client.Save error link product tag.'));
        }
    }
}
