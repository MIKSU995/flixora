<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
   public function index(Request $request)
{
    $searchQuery = $request->search;
    $selectedGenreSlug = $request->genre;
    $selectedType = $request->type;

    $featuredMedia = Media::orderByDesc('avg_rating')->first();

    $topRatedMedia = Media::with('genres')
        ->orderByDesc('avg_rating')
        ->take(6)
        ->get();

    $query = Media::with('genres');

    if ($searchQuery) {
        $query->where('title', 'like', "%{$searchQuery}%");
    }

    if ($selectedType) {
        $query->where('type', $selectedType);
    }

    if ($selectedGenreSlug) {
        $query->whereHas('genres', function ($q) use ($selectedGenreSlug) {
            $q->where('slug', $selectedGenreSlug);
        });
    }

    $catalogMedia = $query
        ->latest()
        ->paginate(12)
        ->withQueryString();

    $allGenres = Genre::orderBy('name')->get();

    return view('home', compact(
        'featuredMedia',
        'topRatedMedia',
        'catalogMedia',
        'allGenres',
        'searchQuery',
        'selectedGenreSlug',
        'selectedType'
    ));
}
}