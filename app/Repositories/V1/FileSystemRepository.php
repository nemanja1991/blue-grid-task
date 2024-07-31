<?php

namespace App\Repositories\V1;

use App\Models\File;
use App\Repositories\V1\Interfaces\FileSystem;
use App\Models\Directory;

class FileSystemRepository implements FileSystem
{

    public function storeDirectory($data)
    {
        
    }

    public function storeFile($data)
    {

    }

    public function getDirectoryFiles()
    {

    }

    public function getDirectories()
    {
        return Directory::paginate(100);
    }

    public function getFiles()
    {
        return File::paginate(100);
    }
}