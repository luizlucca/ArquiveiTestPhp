<?php


namespace App\Http\Cache;


interface CacheInterface
{
    public function  get($key);
    public function  put($key,$value);
}
