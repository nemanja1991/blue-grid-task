<?php 

namespace App\Http\Services;

use Illuminate\Support\Facades\Http;

class FileSystemService
{
    public function getFileSystem()
    {
        $response = Http::get('https://rest-test-eight.vercel.app/api/test');
    }

    public function getDirectories()
    {

    }

    public function getFiles()
    {

    }
}