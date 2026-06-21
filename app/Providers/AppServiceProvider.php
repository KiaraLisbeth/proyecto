<?php

namespace App\Providers;

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
        $institucion = [];
        if (\Illuminate\Support\Facades\Storage::exists('config/institucion.json')) {
            $institucion = json_decode(\Illuminate\Support\Facades\Storage::get('config/institucion.json'), true);
        }
        \Illuminate\Support\Facades\View::share('institucionGlobal', $institucion);
    }
}
