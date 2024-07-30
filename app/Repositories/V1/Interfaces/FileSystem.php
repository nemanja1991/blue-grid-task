<?php 

namespace App\Repositories\V1\Interfaces;

interface FileSystem
{
    public function store();
    public function getDirectoryFiles();
    public function getDirectories();
    public function getFiles();    
}