<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;


class SearchDropdown extends Component
{
    public $search = '';

    public function render()
    {
        $searchResults = [];

        if (strlen($this->search) >2 ) {
            $responseSearchResults = Http::withToken(config('services.tmdb.token'))
                ->get('https://api.themoviedb.org/3/search/movie?query=' . $this->search)
                ->json();
    
            $searchResults = collect($responseSearchResults['results'])->take(7);
        }

        return view('livewire.search-dropdown', [
            'searchResults' => $searchResults
        ]);
    }
}
