<?php

namespace App\Core\Services\Author;

use App\Core\Repository\Author\AuthorProfileFileRepository;
use App\Models\Authors\AuthorProfileFile;
use Illuminate\Foundation\Http\FormRequest;

class AuthorProfileService
{
    public function __construct(
        protected AuthorProfileFileRepository $authorProfileFileRepository
    )
    {
    }

    /**
     * @param int $author_id
     * @param FormRequest $formRequest
     * @return AuthorProfileFile
     */
    public function sync(int $author_id, FormRequest $formRequest): AuthorProfileFile
    {
        $authorProfileFile = $this->authorProfileFileRepository->findByAuthorId($author_id);

        if (
            !empty($authorProfileFile)
            && ($authorProfileFile->getFileId() !== $formRequest->post('file_id'))
        ) {
            $authorProfileFile->setEnabled(false);
            $this->authorProfileFileRepository->save($authorProfileFile);
        }

        $authorProfileFile = new AuthorProfileFile();
        $authorProfileFile->setAuthorId($author_id);
        $authorProfileFile->setFileId((int)$formRequest->post('file_id'));
        $authorProfileFile->setFileName((string)$formRequest->post('file_name'));
        $this->authorProfileFileRepository->save($authorProfileFile);

        return $authorProfileFile;
    }
}
