<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\Genre;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
{
    dd(
        config('database.default'),
        DB::connection()->getDatabaseName(),
        Media::count()
    );
}
}