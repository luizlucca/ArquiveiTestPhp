<?php

namespace App\Providers;

use App\Http\Cache\CacheInterface;
use App\Http\Cache\RedisCache;
use Illuminate\Support\ServiceProvider;

class RedisProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(CacheInterface::class, RedisCache::class);
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
