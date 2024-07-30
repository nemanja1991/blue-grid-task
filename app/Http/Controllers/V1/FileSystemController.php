<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Services\FileSystemService;

class FileSystemController extends Controller
{
    public function __construct(protected FileSystemService $fileSystem) {}

    public function getApiData()
    {
        $fileSystem = $this->fileSystem->getFileSystemData();
    }

    public function getDirectories()
    {
        $directories = $this->fileSystem->getDirectories();
    }

    public function getFiles()
    {
        $files = $this->fileSystem->getFiles();
    }
}