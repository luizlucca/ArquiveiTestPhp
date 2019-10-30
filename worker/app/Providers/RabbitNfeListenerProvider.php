<?php

namespace App\Providers;

use App\Http\Consumers\NfeConsumerRabbit;
use App\Http\Consumers\RabbitConsumer;
use Illuminate\Support\ServiceProvider;

class RabbitNfeListenerProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Http\Consumers\RabbitConsumer', function($app){
            return new RabbitConsumer(resolve(NfeConsumerRabbit::class));
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

    }
}
