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

    public function store(Request $request, $id)
{
    $history = session()->get('watch_history', []);

    // Hilangkan jika sudah ada
    $history = array_filter($history, function ($item) use ($id) {
        return $item != $id;
    });

    array_unshift($history, $id);

    // Maksimal 20 riwayat
    $history = array_slice($history, 0, 20);

    session([
        'watch_history' => $history
    ]);

    return response()->json([
        'success' => true
    ]);
}
}
