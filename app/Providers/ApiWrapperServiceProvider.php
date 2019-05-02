<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Library\Services\ApiWrapper;
use App\Library\Services\Geth;

class ApiWrapperServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //       

        $this->app->bind('ApiWrapper', App\Library\Services\ApiWrapper::class);
        
    }
}
