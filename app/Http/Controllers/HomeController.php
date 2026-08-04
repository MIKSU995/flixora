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
            DB::connection()->getPdo();

            dd([
                'status' => 'CONNECTED',
                'tables' => DB::select('SHOW TABLES'),
            ]);
        } catch (\Exception $e) {
            dd([
                'status' => 'FAILED',
                'error' => $e->getMessage(),
            ]);
        }
    }
}