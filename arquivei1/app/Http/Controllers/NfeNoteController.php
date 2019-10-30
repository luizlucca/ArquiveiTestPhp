<?php

namespace App\Http\Controllers;

use App\Http\Arquivei\ArquiveiClient;
use App\Http\Arquivei\ArquiveiClientInterface;
use App\Http\Cache\CacheInterface;
use App\Http\Publisher\PublisherInterface;
use App\NfeNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class NfeNoteController extends Controller
{
    private $arquiveiClient;
    private $publisher;
    private $cache;

    /**
     * NfeNoteController constructor.
     * @param ArquiveiClientInterface $client
     * @param PublisherInterface $publisher
     * @param CacheInterface $cache
     */
    public function __construct(ArquiveiClientInterface $client, PublisherInterface $publisher, CacheInterface $cache)
    {
        $this->arquiveiClient = $client;
        $this->publisher = $publisher;
        $this->cache = $cache;
    }

    public function fetchFromArquivei()
    {
        $response = $this->arquiveiClient->getAllNfeNotes();
        //why doesn't it work?
//         DB::table('nfe_notes')->insert($response);

        foreach ($response as $nfeNode) {
            try {
                $this->publisher->publish($nfeNode->toJson());
            } catch (\Exception $e) {
                Log::error($e);
            }
        }

        return \response()->json(["status" => "created"], 201);
    }

    public function findByAccessKey(String $acessKey){
        $nfeValue =$this->cache->get($acessKey);

        if($nfeValue ==null){
            $nfeNote = NfeNote::find($acessKey);

            if($nfeNote ===null){
                return response()->json(['accessKey'=> $acessKey, 'error'=>'Not found'], 400);
            }
            $nfeValue = $nfeNote->value;
        }

        return response()->json(['accessKey'=> $acessKey, "value"=>$nfeValue],200);
    }
}
