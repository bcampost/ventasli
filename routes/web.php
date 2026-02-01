<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\SlideController;


/**
 * Root: si está logueado -> dashboard (que redirige a home)
 * si no -> login
 */
Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

/**
 * Rutas protegidas
 */
Route::middleware(['auth'])->group(function () {

    // Breeze espera esta ruta después del login
    Route::get('/dashboard', function () {
        return redirect()->route('home');
    })->name('dashboard');

    // Tu HOME real
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::prefix('admin')->name('admin.')->middleware(['role:Admin'])->group(function () {
        Route::resource('slides', SlideController::class)->except(['show']);
    });

});

require __DIR__.'/auth.php';