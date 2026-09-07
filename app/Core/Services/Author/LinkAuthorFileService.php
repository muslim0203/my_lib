<?php

namespace App\Core\Services\Author;

use App\Core\Repository\LInks\LinkAuthorFilesRepository;
use App\Models\Links\LinkAuthorFile;

class LinkAuthorFileService
{
    public function __construct(
        protected LinkAuthorFilesRepository $linkAuthorFilesRepository
    )
    {
    }

    /**
     * @param int $author_id
     * @param array $extra_files
     * @return void
     * @throws \Throwable
     */
    public function sync(int $author_id, array $extra_files = []): void
    {
        $oldFiles = $this->linkAuthorFilesRepository->findAllByAuthorId($author_id);

        if (!empty($oldFiles)) {
            try {
                foreach ($oldFiles as $oldFile) {
                    $oldFile->deleteOrFail();
                }
            } catch (\Exception $e) {
                throw new \RuntimeException($e->getMessage());
            }
        }

        if (!empty($extra_files)) {

            foreach ($extra_files as $extra_file) {
                $linkAuthorFile = new LinkAuthorFile();
                $linkAuthorFile->setAuthorId($author_id);
                $linkAuthorFile->setFileId($extra_file['file_id']);
                $linkAuthorFile->setFileName($extra_file['file_name']);
                $linkAuthorFile->setFileTypeId($extra_file['file_type_id']);
                $this->linkAuthorFilesRepository->save($linkAuthorFile);
            }
        }
    }
}
