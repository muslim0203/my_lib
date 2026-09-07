<?php

namespace App\Core\Repository\LInks;

use App\Models\Links\LinkAuthorFile;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class LinkAuthorFilesRepository
{
    /**
     * @param LinkAuthorFile $linkAuthorFile
     * @return void
     */
    public function save(LinkAuthorFile $linkAuthorFile): void
    {
        if (!$linkAuthorFile->save()) {
            throw new \RuntimeException(__('client.Save error link author files'));
        }
    }

    /**
     * @param int $author_id
     * @param int $file_id
     * @return Builder|LinkAuthorFile
     */
    public function get(int $author_id, int $file_id): LinkAuthorFile|Builder
    {
        return LinkAuthorFile::query()
            ->where('author_id', $author_id)
            ->where('file_id', $file_id)
            ->where('enabled', true)
            ->firstOrFail();
    }

    /**
     * @param int $author_id
     * @return Builder[]|Collection<int, LinkAuthorFile>
     */
    public function findAllByAuthorId(int $author_id): Collection|array
    {
        return LinkAuthorFile::query()
            ->where('author_id', $author_id)
            ->get();
    }
}
