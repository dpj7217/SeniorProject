<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTagsTable extends Migration
{
    private $tagsToFill = [
        'Happy',
        'Sad',
        'Fun',
        'Lighthearted',
        'Adventurous',
        'Romance',
        'Explicit',
        'Creative',
        'Scary',
        'Engaging',
        'Intriguing',
        'Enlightening',
        'Terrifying',
        'Shocking',
        'Meaningful',
        'Nostalgic',
        'Relateable',
        'Space',
        'Flying',
        'Time Travel',
        'Animals',
        'Emotional',
        'Magic',
        'Wizard',
        'Witch',
        'Comedy',
        'Funny',
        'Science',
        'Educational',
        'Fairies',
        'Mythical Creatures',
        'Dry Humor',
        'Non-Fiction',
        'Fiction',
        'Coming Of Age',
        'Zombies'
    ];


    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->timestamps();
        });
        
        Schema::create('movie_tags', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tags_id');
            $table->unsignedBigInteger('movie_id');
            $table->timestamps();

            $table->unique(['tags_id', 'movie_id']);
            $table->foreign('tags_id')->references('id')->on('tags')->onDelete('cascade');
            $table->foreign('movie_id')->references('id')->on('movies')->onDelete('cascade');
        });
        
        Schema::create('t_v_show_tags', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tags_id');
            $table->unsignedBigInteger('t_v_show_id');
            $table->timestamps();

            $table->unique(['tags_id', 't_v_show_id']);
            $table->foreign('tags_id')->references('id')->on('tags')->onDelete('cascade');
            $table->foreign('t_v_show_id')->references('id')->on('t_v_shows')->onDelete('cascade');
        });

        Schema::create('book_tags', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tags_id');
            $table->unsignedBigInteger('book_id');
            $table->timestamps();

            $table->unique(['tags_id', 'book_id']);
            $table->foreign('tags_id')->references('id')->on('tags')->onDelete('cascade');
            $table->foreign('book_id')->references('id')->on('books')->onDelete('cascade');
        });

        $this->fillTagsTable();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tags');
    }



    private function fillTagsTable() {
        foreach($this->tagsToFill as $tagTitle) {
            $newTag = new App\models\Tags();
            $newTag->title = $tagTitle;

            $newTag->save();
        }
    }
}
