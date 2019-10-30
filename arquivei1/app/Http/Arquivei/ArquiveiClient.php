<?php

namespace App\Http\Arquivei;

use App\Http\Helper\GuzzleHttpService;
use App\Http\Helper\HttpClientInterface;
use App\Http\Publisher\PublisherInterface;
use App\NfeNote;


class ArquiveiClient implements ArquiveiClientInterface
{
    protected $httpClient;

    private $url;
    private $arqXApiHeader;
    private $arqXApiValue;
    private $arqXApiKeyHeader;
    private $arqXApiKeyValue;

    /**
     * ArquiveiClient constructor.
     * @param HttpClientInterface $client
     */
    public function __construct(HttpClientInterface $client)
    {
        $this->httpClient = $client;
        $this->url = 'https://sandbox-api.arquivei.com.br/v1/nfe/received';
        $this->arqXApiHeader = env('ARQUIVEI.X_API_ID.HEADER');
        $this->arqXApiValue = env('ARQUIVEI.X_API_ID.VALUE');
        $this->arqXApiKeyHeader = env('ARQUIVEI.X_API_KEY.HEADER');
        $this->arqXApiKeyValue = env('ARQUIVEI.X_API_KEY.VALUE');
    }

    public function getAllNfeNotes()
    {
        $response = $this->httpClient->doGet($this->url, [
            $this->arqXApiHeader => $this->arqXApiValue,
            $this->arqXApiKeyHeader => $this->arqXApiKeyValue
        ]);

        $nfeResponse = json_decode($response->getBody());

        $decodeNfeList = array();

        foreach ($nfeResponse->data as $encodeXml) {

            $xmlDecoded = base64_decode($encodeXml->xml);
            $positionInit = strrpos($xmlDecoded, '<vNF>');
            $positionEnd = strrpos($xmlDecoded, '</vNF>');

            $value = substr($xmlDecoded, $positionInit + 5, $positionEnd - $positionInit - 5);

            $nfeNote = new NfeNote();
            $nfeNote->setAttribute('id', $encodeXml->access_key);
            $nfeNote->setAttribute('value', doubleval($value));
            $nfeNote->setAttribute('created_at',time());
            $nfeNote->setAttribute('updated_at',time());
//            $nfeNote->save();
            array_push($decodeNfeList, $nfeNote);
        }


        return $decodeNfeList;
    }
}
