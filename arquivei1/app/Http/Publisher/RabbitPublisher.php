<?php


namespace App\Http\Publisher;


class RabbitPublisher implements PublisherInterface
{

    private $routingKey;
    private $exchange;
    private $queue;

    /**
     * RabbitPublisher constructor.
     * @param $routingKey
     * @param $exchange
     * @param $queue
     */
    public function __construct()
    {
        $this->routingKey = env('RABBIT.PUBLISH.ROUTEKEY');
        $this->exchange = env('RABBIT.PUBLISH.EXCHANGE');
        $this->queue = env('RABBIT.PUBLISH.QUEUE');
    }

    public function publish(String $message)
    {
        app('amqp')->publish($message, $this->routingKey, [
            'exchange' => [
                'declare' => true,
                'type' => 'direct',
                'name' => $this->exchange,
            ],
        ]);
    }
}
