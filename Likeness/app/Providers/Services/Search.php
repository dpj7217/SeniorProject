<?php

namespace App\Providers\Services;

use App\models\Movie;
use App\models\TVShow;
use App\models\book;
use App\models\Genres;
use App\models\author;
use App\models\tags;


class Search {

    /*
     * global array containing which columns to search by in database queries
     * */
    private $DBColumns = ['title', 'description'];


    public function forEntertainmentWhere($searchParam) {
        //QUERY DATABASE TO GET DATA
        $resultsCollection = collect();

        /*
         *  GET BOOKS
         */
        $booksCollection = collect($this->BooksFromDB($searchParam));
        $booksCollection = $booksCollection->unique();

        /*
         * GET MOVIES
         */
        $moviesCollection = collect($this->MoviesFromDB($searchParam));
        $moviesCollection = $moviesCollection->unique();

        /*
         * GET TVSHOWS
         */
        $TVShowCollection = collect($this->TVShowsFromDB($searchParam));
        $TVShowCollection = $TVShowCollection->unique();

        $resultsCollection->put('books', $booksCollection);
        $resultsCollection->put('movies', $moviesCollection);
        $resultsCollection->put('tvShows', $TVShowCollection);
 
        return $resultsCollection;
    }

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
            //SELECT * FROM movies where title / description like parameter

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