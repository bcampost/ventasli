<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\MenuController;

use App\Http\Controllers\Admin\SlideController;
use App\Http\Controllers\Admin\QuickLinkController;
use App\Http\Controllers\Admin\MenuCardImageController;
use App\Http\Controllers\Admin\MenuNodeController;
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

    // HOME real
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    /**
     * MENU (CLICK -> pantalla de cards)
     * SOLO UNA RUTA con path opcional
     */
    Route::get('/menu/{sectionSlug}/{path?}', [MenuController::class, 'show'])
        ->where('path', '.*')
        ->name('menu.section');

    /**
     * ADMIN (rol: admin - minúsculas)
     */
    Route::prefix('admin')->name('admin.')->middleware(['role:admin'])->group(function () {

        /**
         * MENU SUPERIOR (DB) - Editor
         */
        Route::get('/menu', [MenuNodeController::class, 'index'])->name('menu.index');

        // Cargar hijos (accordion / AJAX)
        Route::get('/menu/children/{menu_node}', [MenuNodeController::class, 'children'])->name('menu.children');

        // Crear (root o sub-opción)
        Route::post('/menu', [MenuNodeController::class, 'store'])->name('menu.store');

        // Actualizar
        Route::put('/menu/{menu_node}', [MenuNodeController::class, 'update'])->name('menu.update');

        // Eliminar (recursivo)
        Route::delete('/menu/{menu_node}', [MenuNodeController::class, 'destroy'])->name('menu.destroy');

        /**
         * Menu products (CRUD)
         */
        Route::get('/menu-products', [MenuProductController::class, 'index'])->name('menu-products.index');
        Route::post('/menu-products', [MenuProductController::class, 'store'])->name('menu-products.store');
        Route::put('/menu-products/{menu_product}', [MenuProductController::class, 'update'])->name('menu-products.update');
        Route::delete('/menu-products/{menu_product}', [MenuProductController::class, 'destroy'])->name('menu-products.destroy');

        /**
         * Menu cards (imagenes/metadata)
         * ✅ FIX: NO usamos {key} con "/" en la URL (Apache bloquea %2F)
         * Usamos {token} base64url (sin /).
         */
        Route::get('/menu-cards', [MenuCardImageController::class, 'index'])->name('menu-cards.index');
        Route::post('/menu-cards/sync', [MenuCardImageController::class, 'sync'])->name('menu-cards.sync');

        Route::get('/menu-cards/{token}/edit', [MenuCardImageController::class, 'edit'])
            ->where('token', '[A-Za-z0-9\-_]+')
            ->name('menu-cards.edit');

        Route::put('/menu-cards/{token}', [MenuCardImageController::class, 'update'])
            ->where('token', '[A-Za-z0-9\-_]+')
            ->name('menu-cards.update');

        Route::delete('/menu-cards/{token}', [MenuCardImageController::class, 'destroy'])
            ->where('token', '[A-Za-z0-9\-_]+')
            ->name('menu-cards.destroy');

        /**
         * Slides (CRUD)
         */
        Route::resource('slides', SlideController::class)->except(['show']);

        /**
         * Quick Links (CRUD)
         */
        Route::get('/quick-links', [QuickLinkController::class, 'index'])->name('quick-links.index');
        Route::post('/quick-links', [QuickLinkController::class, 'store'])->name('quick-links.store');
        Route::get('/quick-links/{quickLink}/edit', [QuickLinkController::class, 'edit'])->name('quick-links.edit');
        Route::put('/quick-links/{quickLink}', [QuickLinkController::class, 'update'])->name('quick-links.update');
        Route::delete('/quick-links/{quickLink}', [QuickLinkController::class, 'destroy'])->name('quick-links.destroy');
    });
});

require __DIR__ . '/auth.php';