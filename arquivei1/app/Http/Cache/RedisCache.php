<?php


namespace App\Http\Cache;


use Illuminate\Support\Facades\Redis;

class RedisCache implements CacheInterface
{

    public function get($key)
    {
        return Redis::get($key);
    }
}
