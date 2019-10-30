<?php

namespace App\Providers;

use App\Http\Helper\GuzzleHttpService;
use App\Http\Helper\HttpClientInterface;
use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;

class GuzzleHttpServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(HttpClientInterface::class,  function($app){
            return new GuzzleHttpService(new Client());
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
