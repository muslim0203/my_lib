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
        $uploadedFile = $file->file('file');

        $hash = hash_file(
            $this->getHash(),
            $uploadedFile->getRealPath()
        );

        if (!empty($file = $this->fileManagerRepository->findByHash($hash))) {
            return new FileViewResource($file, true);
        }

        $fileName = $uploadedFile->hashName();

        if (!Storage::put($this->getPath(), $uploadedFile)) {
            abort(400, __("client.File upload doesn't work"));
        }

        $file = new File();
        $file->setFileName($fileName);
        $file->setHash($hash);
        $file->setOriginalName($uploadedFile->getClientOriginalName());
        $file->setPath('/storage' . $this->getPath());
        $file->setMimeType($uploadedFile->getClientMimeType());
        $file->setExtension($uploadedFile->extension());
        $file->setSize((string)$uploadedFile->getSize());
        $this->fileManagerRepository->save($file);

        return new FileViewResource($file);
    }

    /**
     * @param $uploadedFile
     * @return FileViewResource
     */
    public function image($uploadedFile): FileViewResource
    {
        $hash = hash_file(
            $this->getHash(),
            $uploadedFile->getRealPath()
        );

        if (!empty($file = $this->fileManagerRepository->findByHash($hash))) {
            return new FileViewResource($file, true);
        }

        $fileName = $uploadedFile->hashName();

        if (!Storage::put($this->getPath(), $uploadedFile)) {
            abort(400, __("client.File upload doesn't work"));
        }

        $file = new File();
        $file->setFileName($fileName);
        $file->setHash($hash);
        $file->setOriginalName($uploadedFile->getClientOriginalName());
        $file->setPath('/storage' . $this->getPath());
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
