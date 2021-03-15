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

        $results = json_decode($response->getBody(), true)['results'];

        foreach($results as $movie) {
            if (!(Movie::where('TMDB_id', $movie['id'])->exists())) {
                $newMovie = new Movie();    

                $newMovie->TMDB_id = $movie['id'];
                $newMovie->title = $movie['title'];
                $newMovie->imagePath = (array_key_exists('poster__path', $movie)) ? $movie['poster_path'] : null;
                $newMovie->description = (array_key_exists('overview', $movie)) ? $movie['overview'] : null;
                $newMovie->releaseDate = (array_key_exists('realease_date', $movie)) ? $movie['release_date'] : null;

                $newMovie->save();

                if (array_key_exists('genre_ids', $movie)) {
                    foreach($movie['genre_ids'] as $genre_id) {
                        $newMovie->genres()->attach(Genres::where('TMDB_genre_id', $genre_id)->first()->id);
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

        $results = json_decode($response->getBody(), true)['items'];

        foreach($results as $book) {

            if (!(Book::where('api_id', $book['id'])->exists())) {
                $newBook = new Book();
                $newBook->api_id = $book['id'];
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

                            $newBook->authors()->attach(Author::where('authorName', $author)->first()->id);
                        }
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

        $results = json_decode($response->getBody(), true)['results'];

        foreach($results as $result) {

            if (!(TVShow::where('TMDB_id', $result['id'])->exists())) {
                $newShow = new TVShow();

                $newShow->TMDB_id = $result['id'];
                $newShow->title = $result['name'];
                $newShow->imagePath = (array_key_exists('poster_path', $result)) ? $result['poster_path'] : null;
                $newShow->description = (array_key_exists('overview', $result)) ? $result['overview'] : null;
                $newShow->releaseDate = (array_key_exists('releaseDate', $result)) ? $result['first_air_date'] : null;

                $newShow->save();

                if (array_key_exists('genre_ids', $result)) {
                    foreach($result['genre_ids'] as $genre_id) {
                        $newShow->genres()->attach(Genres::where('TMDB_genre_id', $genre_id)->first()->id);
                    }
                }
            }
        }
    }


}
