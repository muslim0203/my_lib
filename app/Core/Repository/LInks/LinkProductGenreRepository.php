<?php

namespace App\Core\Repository\LInks;

use App\Models\Links\LinkProductGenre;

class LinkProductGenreRepository
{
    public function save(LinkProductGenre $linkProductGenre, array $options = [])
    {
        if (!$linkProductGenre->save($options)) {
            throw new \RuntimeException(__('client.Save error link product genre.'));
        }
    }

    /**
     * @param array $genreIds
     * @param int $productId
     * @return array
     */
    public function findAllByIds(array $genreIds, int $productId): array
    {
        return LinkProductGenre::query()
            ->whereIn('genre_id', $genreIds)
            ->whereNot('product_id', $productId)
            ->get()
            ->all();
    }
}
