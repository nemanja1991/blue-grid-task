<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Directory extends Model
{
    use HasFactory;

    protected $fillable = [];

    public function files(): HasMany
    {
        return $this->hasMany(File::class);
    }
}
