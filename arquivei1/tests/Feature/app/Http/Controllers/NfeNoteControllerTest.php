<?php

namespace Tests\Feature;

use App\Http\Arquivei\ArquiveiClient;
use App\Http\Cache\CacheInterface;
use App\Http\Publisher\RabbitPublisher;
use App\NfeNote;
use Tests\TestCase;

class NfeNoteControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testFetchFromArquivei()
    {
        $nfeNotesFromArquivei = array(
            new NfeNote(['id'=>'1','value'=>22.2]),
            new NfeNote(['id'=>2,'value'=>11.1]));

        $mockRabbitPublisher = $this->mock(RabbitPublisher::class);
        $mockRabbitPublisher->shouldReceive('publish')
            ->andReturn();

        $mockArquiveiClient = $this->mock(ArquiveiClient::class);
        $mockArquiveiClient->shouldReceive('getAllNfeNotes')
            ->andReturn($nfeNotesFromArquivei);

        $response = $this->get('/api/fetch/arquivei');

        $response->assertStatus(201);
    }


    public function testFindByAccessKey(){

        $mockCache = $this->mock(CacheInterface::class);
        $mockCache->shouldReceive('get')
            ->andReturn(323.0);

        $response = $this->get('/api/nfe/find/101010');

        $response->assertStatus(200);
        $response->assertSee(323);

    }
}
