<?php



use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;




/*
 *
 *
 *
 * use API Like this:  https://api.themoviedb.org/3/search/movie?api_key=a8ced2ec890bfcfa326f61ee8984ac28&language=en-US&query=Star%20Wars&page=1&include_adult=false
 *
 *
 *
 * */




class CreateMoviesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->integer('TMDB_id');
            $table->string('title');
            $table->string('imagePath')->nullable(); //posterPath
            $table->string('backdropPath')->nullable(); //backdropPath
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
        Schema::dropIfExists('movies');
    }
}
