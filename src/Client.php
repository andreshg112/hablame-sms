<?php

namespace Andreshg112\HablameSms;

use GuzzleHttp\Client as GuzzleClient;

class Client
{
    /** @var string $api Clave API suministrada por Háblame SMS. */
    protected $api = null;

    /** @var string $client Número del cliente en Háblame SMS. */
    protected $client = null;

    /**
     * Crea una instancia recibiendo el número del cliente y la clave.
     *
     * @param string $client
     * @param string $api
     */
    public function __construct(string $client, string $api)
    {
        $this->client = $client;

        $this->api = $api;
    }

    /**
     * Consulta el saldo.
     *
     * @return array
     */
    public function checkBalance()
    {
        $http = new GuzzleClient();

        $url = 'https://api.hablame.co/saldo/consulta/index.php';

        $params = ['cliente' => $this->client, 'api' => $this->api];

        $response = $http->post($url, ['form_params' => $params]);

        return json_decode((string)$response->getBody(), true);
    }
}
