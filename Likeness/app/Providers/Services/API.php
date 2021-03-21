<?php

namespace App\Providers\Services;

use GuzzleHttp\client;
use App\models\Movie;
use App\models\TVShow;
use App\models\book;
use App\models\Genres;
use App\models\author;
use App\models\tags;

class API {

    private $client;

    public function __construct() {
        $this->client = new Client();
    }


    public function updateDB($param) {
        $this->BooksFromAPI($param);
        $this->MoviesFromAPI($param);
        $this->TVShowsFromAPI($param);    
    }
    


    /*
     *
     *
     * create date object from string
     *
     * @@PARAMS -- $string = string to be translated to date
     * @@RETURNS -- $date : date object
     *
     */
    private function getDateFrom($string) {
        $date = date_create_from_format('Y-m-d', $string);

        if ($date)
            return $date;
        else
            return null;
    }



     /*
     * use Guzzle to make call to API
     *
     * @@PARAMS -- $param = params for API call
     *
     * @@RETURNS -- void -- just saves data to the database
     *     
     *  
     * @@FUTURE CHANGES -- 
     *      1) move to service provider
     *      2) add if section to update db if it already exists
     */
    public function MoviesFromAPI($param) {

        $url = 'https://api.themoviedb.org/3/search/movie?api_key=a8ced2ec890bfcfa326f61ee8984ac28&language=en-US&query=' . $param . '&page=1&include_adult=false';
        $response =  $this->client->request('GET', $url, ['verify' => false]);

        $APItvShows = json_decode($response->getBody(), true)['results'];

        foreach($APItvShows as $APIMovie) {

            //if movie exists in DB already
            if ($foundMovie = Movie::where('TMDB_id', $APIMovie['id'])->first()) {
                //update it with updateMovie()
                $this->makeMovie($foundMovie, $APIMovie);
            //in any other case (movie doesn't exist)    
            } else {
                //create new movie using createNewMovie()
                $this->makeMovie(new Movie(), $APIMovie);
            }
        }
    }



    /**
     * 
     * helper function to create new movie from api in db or update movie in db
     * 
     * @param movie : movie to be made 
     * @param APIMovie : data from api to be used for creating movie in db
     * 
     * @return void
     * 
     */
    private function makeMovie($movie, $APIMovie) { 
        $movie->TMDB_id = $APIMovie['id'];
        $movie->title = $APIMovie['title'];
        $movie->backdropPath = (array_key_exists('backdrop_path', $APIMovie)) ? $APIMovie['backdrop_path'] : null;
        $movie->imagePath = (array_key_exists('poster_path', $APIMovie)) ? $APIMovie['poster_path'] : null;
        $movie->description = (array_key_exists('overview', $APIMovie)) ? $APIMovie['overview'] : null;
        $movie->releaseDate = (array_key_exists('realease_date', $APIMovie)) ? $APIMovie['release_date'] : null;

        $movie->save();

        if (array_key_exists('genre_ids', $APIMovie)) {
            foreach($APIMovie['genre_ids'] as $genre_id) {
                if (!($movie->genres()->where('genres.id', Genres::getID($genre_id))->exists())) {
                    $movie->genres()->attach(Genres::where('TMDB_genre_id', $genre_id)->first()->id);
                }
            }
        }
    }



   






