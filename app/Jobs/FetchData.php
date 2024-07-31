<?php

namespace App\Jobs;

use App\Models\File;
use App\Models\Directory;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class FetchData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $commonFileExtensions = [
        'txt', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'jpg', 'jpeg', 'png', 'gif', 'zip', 'tar', 'gz', '7z', 'rar', 'db', 'dat', 'exe', 'enc', 'bat', 'so', 'ini', 'aspx',
        'mp3', 'wav', 'ogg', 'mp4', 'avi', 'mkv', 'mov', 'html', 'htm', 'css', 'js', 'json', 'xml', 'csv', 'sql', 'vir', 'run', 'dll', 'jar', 'sh', 'tmpl', 'yml', 'config',
        'pyd', 'cab', 'ico', 'py', 'lib', 'h', 'pdb', 'conf', 'dtd', 'rdb', 'cmd', 'cfg', 'idl', 'lock', 'log', 'p12', 'frm', 'ibd'
    ];

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 300);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::debug('Job started');

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

        // Directory::with('children', 'children.children', 'children.files', 'files')
        // ->chunk(500, function ($directories) {
        //     Cache::put('directories', $directories, 60);
        // });

        Log::debug('Job finished');
    }

    public function prepareUrl($url)
    {
        Log::debug('Preparing data');
        
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
}
