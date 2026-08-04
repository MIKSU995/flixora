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
    try {

        $media = Media::latest()->take(6)->get();

        dd($media);

    } catch (\Exception $e) {

        dd($e->getMessage());

    }
}
}