<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\QuickLink;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Links del menú lateral derecho en todas las vistas donde se incluya el partial
        View::composer('partials.right-quick-menu', function ($view) {
            $links = QuickLink::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get();

            $view->with('quickLinks', $links);
        });
    }
}