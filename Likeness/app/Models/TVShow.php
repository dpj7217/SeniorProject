<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TVShow extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function genres() {
        return $this->belongsToMany(Genres::class);
    }

    public function tags() {
        return $this->belongsToMany(Tags::class);
    }
    
    /*
     * 
     * Creates tags for a specific TVShow instance
     * 
     * @@RETURNS -- void
     * 
     */
    public function makeTags() {

        /*
         * ATTACH TAG OF GENRE 
         */
        //IF $THIS HAS GENRES
        if ($this->genres()->count() > 0) {
            //LOOP THROUGH ALL GENRES
            foreach($this->genres()->get() as $genre) {
                //VARIABLE TO HOLD TAG
                $genreTag;

                //IF TAG DOESNT ALREADY EXIST FOR GENRE
                if (!(Tags::where('title', $genre->TMDB_genre_title)->exists())) {
                    //CREATE TAG
                    $newTag = new Tags();
                    $newTag->title = $genre->TMDB_genre_title;
                    $newTag->save();

                    $genreTag = $newTag;
                } else {
                    //GET NEWTAG
                    $genreTag = Tags::where('title', $genre->TMDB_genre_title)->first();
                }
                

                //IF $THIS DOESN'T ALREADY HAVE TAG ATTACHED
                if (!($this->tags()->where('tags_id', $genreTag->id)->exists())) {
                        $this->tags()->attach($genreTag->id);
                }
            }
            
        }

        /*
         * ATTACH TAGS IF TAG TITLE IS IN DESCRIPTION
         */
        if ($this->description) {
            dd($this->description);

            //GET ALL TAGS
            $tags = Tags::get();

            //GET DESCRIPTION AS ARRAY 
            $descriptionArray = explode(' ', $this->description);

            //LOOP THROUGH TAGS
            foreach($tags as $tag) {
                //LOOP THROUGH DESCRIPTION ARRAY
                foreach($descriptionArray as $descriptionWord) {
                    //IF WORD IN DESCRIPTION ARRAY MATCHES TAG TITLE
                    if (strtolower($tag->title) == $descriptionWord && !($this->tags()->where('tags.id', $tag->id)->first())) {
                        //ATTACH TAG
                        $this->tags()->attach($tag->id);
                    }
                }
            }
        }
    }
}

