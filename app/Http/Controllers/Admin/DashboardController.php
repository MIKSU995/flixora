<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Models\Genre;
use App\Models\Rating;
use App\Models\Comment;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalMovies = Media::where('type', 'movie')->count();
        $totalTvShows = Media::where('type', 'tv_show')->count();
        $totalGenres = Genre::count();
        $totalRatings = Rating::count();
        $totalComments = Comment::count();

        $recentMedia = Media::with('genres')->orderBy('created_at', 'desc')->take(5)->get();
        $recentComments = Comment::with('media')->orderBy('created_at', 'desc')->take(5)->get();

        return view('admin.dashboard', compact(
            'totalMovies',
            'totalTvShows',
            'totalGenres',
            'totalRatings',
            'totalComments',
            'recentMedia',
            'recentComments'
        ));
    }
}
