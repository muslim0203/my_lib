<?php

namespace App\Core\Services\Authority;

use App\Core\Repository\Authority\AuthorityProfileFileRepository;
use App\Core\Repository\Authority\AuthorityRepository;
use App\Core\Services\Authority\Contract\AuthorityProfileFile;

class AuthorityProfileFileService implements AuthorityProfileFile
{
    public function __construct(
        protected AuthorityProfileFileRepository $authorityProfileFileRepository,
        protected AuthorityRepository            $authorityRepository
    )
    {
    }

    /**
     * @param int $file_id
     * @param int $authority_id
     * @param string $file_name
     * @return void
     */
    public function sync(int $file_id, int $authority_id, string $file_name): void
    {
        $model = $this->authorityProfileFileRepository->findByFileIdAndAuthorityId($file_id, $authority_id);
        $authority = $this->authorityRepository->getById($authority_id);

        if (!empty($model)) {

            $model->setEnabled(true);
            $authority->setProfileFileId($model->getId());

            $this->authorityProfileFileRepository->save($model);
            $this->authorityRepository->save($authority);

        } else {

            $model = new \App\Models\Authority\AuthorityProfileFile();
            $model->setFileId($file_id);
            $model->setAuthorityId($authority_id);
            $model->setFileName($file_name);
            $this->authorityProfileFileRepository->save($model);

            $authority->setProfileFileId($model->getId());
            $this->authorityRepository->save($authority);

        }
    }
}
