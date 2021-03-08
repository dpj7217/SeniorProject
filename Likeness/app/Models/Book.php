<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    public function authors() {
        return $this->belongsToMany(Author::class);
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
         * ATTACH TAG OF CATEGORY 
         */
        //IF $THIS HAS CATEGORIES
        if ($this->categories) {
            $categories = explode('|', $this->categories);
            //LOOP THROUGH ALL CATEGORIES
            foreach($categories as $category) {
                if (strlen($category) > 0) {
                    //VARIABLE TO HOLD TAG
                    $categoryTag;

                    //IF TAG DOESNT ALREADY EXIST FOR GENRE
                    if (!(Tags::where('title', $category)->exists())) {
                        //CREATE TAG
                        $newTag = new Tags();
                        $newTag->title = $category;
                        $newTag->save();

                        $categoryTag = $newTag;
                    //ELSE IF TAGS EXISTS ALREADY
                    } else {
                        //GET NEWTAG
                        $categoryTag = Tags::where('title', $category)->first();
                    }
                    

                    //IF $THIS DOESN'T ALREADY HAVE TAG ATTACHED
                    if (!($this->tags()->where('tags_id', $categoryTag->id)->exists())) {
                            $this->tags()->attach($categoryTag->id);
                    }
                }
            }            
        }

        /*
         * ATTACH TAGS IF TAG TITLE IS IN DESCRIPTION
         */
        if ($this->description) {
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
