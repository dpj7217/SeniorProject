<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Genres extends Model
{
    use HasFactory;

    public function Movies() {
        return $this->belongsToMany(Movies::class);
    }

    public function tvShows() {
        return $this->belongsToMany(TVShows::class);
    }
}
