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

use App\Http\Controllers\MenuProductPublicController;
use App\Http\Controllers\Admin\MenuProductDetailController;

use App\Http\Controllers\Admin\MenuProductVariantColorsController;

// ✅ NUEVO: subir PDFs del producto (Ficha técnica / Instructivo)
use App\Http\Controllers\Admin\MenuProductFilesController;

use App\Http\Controllers\MaterialVisualController;
use App\Http\Controllers\ComunicadoController;
use App\Http\Controllers\DocumentacionController;

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

    Route::get('/documentacion', [DocumentacionController::class, 'index'])
        ->name('documentacion.index');

    Route::middleware(['role:admin|docs_admin'])->group(function () {
        Route::post('/documentacion', [DocumentacionController::class, 'store'])
            ->name('documentacion.store');

        Route::put('/documentacion/{documento}', [DocumentacionController::class, 'update'])
            ->name('documentacion.update');

        Route::delete('/documentacion/{documento}', [DocumentacionController::class, 'destroy'])
            ->name('documentacion.destroy');
    });


    Route::get('/material-visual', [MaterialVisualController::class, 'index'])
        ->name('material-visual.index');


    Route::get('/dashboard', function () {
        return redirect()->route('home');
    })->name('dashboard');

    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::get('/comunicados', [ComunicadoController::class, 'index'])
        ->name('comunicados.index');


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


        Route::post('/material-visual/items', [\App\Http\Controllers\MaterialVisualController::class, 'store'])
            ->name('material-visual.items.store');

        Route::put('/material-visual/items/{item}', [\App\Http\Controllers\MaterialVisualController::class, 'update'])
            ->name('material-visual.items.update');

        Route::delete('/material-visual/items/{item}', [\App\Http\Controllers\MaterialVisualController::class, 'destroy'])
            ->name('material-visual.items.destroy');
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

        // ✅ NUEVO: pantalla para administrar un nodo (crear hijos + asignar PDF)
        Route::get('/menu/{menu_node}/manage', [MenuNodeController::class, 'manage'])
            ->name('menu.manage');

        // ✅ NUEVO: crear hijo dentro de un nodo específico
        Route::post('/menu/{menu_node}/children', [MenuNodeController::class, 'storeChild'])
            ->name('menu.children.store');

        // ✅ NUEVO: subir PDF a un nodo (y deja url en el nodo)
        Route::post('/menu/{menu_node}/upload', [MenuNodeController::class, 'uploadPdf'])
            ->name('menu.upload');

        Route::delete('/menu/{menu_node}/pdf', [MenuNodeController::class, 'deletePdf'])
            ->name('menu.pdf.destroy');
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
        | ✅ NUEVO: PDFs del producto (Ficha técnica / Instructivo)
        |--------------------------------------------------------------------------
        */
        Route::post('/menu-products/{menu_product}/files', [MenuProductFilesController::class, 'update'])
            ->name('menu-products.files.update');

        /*
        |--------------------------------------------------------------------------
        | ✅ ASIGNACIÓN de imágenes + detalle (NO edita colores)
        |--------------------------------------------------------------------------
        */
        Route::get('/product-details/{menu_product}/edit', [MenuProductDetailController::class, 'edit'])
            ->name('product-details.edit');

        Route::put('/product-details/{menu_product}', [MenuProductDetailController::class, 'update'])
            ->name('product-details.update');

        Route::post('/product-details/{menu_product}/cover', [MenuProductDetailController::class, 'setCover'])
            ->name('product-details.set-cover');

        /*
        |--------------------------------------------------------------------------
        | ✅ Editor SOLO de colores (Estructura/Melamina)
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
        | PDFs Lista de precios (dinámico)
        |--------------------------------------------------------------------------
        */
        Route::get('/price-list-pdfs', [PriceListPdfController::class, 'index'])
            ->name('price-list-pdfs.index');

        Route::post('/price-list-pdfs', [PriceListPdfController::class, 'store'])
            ->name('price-list-pdfs.store');

        Route::post('/price-list-pdfs/{menu_node}/upload', [PriceListPdfController::class, 'upload'])
            ->name('price-list-pdfs.upload');

        Route::post('/price-list-pdfs/{item}/toggle', [PriceListPdfController::class, 'toggle'])
            ->name('price-list-pdfs.toggle');

        Route::delete('/price-list-pdfs/{item}', [PriceListPdfController::class, 'destroy'])
            ->name('price-list-pdfs.destroy');

        /*
        |--------------------------------------------------------------------------
        | Upload videos capacitaciones
        |--------------------------------------------------------------------------
        */
        Route::post('/training-media/upload', [TrainingMediaController::class, 'upload'])
            ->name('training-media.upload');

        /*
        |--------------------------------------------------------------------------
        | Users admin
        |--------------------------------------------------------------------------
        */
        Route::get('/users', [\App\Http\Controllers\Admin\UserAdminController::class, 'index'])
            ->name('users.index');

        Route::put('/users/{user}/role', [\App\Http\Controllers\Admin\UserAdminController::class, 'updateRole'])
            ->name('users.role');

        Route::post('/material-colors', [\App\Http\Controllers\Admin\MaterialColorController::class, 'store'])
            ->name('material-colors.store');

        Route::delete('/material-colors/{material_color}', [\App\Http\Controllers\Admin\MaterialColorController::class, 'destroy'])
            ->name('material-colors.destroy');

    });
});

require __DIR__ . '/auth.php';