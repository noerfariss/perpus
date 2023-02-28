<?php

namespace App\Providers;

use App\Service\WeblogService;
use Illuminate\Support\ServiceProvider;

class WebLogServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('Weblog', function($app){
            return new WeblogService;
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
