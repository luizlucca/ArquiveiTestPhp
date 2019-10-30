<?php

namespace App\Providers;

use App\Http\Consumers\NfeConsumerInterface;
use App\Http\Consumers\NfeConsumerRabbit;
use App\Http\Helper\GuzzleHttpService;
use Illuminate\Support\ServiceProvider;

class NfeRabbitListenerProvider extends ServiceProvider
{

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
//        $this->app->singleton('App\Http\Consumers\NfeConsumerInterface', function($app){
//            return new NfeConsumerRabbit($app->make('App\Http\Consumers\NfeConsumerRabbit'));
//        });

        $this->app->singleton(NfeConsumerInterface::class,NfeConsumerRabbit::class);
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
