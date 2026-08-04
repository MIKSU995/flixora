<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        dd([
            'database' => DB::connection()->getDatabaseName(),
            'db_count' => DB::table('media')->count(),
            'eloquent_count' => Media::count(),
            'titles' => Media::pluck('title'),
        ]);
    }
}