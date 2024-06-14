<?php

namespace App\Http\Controllers;

use App\ViewModels\MoviesViewModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MovieController extends Controller
{
    public function index()
    {
        $responsePopularMovies = Http::withToken(config('services.tmdb.token'))
            ->get('https://api.themoviedb.org/3/movie/popular')
            ->json();

        $popularMovies = collect($responsePopularMovies['results']);

        $responseGenres = Http::withToken(config('services.tmdb.token'))
            ->get('https://api.themoviedb.org/3/genre/movie/list')
            ->json();

        $genresArray = collect($responseGenres['genres']);

        $genres = collect($genresArray)->mapWithKeys(function ($genre) {
            return [$genre['id'] => $genre['name']];
        });

        $responseNowPlayingMovies = Http::withToken(config('services.tmdb.token'))
            ->get('https://api.themoviedb.org/3/movie/now_playing')
            ->json();

        $nowPlayingMovies = collect($responseNowPlayingMovies['results']);

        // return view('index', [
        //     'popularMovies' => $popularMovies,
        //     'genres' => $genres,
        //     'nowPlayingMovies' => $nowPlayingMovies
        // ]);

        $viewModel = new MoviesViewModel(
            $popularMovies, 
            $nowPlayingMovies,
            $genres,
        );

        return view('index', $viewModel);
    }

    public function show(string $id)
    {
        $movie = Http::withToken(config('services.tmdb.token'))
            ->get('https://api.themoviedb.org/3/movie/' . $id . '?append_to_response=credits,videos,images')
            ->json();

        return view('show' , [
            'movie' => $movie
        ]);
    }

}
