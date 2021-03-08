<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTVShowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_v_shows', function (Blueprint $table) {
            $table->id();
            $table->integer('TMDB_id');
            $table->string('title');
            $table->string('imagePath')->nullable(); //posterPath
            //create new linking table for genres table $table->string('genre')->nullable; //genre.name //make this a tag //have to use api like this to get genre title: ???
            $table->text('description')->nullable(); //overview
            $table->date('releaseDate')->nullable(); //release_date
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_v_shows');
    }
}
