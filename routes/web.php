<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\GlobalSearchController;

use App\Http\Controllers\Admin\SlideController;
use App\Http\Controllers\Admin\QuickLinkController;
use App\Http\Controllers\Admin\MenuCardImageController;
use App\Http\Controllers\Admin\MenuNodeController;
use App\Http\Controllers\Admin\MenuProductController;
use App\Http\Controllers\Admin\FooterLinkController;

use App\Http\Controllers\Admin\PriceListPdfController;
use App\Http\Controllers\Admin\TrainingMediaController;

use App\Http\Controllers\Admin\MenuHeroController;

// ✅ NUEVO (detalle público + editor admin)
use App\Http\Controllers\MenuProductPublicController;
use App\Http\Controllers\Admin\MenuProductDetailController;

// ✅ NUEVO: editor SOLO de colores
use App\Http\Controllers\Admin\MenuProductVariantColorsController;

/*
|--------------------------------------------------------------------------
| ROOT
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| BUSCADOR GLOBAL
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/buscar', [GlobalSearchController::class, 'index'])
        ->name('search.global');
});

/*
|--------------------------------------------------------------------------
| RUTAS PROTEGIDAS
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', function () {
        return redirect()->route('home');
    })->name('dashboard');

    Route::get('/home', [HomeController::class, 'index'])->name('home');

    /*
    |--------------------------------------------------------------------------
    | MENU
    |--------------------------------------------------------------------------
    */
    Route::get('/menu/{sectionSlug}/{path?}', [MenuController::class, 'show'])
        ->where('path', '.*')
        ->name('menu.section');

    /*
    |--------------------------------------------------------------------------
    | ✅ DETALLE PÚBLICO DE PRODUCTO
    |--------------------------------------------------------------------------
    */
    Route::get('/producto/{menu_product}', [MenuProductPublicController::class, 'show'])
        ->name('menu.product.show');

    /*
    |--------------------------------------------------------------------------
    | ADMIN
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin')->name('admin.')->middleware(['role:admin'])->group(function () {

        Route::post('/footer-links/bulk', [FooterLinkController::class, 'bulkUpdate'])
            ->name('footer_links.bulk');

        // MENU SUPERIOR (DB)
        Route::get('/menu', [MenuNodeController::class, 'index'])->name('menu.index');

        Route::get('/menu/children/{menu_node}', [MenuNodeController::class, 'children'])
            ->name('menu.children');

        Route::post('/menu', [MenuNodeController::class, 'store'])
            ->name('menu.store');

        Route::put('/menu/{menu_node}', [MenuNodeController::class, 'update'])
            ->name('menu.update');

        Route::delete('/menu/{menu_node}', [MenuNodeController::class, 'destroy'])
            ->name('menu.destroy');

        /*
        |--------------------------------------------------------------------------
        | Menu Products (CRUD)
        |--------------------------------------------------------------------------
        */
        Route::get('/menu-products', [MenuProductController::class, 'index'])
            ->name('menu-products.index');

        Route::post('/menu-products', [MenuProductController::class, 'store'])
            ->name('menu-products.store');

        Route::put('/menu-products/{menu_product}', [MenuProductController::class, 'update'])
            ->name('menu-products.update');

        Route::delete('/menu-products/{menu_product}', [MenuProductController::class, 'destroy'])
            ->name('menu-products.destroy');

        /*
        |--------------------------------------------------------------------------
        | ✅ ASIGNACIÓN de imágenes + detalle (NO edita colores)
        |--------------------------------------------------------------------------
        */
        Route::get('/product-details/{menu_product}/edit', [MenuProductDetailController::class, 'edit'])
            ->name('product-details.edit');

        Route::put('/product-details/{menu_product}', [MenuProductDetailController::class, 'update'])
            ->name('product-details.update');

        /*
        |--------------------------------------------------------------------------
        | ✅ Editor SOLO de colores (Acero/Melamina)
        |--------------------------------------------------------------------------
        */
        Route::get('/product-variants/{menu_product}/colors', [MenuProductVariantColorsController::class, 'edit'])
            ->name('product-variants.colors.edit');

        Route::put('/product-variants/{menu_product}/colors', [MenuProductVariantColorsController::class, 'update'])
            ->name('product-variants.colors.update');

        /*
        |--------------------------------------------------------------------------
        | Menu Cards
        |--------------------------------------------------------------------------
        */
        Route::get('/menu-cards', [MenuCardImageController::class, 'index'])
            ->name('menu-cards.index');

        Route::post('/menu-cards/sync', [MenuCardImageController::class, 'sync'])
            ->name('menu-cards.sync');

        Route::get('/menu-cards/{token}/edit', [MenuCardImageController::class, 'edit'])
            ->where('token', '[A-Za-z0-9\-_]+')
            ->name('menu-cards.edit');

        Route::put('/menu-cards/{token}', [MenuCardImageController::class, 'update'])
            ->where('token', '[A-Za-z0-9\-_]+')
            ->name('menu-cards.update');

        Route::delete('/menu-cards/{token}', [MenuCardImageController::class, 'destroy'])
            ->where('token', '[A-Za-z0-9\-_]+')
            ->name('menu-cards.destroy');

        /*
        |--------------------------------------------------------------------------
        | HERO / SLIDER por nivel
        |--------------------------------------------------------------------------
        */
        Route::get('/menu-hero/{token}/edit', [MenuHeroController::class, 'edit'])
            ->where('token', '[A-Za-z0-9\-_]+')
            ->name('menu-hero.edit');

        Route::put('/menu-hero/{token}', [MenuHeroController::class, 'update'])
            ->where('token', '[A-Za-z0-9\-_]+')
            ->name('menu-hero.update');

        /*
        |--------------------------------------------------------------------------
        | Slides
        |--------------------------------------------------------------------------
        */
        Route::resource('slides', SlideController::class)->except(['show']);

        /*
        |--------------------------------------------------------------------------
        | Quick Links
        |--------------------------------------------------------------------------
        */
        Route::get('/quick-links', [QuickLinkController::class, 'index'])
            ->name('quick-links.index');

        Route::post('/quick-links', [QuickLinkController::class, 'store'])
            ->name('quick-links.store');

        Route::get('/quick-links/{quickLink}/edit', [QuickLinkController::class, 'edit'])
            ->name('quick-links.edit');

        Route::put('/quick-links/{quickLink}', [QuickLinkController::class, 'update'])
            ->name('quick-links.update');

        Route::delete('/quick-links/{quickLink}', [QuickLinkController::class, 'destroy'])
            ->name('quick-links.destroy');

        /*
        |--------------------------------------------------------------------------
        | Footer Links
        |--------------------------------------------------------------------------
        */
        Route::put('/footer-links/{footer_link}', [FooterLinkController::class, 'update'])
            ->name('footer-links.update');

        Route::post('/footer-links/{footer_link}/upload', [FooterLinkController::class, 'upload'])
            ->name('footer-links.upload');

        /*
        |--------------------------------------------------------------------------
        | PDFs Lista de precios
        |--------------------------------------------------------------------------
        */
        Route::get('/price-list-pdfs', [PriceListPdfController::class, 'index'])
            ->name('price-list-pdfs.index');

        Route::post('/price-list-pdfs/{menu_node}', [PriceListPdfController::class, 'upload'])
            ->name('price-list-pdfs.upload');

        /*
        |--------------------------------------------------------------------------
        | Upload videos capacitaciones
        |--------------------------------------------------------------------------
        */
        Route::post('/training-media/upload', [TrainingMediaController::class, 'upload'])
            ->name('training-media.upload');
    });
});

require __DIR__ . '/auth.php';