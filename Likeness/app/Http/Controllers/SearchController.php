<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

        \API::updateDB($request['search']);

        return view('searchResults', [
            'results' => \Search::forEntertainmentWhere($request['search'])
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



}
