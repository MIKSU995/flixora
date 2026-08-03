<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\Genre;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $selectedType = $request->query('type'); // 'movie', 'tv_show', or null
        $selectedGenreSlug = $request->query('genre');
        $searchQuery = $request->query('q');

        // Base Query
        $query = Media::with('genres');

        if ($selectedType && in_array($selectedType, ['movie', 'tv_show'])) {
            $query->where('type', $selectedType);
        }

        if ($selectedGenreSlug) {
            $query->whereHas('genres', function ($q) use ($selectedGenreSlug) {
                $q->where('slug', $selectedGenreSlug);
            });
        }

        if ($searchQuery) {
            $query->where(function ($q) use ($searchQuery) {
                $q->where('title', 'like', "%{$searchQuery}%")
                  ->orWhere('description', 'like', "%{$searchQuery}%")
                  ->orWhere('director', 'like', "%{$searchQuery}%")
                  ->orWhere('cast', 'like', "%{$searchQuery}%");
            });
        }

        // Hero Featured Movie/TV Show
        $featuredMedia = Media::with('genres')->orderBy('avg_rating', 'desc')->first();

        // Top Rated Media
        $topRatedMedia = Media::with('genres')->orderBy('avg_rating', 'desc')->take(4)->get();

        // Main Catalog Items
        $catalogMedia = $query->orderBy('created_at', 'desc')->paginate(12)->withQueryString();

        // Master Genres for navigation
        $allGenres = Genre::orderBy('name', 'asc')->get();

        return view('home', compact(
            'featuredMedia',
            'topRatedMedia',
            'catalogMedia',
            'allGenres',
            'selectedType',
            'selectedGenreSlug',
            'searchQuery'
        ));
    }
}
