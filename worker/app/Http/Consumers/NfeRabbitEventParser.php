<?php


namespace App\Http\Consumers;

use Belamov\RabbitMQListener\AbstractEventParser;

class NfeRabbitEventParser extends AbstractEventParser
{

    private $nfeConsumerRabbit;

    /**
     * NfeRabbitEventParser constructor.
     */
    public function __construct(NfeConsumerInterface $nfeConsumerRabbit)
    {
        $this->nfeConsumerRabbit = $nfeConsumerRabbit;
    }

    public function setEventPayload(string $payload)
    {
        echo 'Payload: ' . $payload;
        $this->nfeConsumerRabbit->consumeNfe($payload);
    }

    public function getEventName()
    {
        // TODO: Implement getEventName() method.
    }

    public function getEventPayload()
    {
        // TODO: Implement getEventPayload() method.
    }
}
