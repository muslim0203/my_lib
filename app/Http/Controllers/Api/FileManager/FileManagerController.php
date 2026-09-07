<?php

namespace App\Http\Controllers\Api\FileManager;

use App\Core\Helpers\Response\Success;
use App\Core\Services\FileManager\Interface\FileManagerInterface;
use App\Http\Requests\FileManager\File;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class FileManagerController extends Controller
{
    public function __construct(
        protected FileManagerInterface $fileManagerService
    )
    {
    }

    /**
     * @param File $file
     * @return JsonResponse
     */
    public function upload(File $file): JsonResponse
    {
        return Success::send('Successful done file uploaded', $this->fileManagerService->upload($file));
    }
}
