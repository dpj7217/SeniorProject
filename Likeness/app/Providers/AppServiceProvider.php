<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Providers\Services\API;
use App\Providers\Services\Search;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

        $this->app->bind('api', function() {
            return new API();
        });
        
        $this->app->bind('search', function() {
            return new Search();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
