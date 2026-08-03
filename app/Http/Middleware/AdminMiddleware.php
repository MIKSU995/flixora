<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || !auth()->user()->is_admin) {
            return redirect()->route('admin.login')->with('error', 'Akses khusus Admin. Silakan login terlebih dahulu.');
        }

        return $next($request);
    }
}
