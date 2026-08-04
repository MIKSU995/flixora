<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
   public function index()
{
    $featured = Media::orderByDesc('avg_rating')->take(5)->get();

    $latest = Media::latest()->take(12)->get();

    return view('home', compact('featured', 'latest'));
}
}