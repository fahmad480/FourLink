<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Config;
use App\Models\SystemParameter;

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

        // Share app name from system parameters to all views
        View::composer('*', function ($view) {
            try {
                $appName = SystemParameter::getValue('app_name');
                $view->with('appName', $appName ?: config('app.name'));
            } catch (\Exception $e) {
                // If system_parameters table doesn't exist yet (e.g., during migration)
                $view->with('appName', config('app.name'));
            }
        });

        // Override app name config if system parameter exists
        try {
            $appName = SystemParameter::getValue('app_name');
            if ($appName) {
                Config::set('app.name', $appName);
            }
        } catch (\Exception $e) {
            // Ignore if table doesn't exist yet
        }
    }
}
