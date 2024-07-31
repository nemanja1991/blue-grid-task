<?php 

namespace App\Repositories\V1\Interfaces;

interface FileSystem
{
    public function storeDirectory($data);
    public function storeFile($data);
    public function getDirectoryFiles();
    public function getDirectories();
    public function getFiles();    
}