<?php

namespace App\Providers;

use App\Http\Arquivei\ArquiveiClient;
use App\Http\Arquivei\ArquiveiClientInterface;
use Illuminate\Support\ServiceProvider;

class ArquiveiProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ArquiveiClientInterface::class, ArquiveiClient::class);
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
