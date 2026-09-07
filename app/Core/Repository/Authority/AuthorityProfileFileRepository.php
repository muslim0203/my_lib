<?php

namespace App\Core\Repository\Authority;


use App\Models\Authority\AuthorityProfileFile;
use Illuminate\Database\Eloquent\Builder;

class AuthorityProfileFileRepository
{
    /**
     * @param int $fileId
     * @param int $authorityId
     * @return AuthorityProfileFile|Builder|null
     */
    public function findByFileIdAndAuthorityId(int $fileId, int $authorityId): AuthorityProfileFile|Builder|null
    {
        return AuthorityProfileFile::query()
            ->where('file_id', $fileId)
            ->where('authority_id', $authorityId)
            ->first();
    }

    /**
     * @param AuthorityProfileFile $authorityFile
     * @param array $options
     * @return void
     */
    public function save(AuthorityProfileFile $authorityFile, array $options = []): void
    {
        if (!$authorityFile->save($options)) {
            throw new \RuntimeException(__('client.Save error authority file'));
        }
    }
}
