<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\FileSystemController;

Route::get('files-and-directories', [FileSystemController::class, "index"]);
Route::get('directories', [FileSystemController::class, "getDirectories"]);
Route::get('files', [FileSystemController::class, "getFiles"]);