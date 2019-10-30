<?php

namespace App\Providers;

use App\Http\Publisher\PublisherInterface;
use App\Http\Publisher\RabbitPublisher;
use Illuminate\Support\ServiceProvider;

class RabbitProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(PublisherInterface::class, RabbitPublisher::class);
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
