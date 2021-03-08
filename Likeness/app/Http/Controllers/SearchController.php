<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\client;
use App\models\Movie;
use App\models\TVShow;
use App\models\book;
use App\models\Genres;
use App\models\author;
use App\models\tags;

class SearchController extends Controller
{
    /*
     *
     * Show the Search form
     *
     * @@RETURNS -- view
     *
     * */
    public function show() {
        return view('search');
    }




    /*
     *
     * Handler for search form
     *
     * The actual Search Logic
     *     search for items that match search string
     *     search by title, author, director, etc.
     *
     *
     * @@PARAMS -- $request : request object from form
     * @@RETURNS -- view ('searchResults') with collection of collections of entertainment objects (movie, tv-show, etc.)
     *              [[books], [movies], [tv-shows]]
     *
     * */
    public function index(Request $request) {

        //CALL API TO SAVE MOST RECENT DATA TO DATABASE
        $this->BooksFromAPI($request['search']);
        $this->MoviesFromAPI($request['search']);
        $this->TVShowsFromAPI($request['search']);

        //QUERY DATABASE TO GET DATA
        $resultsCollection = collect();

        /*
         *  GET BOOKS
         */
        $booksCollection = collect($this->BooksFromDB($request['search']));
        $booksCollection = $booksCollection->unique();

        /*
         * GET MOVIES
         */
        $moviesCollection = collect($this->MoviesFromDB($request['search']));
        $moviesCollection = $moviesCollection->unique();

        /*
         * GET TVSHOWS
         */
        $TVShowCollection = collect($this->TVShowsFromDB($request['search']));
        $TVShowCollection = $TVShowCollection->unique();

        $resultsCollection->put('books', $booksCollection);
        $resultsCollection->put('movies', $moviesCollection);
        $resultsCollection->put('tvShows', $TVShowCollection);
 
        return view('searchResults', [
            'results' => $resultsCollection
        ]);
    }


    /* Call this from searchResults when "get likes" button is pushed ??
     *
     *
     * Get like entertainment
     *      search for items with tags that match given entertainment's
     *
     *
     * @@RETURNS -- array of entertainment objects (movie, tv-show, etc.)
     *              [[books], [movies], [tv-shows]]
     */
    public function GetLikes() {

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
        $client = new client();
        $url = 'https://api.themoviedb.org/3/search/movie?api_key=a8ced2ec890bfcfa326f61ee8984ac28&language=en-US&query=' . $param . '&page=1&include_adult=false';

        $response = $client->request('GET', $url, ['verify ' => false]);

        $APIResults = json_decode($response->getBody(), true)['results'];

       

        foreach($APIResults as $movie) {
            //IF MOVIE ISN'T ALREADY IN DB
            if (!(Movie::where('TMDB_id', $movie['id'])->exists())) {
                $record = new Movie();
                $record->TMDB_id = $movie['id'];
                $record->title = $movie['title'];
                $record->imagePath = (array_key_exists('poster_path', $movie)) ? $movie['poster_path'] : null;
                $record->description =  (array_key_exists('overview', $movie)) ? $movie['overview'] : null;
                $record->releaseDate = (array_key_exists('release_date', $movie)) ? $this->getDateFrom($movie['release_date']) : null;

                $record->save();

                $record->makeTags();
           
                if (array_key_exists('genre_ids', $movie)) {
                    foreach($movie['genre_ids'] as $genre_id) {
                        $record->genres()->attach(Genres::where('TMDB_genre_id', $genre_id)->first()->id);
                    }
                }
            }

        }

        return $APIResults;
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
        $client = new client();

        $url = 'https://www.googleapis.com/books/v1/volumes?q=' . $param;
        $response =  $client->request('GET', $url, ['verify' => false]);

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
        $client = new client();
        $url = 'https://api.themoviedb.org/3/search/tv?api_key=a8ced2ec890bfcfa326f61ee8984ac28&language=en-US&page=1&query='. $param .'&include_adult=false';

        $response = $client->request('GET', $url, ['verify' => false]);

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



    /*
     * global array containing which columns to search by in database queries
    */
    private $DBColumns = ['title', 'description'];




    /*
     *
     * Search movie DB based on param
     *
     * @@PARAMS -- $param = provided search parameter
     * @@RETURNS -- $results = array containing results
     *
     */
    private function MoviesFromDB($param) {
        $results = collect();

        foreach($this->DBColumns as $column) {
            $movies = Movie::where($column, 'like', '%' . $param . '%')->get();

            foreach($movies as $movie) {
                $results->push($movie);
            }
        }

        return $results->toArray();
    }

    /*
     *
     * Search book DB based on param
     *
     * @@PARAMS -- $param = provided search parameter
     * @@RETURNS -- $results = array containing search results
     *
     */
    private function BooksFromDB($param) {
        $results = collect();

        foreach($this->DBColumns as $column) {
            $books = book::where($column, 'like', '%'. $param . '%')->get();

            foreach ($books as $book) {
                $results->push($book);            
            }
        }

        return $results->toArray();
    }


    /*
     *
     * Search TVShow DB based on param
     *
     * @@PARAMS -- $param = provided search parameter
     * @@RETURNS -- $results = array containing search results
     *
     */
    private function TVShowsFromDB($param) {
        $results = collect();

        foreach($this->DBColumns as $column) {
            $TVShows = TVShow::where($column, 'like', '%' . $param . '%')->get();

            foreach ($TVShows as $show) {
                $results->push($show);                
            }
        }

        return $results->toArray();
    }

}
