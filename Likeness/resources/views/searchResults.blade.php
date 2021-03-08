

@extends('layouts.app')

@section('head')
    <link rel="stylesheet" href="{{ asset('/css/owl.carousel.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/owl.theme.default.css') }}">

@endsection

@section('content')

    <div class="top-bar">
        <h1>Grab and drag to see more options</h1>
    </div>

    <h3>Books</h3>

    <div class="carousel py-2">
        <div class="owl-carousel">
            @foreach($results->get('books') as $book)
                <div class="card" style="width: 18rem;">
                    <div class="book-card-top">
                        <img class="card-img-top" src="{{ $book['imageLink'] }}" alt="{{ $book['title'] }}" >
                    </div>
                    <div class="d-flex flex-column card-body">
                        <h5 class="card-title">{{ $book['title'] }}</h5>                    
                        <div class="card-description">
                            <p class="card-text">{{ Str::Limit($book['description'], 150, $end='...') }}</p>
                        </div>
                        <a href="{{ route('showEntertainment', $book['api_id']) }}" class="mt-auto btn btn-primary">Details</a>
                    </div>
                </div>
            @endforeach

        </div>
    </div>
    
    <br/>
    <br/>
    <br/>

    <hr style="height: .25em; background-color: black"/>
    
    <br/>
    <br/>
    <br/>
    
    <h3>Movies</h3>

    <div class="carousel py-2">
        <div class="owl-carousel">
            @foreach($results->get('movies') as $movie)
                <div class="card" style="width: 18rem;">
                    @if ($movie['imagePath'])
                        <img class="card-img-top" src="https://www.themoviedb.org/t/p/w600_and_h900_bestv2{{ $movie['imagePath'] }}" alt="{{ $movie['title'] }}" >
                    @else
                        <div class="movie-card-top">
                            <p>No image found for "{{ $movie['title'] }}"</p>
                        </div>
                    @endif
                    <div class="d-flex flex-column card-body">
                        <h5 class="card-title">{{ $movie['title'] }}</h5>                    
                        <div class="card-description">
                            <p class="card-text">{{ Str::Limit($movie['description'], 150, $end='...') }}</p>
                        </div>
                        <a href="{{ route('showEntertainment', $movie['TMDB_id']) }}" class="mt-auto btn btn-primary">Details</a>
                    </div>
                </div>
            @endforeach

        </div>
    </div>
    
        
    <br/>
    <br/>
    <br/>

    <hr style="height: .25em; background-color: black"/>
    
    <br/>
    <br/>
    <br/>
    
    <h3>TV Shows</h3>

    <div class="carousel py-2">
        <div class="owl-carousel">
            @foreach($results->get('tvShows') as $show)
                <div class="card" style="width: 18rem;">
                    @if ($show['imagePath'])
                        <img class="card-img-top" src="https://www.themoviedb.org/t/p/w600_and_h900_bestv2{{ $show['imagePath'] }}" alt="{{ $show['title'] }}" >
                    @else
                        <div class="movie-card-top">
                            <p>No image found for "{{ $show['title'] }}"</p>
                        </div>
                    @endif
                    <div class="d-flex flex-column card-body">
                        <h5 class="card-title">{{ $show['title'] }}</h5>                    
                        <div class="card-description">
                            <p class="card-text">{{ Str::Limit($show['description'], 150, $end='...') }}</p>
                        </div>
                        <a href="{{ route('showEntertainment', $show['TMDB_id']) }}" class="mt-auto btn btn-primary">Details</a>
                    </div>
                </div>
            @endforeach

        </div>
    </div>
@endsection

@section('foot')
    <script src="{{ asset('/js/owl.carousel.js') }}"></script>
    <script>
        $(document).ready(function(){
            $(".owl-carousel").owlCarousel({
                margin:2,
                nav:true,
                center:false,
                responsive:{
                    0:{
                        items:1,
                        nav:true,
                        center:false
                    },
                    600:{
                        items:3,
                        nav:true,
                        center:false
                    },
                    1000:{
                        items:5,
                        nav:true,
                        center:false
                    }
                }
            });
        });
    </script>
@endsection