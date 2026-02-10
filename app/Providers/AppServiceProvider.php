<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Support\MenuTree;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Disponible en todas las vistas como $menuTop
        View::composer('*', function ($view) {
            try {
                $view->with('menuTop', MenuTree::build());
            } catch (\Throwable $e) {
                // Si aún no hay tabla/migraciones en un entorno, no revienta
                $view->with('menuTop', []);
            }
        });
    }
}