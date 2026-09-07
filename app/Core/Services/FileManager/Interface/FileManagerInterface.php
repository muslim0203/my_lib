<?php

namespace App\Core\Services\FileManager\Interface;

use App\Http\Requests\FileManager\File;

interface FileManagerInterface
{
    public function upload(File $file);
    public function image($uploadedFile);
}
