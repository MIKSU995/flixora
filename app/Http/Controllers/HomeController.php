<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\Genre;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
{
    dd([
        'connection' => config('database.default'),
        'host'       => env('DB_HOST'),
        'database'   => env('DB_DATABASE'),
        'username'   => env('DB_USERNAME'),
        'port'       => env('DB_PORT'),
    ]);
}
}
