<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tags extends Model
{
    use HasFactory;

    public function books() {
        return $this->belongsToMany(books::class);    
    }

    public function movies() {
        return $this->belongsToMany(Movie::class);
    }

    public function tvShows() {
        return $this->belongsToMany(TVShow::class);
    }

    public static function getTagsArray() {
        return Tags::get()->pluck('title')->toArray();
    }
}
