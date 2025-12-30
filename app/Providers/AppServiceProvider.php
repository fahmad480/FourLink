<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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
        // Force root URL untuk menangani subdirektori
        if ($appUrl = config('app.url')) {
            URL::forceRootUrl($appUrl);
        }

        // // Deteksi jika menggunakan HTTPS
        // if (config('app.env') === 'production' || request()->server('HTTPS') === 'on') {
        //     URL::forceScheme('https');
        // }
    }
}
