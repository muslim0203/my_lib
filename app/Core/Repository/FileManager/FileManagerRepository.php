<?php

namespace App\Core\Repository\FileManager;

use App\Models\Files\File;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\HigherOrderBuilderProxy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HigherOrderCollectionProxy;

class FileManagerRepository
{
    /**
     * @param int $id
     * @return File|Model
     */
    public function getById(int $id): Model|File
    {
        return File::query()
            ->where('id', $id)
            ->firstOrFail();
    }

    /**
     * @param string $hash
     * @return Builder|File|null
     */
    public function findByHash(string $hash): File|Builder|null
    {
        return File::query()
            ->where('hash', $hash)
            ->first();
    }

    /**
     * @param File $file
     * @return void
     */
    public function save(File $file): void
    {
        if (!$file->save()) {
            throw new \RuntimeException(__('client.Save error file'));
        }
    }

    /**
     * @param int $file_id
     * @return HigherOrderBuilderProxy|HigherOrderCollectionProxy|mixed
     */
    public function getFileSource(int $file_id = null): mixed
    {
        if (!empty($file_id)){
            $file = File::query()
                ->select(DB::raw("CONCAT(path,'/',file_name) AS path"))
                ->findOrFail($file_id);
            return $file['path'];
        }
        return null;
    }

}
