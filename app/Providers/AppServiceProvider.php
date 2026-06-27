<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);

        // Share cart count to all views
        view()->composer('*', function ($view) {
            $cartCount = 0;
            if (auth()->check()) {
                $user = auth()->user();
                if ($user->cart) {
                    $cartCount = $user->cart->items()->sum('quantity');
                }
            }
            $view->with('cartCount', $cartCount);
        });
    }

}