    /*
     * use Guzzle to make call to API
     *
     * @@PARAMS -- $param = params for API call
     *
     * @@RETURNS -- void -- just saves data from API to databse
     *
     *      
     * @@FUTURE CHANGES -- 
     *      1) move to service provider
     *      2) add if section to update db if it already exists
     */
    private function BooksFromAPI($param) {

        $url = 'https://www.googleapis.com/books/v1/volumes?q=' . $param;

        $response =  $this->client->request('GET', $url, ['verify' => false]);

        $APItvShows = json_decode($response->getBody(), true)['items'];

        foreach($APItvShows as $book) {

            $newBook;

            //if book exists in database already
            if ($foundBook = Book::where('api_id', $book['id'])->first()) {
                //set scoped variable to 
                $newBook = $foundBook;
            //in any other case (book doesn't exist)    
            } else {
                //set scoped variable to 
                $newBook = new Book();

                $newBook->api_id = $book['id'];
            }


            $newBook->title = $book['volumeInfo']['title'];
            $newBook->subtitle = (array_key_exists('subtitle', $book['volumeInfo'])) ? $book['volumeInfo']['subtitle'] : null;
            
            if (array_key_exists('categories', $book['volumeInfo'])) {

                $categoryString = "";

                foreach ($book['volumeInfo']['categories'] as $category) {
                    $categoryString = $categoryString . "|" . $category;
                }

                $newBook->categories = $categoryString;
            } else {
                $newBook->categories = null;
            }


            if (array_key_exists('imageLinks', $book['volumeInfo']) && array_key_exists('thumbnail', $book['volumeInfo']['imageLinks'])) {
                $newBook->imageLink = $book['volumeInfo']['imageLinks']['thumbnail'];
            } else {
                $newBook->imageLink = null;
            }

            $newBook->publishedDate = (array_key_exists('publishedDate', $book['volumeInfo'])) ? $this->getDateFrom($book['volumeInfo']['publishedDate']) : null;
            $newBook->pageCount = (array_key_exists('pageCount', $book['volumeInfo'])) ? $book['volumeInfo']['pageCount'] : null;
            $newBook->description = (array_key_exists('description', $book['volumeInfo'])) ? $book['volumeInfo']['description'] : null;

            $newBook->save();

            if (array_key_exists('authors', $book['volumeInfo'])) {
                foreach ($book['volumeInfo']['authors'] as $author) {
                    if (Author::where('authorName', $author)->first()) {
                        continue;
                    } else {
                        $newAuthor = new Author();
                        $newAuthor->authorName = $author;
                        $newAuthor->save();
                    }

                    if (!($newBook
                          ->authors()
                          ->where('id', 
                            Author::where('authorName', $author)->first()->id))) {
                        $newBook->authors()->attach(Author::where('authorName', $author)->first()->id);
                    }
                }
            }
            
        }
    }





    /*
     * use Guzzle to make call to API
     *
     * @@PARAMS -- $param = params for API call
     *
     * @@RETURNS -- void -- just saves data to the database
     *
     * 
     * 
     * @@FUTURE CHANGES -- 
     *      1) move to service provider
     *      2) add if section to update db if it already exists
     */
    private function TVShowsFromAPI($param) {
        $url = 'https://api.themoviedb.org/3/search/tv?api_key=a8ced2ec890bfcfa326f61ee8984ac28&language=en-US&page=1&query='. $param .'&include_adult=false';

        $response = $this->client->request('GET', $url, ['verify' => false]);

        $APItvShows = json_decode($response->getBody(), true)['results'];

        foreach($APItvShows as $APItvShow) {
            //if TVShow already exists in DB
            if ($foundTVShow = TVShow::where('TMDB_id', $APItvShow['id'])->first()) {
                //add scoped variable to found tv show
                $this->makeTVShow($foundTVShow, $APItvShow);
            //in any other case (tvshow doesn't exist)    
            } else {
                //set scoped variable to new TVShow
                $this->makeTVShow(new TVShow(), $APItvShow);
            }


            
        }
    }

    /**
     * 
     * helper function to make new tvshow or update existing record
     * 
     * @param tvShow : tvShow to be made and saved
     * @param APItvShow : tvShow from API
     * 
     * @return void
     * 
     */
    private function makeTVShow($tvShow, $APItvShow) {
        $tvShow->TMDB_id = $APItvShow['id'];
        $tvShow->title = $APItvShow['name'];
        $tvShow->imagePath = (array_key_exists('poster_path', $APItvShow)) ? $APItvShow['poster_path'] : null;
        $tvShow->backdropPath = (array_key_exists('backdrop_path', $APItvShow)) ? $APItvShow['backdrop_path'] : null;
        $tvShow->description = (array_key_exists('overview', $APItvShow)) ? $APItvShow['overview'] : null;
        $tvShow->releaseDate = (array_key_exists('releaseDate', $APItvShow)) ? $APItvShow['first_air_date'] : null;

        $tvShow->save();

        if (array_key_exists('genre_ids', $APItvShow)) {
            foreach($APItvShow['genre_ids'] as $genre_id) {
                if (!($tvShow->genres()->where('genres.id', Genres::getID($genre_id))->exists())) {
                    $tvShow->genres()->attach(Genres::getID($genre_id));
                }
            }
        }
    }

}


