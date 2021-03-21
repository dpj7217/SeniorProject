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
            $table->string('imagePath')->nullable(); //poster_path
            $table->string('backdropPath')->nullable(); //backdrop_path
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
