<?php 

namespace App\Http\Services;

use App\Repositories\FileSystemRepository;
use Illuminate\Support\Facades\Http;

class FileSystemService
{
    public function __construct( protected FileSystemRepository $fileSystemRepository) {}

    public function getFileSystemData()
    {
        
    }

    public function getDirectories()
    {

    }

    public function getFiles()
    {

    }
}