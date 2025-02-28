<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $fillable = ['directory_id', 'file_name', 'created_at'];

    public function directories()
    {
        return $this->belongsTo(Directory::class);
    }
}
