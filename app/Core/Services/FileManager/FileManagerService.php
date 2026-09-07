<?php

namespace App\Core\Services\FileManager;

use App\Core\Repository\FileManager\FileManagerRepository;
use App\Core\Services\FileManager\Interface\FileManagerInterface;
use App\Http\Resources\FileManager\FileViewResource;
use App\Models\Files\File;
use Illuminate\Support\Facades\Storage;

class FileManagerService implements FileManagerInterface
{
    public function __construct(
        protected FileManagerRepository $fileManagerRepository,
        protected ?string               $path,
        protected ?string               $hash
    )
    {
    }

    /**
     * @param \App\Http\Requests\FileManager\File $file
     * @return FileViewResource
     */
    public function upload(\App\Http\Requests\FileManager\File $file): FileViewResource
    {
        return $this->store($file->file('file'));
    }

    /**
     * @param $uploadedFile
     * @return FileViewResource
     */
    public function image($uploadedFile): FileViewResource
    {
        return $this->store($uploadedFile);
    }

    /**
     * Faylni maxfiy diskka yozadi va yozuvini saqlaydi.
     *
     * Fayllar web root'dan tashqarida turadi; havola har doim
     * avtorizatsiya qiluvchi `file-view` marshrutiga ishora qiladi.
     *
     * @param \Illuminate\Http\UploadedFile $uploadedFile
     * @return FileViewResource
     */
    protected function store($uploadedFile): FileViewResource
    {
        $hash = hash_file(
            $this->getHash(),
            $uploadedFile->getRealPath()
        );

        if (!empty($existing = $this->fileManagerRepository->findByHash($hash))) {
            return new FileViewResource($existing, true);
        }

        $fileName = $uploadedFile->hashName();

        $stored = Storage::disk(config('filesystems.upload_disk'))
            ->putFileAs($this->getPath(), $uploadedFile, $fileName);

        if ($stored === false) {
            abort(400, __("client.File upload doesn't work"));
        }

        $file = new File();
        $file->setFileName($fileName);
        $file->setHash($hash);
        $file->setOriginalName($uploadedFile->getClientOriginalName());
        $file->setPath(rtrim((string)config('filesystems.public_url_prefix'), '/'));
        $file->setMimeType($uploadedFile->getClientMimeType());
        $file->setExtension($uploadedFile->extension());
        $file->setSize((string)$uploadedFile->getSize());
        $this->fileManagerRepository->save($file);

        return new FileViewResource($file);
    }

    public function getHash(): string
    {
        return $this->hash;
    }

    public function setHash(?string $hash): void
    {
        $this->hash = $hash ?? config('filesystems.hash');
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function setPath(?string $path): void
    {
        $this->path = $path ?? config('filesystems.upload_path');
    }
}
