<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;

use Illuminate\Support\Facades\DB;

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
    public function boot()
    {

         require_once app_path('helper.php');
        
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }
        DB::statement("SET time_zone = '+05:30'");
    }
}
