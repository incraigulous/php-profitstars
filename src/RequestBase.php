<?php

namespace jdavidbakr\ProfitStars;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ServerException;
use League\Plates\Engine as Plates;
use SimpleXMLElement;

abstract class RequestBase {

    protected $endpoint;
    public $faultcode;
    public $faultstring;
    public $credentials;
    public $views;

    public function __construct(array $credentials) {
        $this->setCredentials($credentials);
        $this->initViews();
    }

    /**
     * Initialize plates.
     */
    protected function initViews() {
        $this->views = new Plates(dirname(__FILE__) . '/views');
    }

    /**
     * Load the credentials.
     * @param $credentials
     * @throws \Exception
     */
    public function setCredentials($credentials)
    {
        if (empty($credentials['store-id'])) throw new \Exception('ProfitStars store key is required.');
        if (empty($credentials['store-key'])) throw new \Exception('ProfitStars store id is required.');
        if (empty($credentials['entity-id'])) throw new \Exception('ProfitStars entity id is required.');
        if (empty($credentials['location-id'])) throw new \Exception('ProfitStars location id is required.');
        $this->credentials = $credentials;
    }

    /**
     * @return mixed
     */
    public function getCredentials()
    {
        return $this->credentials;
    }

    public function Call($requestXml)
    {
        $client = new Client();

        try {
            $response = $client->post($this->endpoint,[
                'headers'=>[
                    'Content-Type'=>'text/xml;charset=UTF-8',
                ],
                'body'=>$requestXml,
            ]);
        } catch (ServerException $e) {
            $response = $e->getResponse();
        }
        $body = $response->getBody();
        $xml = new SimpleXMLElement((string)$body);
        $body = $xml->children('soap',true)->Body;
        $fault = $body->children('soap',true)->Fault;
        if($body->children() && $body->children()->children()) {
            return $body->children()->children();
        } else if ($fault) {
            $this->faultcode = (string)$fault->children()->faultcode[0];
            $this->faultstring = (string)$fault->children()->faultstring[0];
            return false;
        } else {
            abort(500, "Unknown SOAP response");
        }
    }

}
