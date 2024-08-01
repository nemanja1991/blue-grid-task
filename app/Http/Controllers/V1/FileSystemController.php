<?php

namespace App\Http\Controllers\V1;

use App\Models\File;
use App\Jobs\FetchData;
use App\Models\Directory;
use App\Trait\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Http\Resources\FilesResource;
use Illuminate\Support\Facades\Config;
use App\Http\Resources\DirectoryResource;
use App\Http\Resources\FilesDirectoryResource;
use Illuminate\Support\Facades\Cache;

class FileSystemController extends Controller
{
    use JsonResponse;

    protected $commonFileExtensions = [
        'txt', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'jpg', 'jpeg', 'png', 'gif', 'zip', 'tar', 'gz', '7z', 'rar', 'db', 'dat', 'exe', 'enc', 'bat', 'so', 'ini', 'aspx',
        'mp3', 'wav', 'ogg', 'mp4', 'avi', 'mkv', 'mov', 'html', 'htm', 'css', 'js', 'json', 'xml', 'csv', 'sql', 'vir', 'run', 'dll', 'jar', 'sh', 'tmpl', 'yml', 'config',
        'pyd', 'cab', 'ico', 'py', 'lib', 'h', 'pdb', 'conf', 'dtd', 'rdb', 'cmd', 'cfg', 'idl', 'lock', 'log', 'p12', 'frm', 'ibd'
    ];
    
    public function getApiData()
    {
        // $directories = Cache::get('directories');

        // if(!$directories) {
        //     FetchData::dispatch();

        //     $directories = Directory::with('children', 'children.children', 'children.files', 'files')->get();

        //     Cache::put('directories', $directories, 60);
        // }
        
        // return response()->json([
        //     'data' =>FilesDirectoryResource::collection($directories)
        // ]);

        $directories = Cache::get('directories');

        if(!$directories) {
            $url = 'https://rest-test-eight.vercel.app/api/test'; //Config::get('vercel_api_url');

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->get($url);
    
            $items = json_decode($response->body());
    
            foreach($items->items as $item){
                if(isset($item->fileUrl)){
                    $this->prepareUrl($item->fileUrl);
                }
            }
            
            Directory::with('children', 'children.children', 'children.files', 'files')
            ->chunk(50, function ($directories) {
                Cache::forever('directories', $directories);
            });
        }
       
        $directories = Directory::with('children', 'children.children', 'children.files', 'files')->get();

        return response()->json([
            'data' =>FilesDirectoryResource::collection($directories)
        ]);
    }

    public function prepareUrl($url)
    {
        ini_set('max_execution_time', 300);
        $parsedUrl = parse_url($url);
        $path = $parsedUrl['path'];
        $segments = explode('/', trim($path, '/'));
        $ipAddress = $parsedUrl['host'];
        $fileName = null;

        $currentParentId = null;

        foreach($segments as $segment)
        {
            if($this->checkIsFile($segment)) {
                $fileName = $segment;
                break;
            }

            $directory =  Directory::firstOrCreate([
                'ip_address' => $ipAddress,	
                'directory' => $segment,
                'parent_id' => $currentParentId
            ]);

            $currentParentId = $directory->id;
        }

        if(!empty($fileName) && $currentParentId)
        {

            $encoding = mb_detect_encoding($fileName, 'UTF-8, ISO-8859-1, ASCII', true);
            $convertedText = mb_convert_encoding($fileName, 'UTF-8', $encoding);

            File::create([
                'directory_id' => $currentParentId,
                'file_name' => $convertedText
            ]);
        }
    }

    public function checkIsFile($url)
    {
        $pathInfo = pathinfo($url);
        $extension = $pathInfo['extension'] ?? '';

        return in_array($extension, $this->commonFileExtensions);
    }

    public function getDirectories()
    {
        $directories = Directory::paginate(100);

        return $this->paginatedResponse($directories, DirectoryResource::class);
    }

    public function getFiles()
    {
        $files = File::paginate(100);

        return $this->paginatedResponse($files, FilesResource::class);
    }
}