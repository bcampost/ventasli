<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\SlideController;
use App\Http\Controllers\Admin\QuickLinkController;

/**
 * Root: si está logueado -> dashboard (redirige a home)
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

    /**
     * ADMIN (rol: admin - MINÚSCULAS)
     * Aquí va todo lo que solo el admin puede ver/editar
     */
    Route::prefix('admin')->name('admin.')->middleware(['role:admin'])->group(function () {

        // Slides (CRUD)
        Route::resource('slides', SlideController::class)->except(['show']);

        // Quick Links (CRUD)
        Route::get('/quick-links', [QuickLinkController::class, 'index'])->name('quick-links.index');
        Route::post('/quick-links', [QuickLinkController::class, 'store'])->name('quick-links.store');
        Route::get('/quick-links/{quickLink}/edit', [QuickLinkController::class, 'edit'])->name('quick-links.edit');
        Route::put('/quick-links/{quickLink}', [QuickLinkController::class, 'update'])->name('quick-links.update');
        Route::delete('/quick-links/{quickLink}', [QuickLinkController::class, 'destroy'])->name('quick-links.destroy');
    });
});

require __DIR__.'/auth.php';