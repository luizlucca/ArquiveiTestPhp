<?php

namespace App\Http\Consumers;


use App\Http\Cache\CacheInterface;
use App\NfeNote;

class NfeConsumerRabbit implements NfeConsumerInterface
{
    private $cache;

    /**
     * NfeConsumerRabbit constructor.
     */
    public function __construct(CacheInterface $cache)
    {
        $this->cache = $cache;
    }

    public function consumeNfe(String $message): NfeNote
    {

        $decodedNfe = json_decode($message, true);
        $nfeAccessKey = $decodedNfe['id'];
        $nfeValue = $decodedNfe['value'];

        if ($nfeAccessKey === null || $nfeValue === null) {
            return null;
        }

        $nfeNote = new NfeNote(['id' => $nfeAccessKey, 'value' => $nfeValue]);
        if ($this->cache->get($nfeAccessKey) === null) {

            echo 'Not in redis';
            $this->cache->put($nfeAccessKey, $nfeValue); //add to cache

            if (NfeNote::find($nfeAccessKey) === null) {
                $nfeNote->save(); //save in database
            }
        } else {
            echo 'Already in redis';
        }

        return $nfeNote;
    }
}
