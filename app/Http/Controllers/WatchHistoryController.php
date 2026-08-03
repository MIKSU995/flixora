<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\Request;

class WatchHistoryController extends Controller
{
    public function index()
    {
        return view('history');
    }
}
