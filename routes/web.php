<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\Admin\SlideController;
use App\Http\Controllers\Admin\QuickLinkController;
use App\Http\Controllers\Admin\MenuCardImageController;
use App\Http\Controllers\Admin\MenuProductController;

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
     * MENU (CLICK -> pantalla de cards)
     * OJO: SOLO UNA RUTA, con path opcional.
     */
    Route::get('/menu/{sectionSlug}/{path?}', [MenuController::class, 'show'])
        ->where('path', '.*')
        ->name('menu.section');

    /**
     * ADMIN (rol: admin - minúsculas)
     */
    Route::prefix('admin')->name('admin.')->middleware(['role:admin'])->group(function () {

        // Sync de keys (si lo usas)
        Route::post('/menu-cards/sync', [MenuCardImageController::class, 'sync'])->name('menu-cards.sync');

        // Slides (CRUD)
        Route::resource('slides', SlideController::class)->except(['show']);

        // Quick Links (CRUD)
        Route::get('/quick-links', [QuickLinkController::class, 'index'])->name('quick-links.index');
        Route::post('/quick-links', [QuickLinkController::class, 'store'])->name('quick-links.store');
        Route::get('/quick-links/{quickLink}/edit', [QuickLinkController::class, 'edit'])->name('quick-links.edit');
        Route::put('/quick-links/{quickLink}', [QuickLinkController::class, 'update'])->name('quick-links.update');
        Route::delete('/quick-links/{quickLink}', [QuickLinkController::class, 'destroy'])->name('quick-links.destroy');

        // Cards del menú: imagen / título / descripción
        Route::get('/menu-cards', [MenuCardImageController::class, 'index'])->name('menu-cards.index');
        Route::get('/menu-cards/{key}/edit', [MenuCardImageController::class, 'edit'])->name('menu-cards.edit');
        Route::put('/menu-cards/{key}', [MenuCardImageController::class, 'update'])->name('menu-cards.update');
        Route::delete('/menu-cards/{key}', [MenuCardImageController::class, 'destroy'])->name('menu-cards.destroy');

        /**
         * ✅ Productos por sección de menú (CRUD)
         * Se administran por "menu_key" (query string key=...)
         */
        Route::get('/menu-products', [MenuProductController::class, 'index'])->name('menu-products.index');
        Route::get('/menu-products/create', [MenuProductController::class, 'create'])->name('menu-products.create');
        Route::post('/menu-products', [MenuProductController::class, 'store'])->name('menu-products.store');
        Route::get('/menu-products/{menuProduct}/edit', [MenuProductController::class, 'edit'])->name('menu-products.edit');
        Route::put('/menu-products/{menuProduct}', [MenuProductController::class, 'update'])->name('menu-products.update');
        Route::delete('/menu-products/{menuProduct}', [MenuProductController::class, 'destroy'])->name('menu-products.destroy');
    });
});

require __DIR__ . '/auth.php';