<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\WatchHistoryController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\MediaManagementController as AdminMediaController;


/*
|--------------------------------------------------------------------------
| Public User Routes (Tanpa Login Required)
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/media/{slug}', [MediaController::class, 'show'])->name('media.show');
Route::post('/media/{id}/rate', [MediaController::class, 'rate'])->name('media.rate');
Route::post('/media/{id}/comment', [MediaController::class, 'comment'])->name('media.comment');
Route::get('/watch-history', [WatchHistoryController::class, 'index'])->name('history');
Route::post('/history/add/{id}', [WatchHistoryController::class, 'store'])->name('history.store');

/*
|----------------------a----------------------------------------------------
| Admin Auth & Management Routes (Wajib Login Admin)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    // Guest Admin Routes
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    // Authenticated Admin Protected Routes
    Route::middleware([\App\Http\Middleware\AdminMiddleware::class])->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        
        // Media Management CRUD
        Route::get('/media', [AdminMediaController::class, 'index'])->name('media.index');
        Route::get('/media/create', [AdminMediaController::class, 'create'])->name('media.create');
        Route::post('/media', [AdminMediaController::class, 'store'])->name('media.store');
        Route::get('/media/{id}/edit', [AdminMediaController::class, 'edit'])->name('media.edit');
        Route::put('/media/{id}', [AdminMediaController::class, 'update'])->name('media.update');
        Route::delete('/media/{id}', [AdminMediaController::class, 'destroy'])->name('media.destroy');
        Route::delete('/comments/{id}', [AdminMediaController::class, 'deleteComment'])->name('comments.destroy');
    });
});

Route::get('/debug-url', function () {
    return response()->json([
        'app_url' => config('app.url'),
        'asset' => asset('css/flixora.css'),
        'route' => route('home'),
        'url' => url('/'),
        'secure' => request()->isSecure(),
        'scheme' => request()->getScheme(),
        'host' => request()->getHost(),
        'env' => app()->environment(),
    ]);
});