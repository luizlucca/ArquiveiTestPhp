<?php

namespace Tests\Unit;

use App\Http\Cache\RedisCache;
use App\Http\Consumers\NfeConsumerInterface;
use App\NfeNote;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NfeConsumerRabbitTest extends TestCase
{
    use RefreshDatabase;

    private $nfeConsumerRabbit;
    private $mockRedis;

    public function setUp(): void
    {
        parent::setUp();

        $this->mockRedis = $this->mock(RedisCache::class);

        $this->nfeConsumerRabbit = $this->app->make(NfeConsumerInterface::class);
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testConsumeNfeWithoutCache()
    {
        $this->mockRedis
            ->shouldReceive('get')
            ->andReturn(null);

        $this->mockRedis
            ->shouldReceive('put')
            ->andReturn();


        $this->nfeConsumerRabbit->consumeNfe('{"id":"35171154759614000180550010000716181001474211","value":7293.45,"created_at":"2019-10-30 15:02:28","updated_at":"2019-10-30 15:02:28"}');

        $savedNfe = NfeNote::find('35171154759614000180550010000716181001474211');

        $this->assertEquals($savedNfe->id,'35171154759614000180550010000716181001474211' );
        $this->assertEquals($savedNfe->value,'7293.45' );
    }

    public function testConsumeNfeWithCache(){
        $this->mockRedis
            ->shouldReceive('get')
            ->andReturn(21.2);

        $nfe = $this->nfeConsumerRabbit->consumeNfe('{"id":"123","value":21.2,"created_at":"2019-10-30 15:02:28","updated_at":"2019-10-30 15:02:28"}');
        $this->assertEquals($nfe->id, '123');
        $this->assertEquals($nfe->value, 21.2);
    }
}
