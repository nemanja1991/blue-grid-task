<?php

namespace App\Helpers;

use App\Models\ApiLog;
use App\Http\Controllers\Controller;

class Helper extends Controller
{
    public static function apiLog($method, $endpoint, $status) {
        ApiLog::create([
            'request_method' => $method,
            'endpoint' => $endpoint,
            'response_status' => $status,
        ]);
    }
}