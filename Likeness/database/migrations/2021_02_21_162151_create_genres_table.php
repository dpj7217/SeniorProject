<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use GuzzleHttp\client;

class CreateGenresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('genres', function (Blueprint $table) {
            $table->id();
            $table->integer('TMDB_genre_id');
            $table->string('TMDB_genre_title');
            $table->timestamps();
        });

        Schema::create('genres_movie', function (Blueprint $table){
            $table->id();
            $table->unsignedBigInteger('genres_id');
            $table->unsignedBigInteger('movie_id');
            $table->timestamps();

            $table->unique(['genres_id', 'movie_id']);
            $table->foreign('genres_id')->references('id')->on('genres')->onDelete('cascade');
            $table->foreign('movie_id')->references('id')->on('movies')->onDelete('cascade');
        });

        Schema::create('genres_t_v_show', function (Blueprint $table){
            $table->id();
            $table->unsignedBigInteger('genres_id');
            $table->unsignedBigInteger('t_v_show_id');
            $table->timestamps();

            $table->unique(['genres_id', 't_v_show_id']);
            $table->foreign('genres_id')->references('id')->on('genres')->onDelete('cascade');
            $table->foreign('t_v_show_id')->references('id')->on('t_v_shows')->onDelete('cascade');
        });

        $this->fillGenresTable();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('genres');
    }


    private function fillGenresTable() {
        $client = new client();
        $url = 'https://api.themoviedb.org/3/genre/movie/list?api_key=a8ced2ec890bfcfa326f61ee8984ac28&language=en-US';

        $response = $client->request('GET', $url, ['verify' => false]);

        $results = json_decode($response->getBody(), true)['genres'];

        foreach($results as $genre) {
            $newGenre = new App\Models\Genres();
            $newGenre->TMDB_genre_id = $genre['id'];
            $newGenre->TMDB_genre_title = $genre['name'];
            $newGenre->save();
        }

        $url = 'https://api.themoviedb.org/3/genre/tv/list?api_key=a8ced2ec890bfcfa326f61ee8984ac28&language=en-US';

        $response = $client->request('GET', $url, ['verify' => false]);

        $results = json_decode($response->getBody(), true)['genres'];

        foreach($results as $genre) {
            if (!(App\models\Genres::where('TMDB_genre_id', $genre['id'])->exists())) { 
                $newGenre = new App\Models\Genres();
                $newGenre->TMDB_genre_id = $genre['id'];
                $newGenre->TMDB_genre_title = $genre['name'];
                $newGenre->save();
            }
        }

        return;

    }
}
