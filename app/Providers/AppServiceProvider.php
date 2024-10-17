<?php

namespace App\Providers;

use App\RSS\SportsRSSParser;
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
        $this->app->bind(
            'SportsParser',
            SportsRSSParser::class
        );
    }
}
