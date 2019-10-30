<?php

namespace App\Http\Helper;

use GuzzleHttp\Client;

class GuzzleHttpService implements HttpClientInterface {

    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function doGet(String $url, $headers =null)
    {
        $headers["Content-Type"] ="application/json";
        $response = $this->client->get($url, ['headers'=> $headers]);
        return $response;
    }

    public function doPost(String $url, Object $data)
    {

    }
}
