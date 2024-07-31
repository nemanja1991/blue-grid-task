<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Directory extends Model
{
    use HasFactory;

    protected $fillable = ['ip_address', 'directory', 'parent_id', 'created_at'];

    public function parent()
    {
        return $this->belongsTo(Directory::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Directory::class, 'parent_id')->with('children')->with('files');
    }

    public function files(): HasMany
    {
        return $this->hasMany(File::class);
    }
}
