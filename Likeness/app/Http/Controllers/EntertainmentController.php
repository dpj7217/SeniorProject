<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\models\TVShow;
use App\models\Movie;
use App\models\Book;

class EntertainmentController extends Controller
{
    public function show($entertainment_id) {
        if ($show = TVShow::where('TMDB_id', $entertainment_id)) {
            return view('tvShowDetails', [
                'entertainment' => $show
            ]);
        } else if ($movie = Movie::where('TMDB_id', $entertainment_id)) {
            return view('movieDetails', [
                'entertainment' => $movie
            ]);
        } else if ($book = Book::where('api_id', $entertainment_id)) {
            return view('bookDetails', [
                'entertainment' => $book
            ]);
        } else {
            die(404);
        }
    }
}
