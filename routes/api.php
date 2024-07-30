<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\FilesDirectoryController;

Route::get('files-and-directories', [FilesDirectoryController::class, "index"]);
Route::get('directories', [FilesDirectoryController::class, "getDirectories"]);
Route::get('files', [FilesDirectoryController::class, "getFiles"]);