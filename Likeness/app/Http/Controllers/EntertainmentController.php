<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\models\TVShow;
use App\models\Movie;
use App\models\Book;

class EntertainmentController extends Controller
{
    public function show($entertainment_id) {
        if ($show = TVShow::where('TMDB_id', $entertainment_id)->first()) {
            dd($show);
            return view('entertainment.tvShowDetails', [
                'entertainment' => $show
            ]);
        } else if ($movie = Movie::where('TMDB_id', $entertainment_id)->first()) {
            dd($movie);
            return view('entertainment.movieDetails', [
                'entertainment' => $movie
            ]);
        } else if ($book = Book::where('api_id', $entertainment_id)->first()) {
            dd($book);
            return view('entertainment.bookDetailsd', [
                'entertainment' => $book
            ]);
        } else {
            abort(404);
        }
    }
}
