<?php

namespace Andreshg112\HablameSms;

use GuzzleHttp\Client as GuzzleClient;

class Client
{
    /** @var string $api Clave API suministrada por Háblame SMS. */
    protected $api = null;

    /** @var string $client Número del cliente en Háblame SMS. */
    protected $client = null;

    /** @var GuzzleClient $http Cliente de Guzzle. */
    protected $http = null;

    /**
     * Crea una instancia recibiendo el número del cliente y la clave.
     *
     * @param string $client
     * @param string $api
     * @param GuzzleClient $http
     */
    public function __construct(string $client, string $api, GuzzleClient $http = null)
    {
        $this->client = $client;

        $this->api = $api;

        $this->http = $http ?? new GuzzleClient();
    }

    /**
     * Consulta el saldo.
     *
     * @return array
     */
    public function checkBalance()
    {
        $url = 'https://api.hablame.co/saldo/consulta/index.php';

        $params = ['cliente' => $this->client, 'api' => $this->api];

        $response = $this->http->post($url, ['form_params' => $params]);

        return json_decode((string)$response->getBody(), true);
    }

    /**
     * Envía un mensaje de texto (SMS) al destinatario o destinatarios indicados.
     *
     * @param int|string $phoneNumbers Número(s) telefonicos a enviar SMS (separados por una coma).
     * @param string $sms Mensaje de texto a enviar.
     * @param string $reference [optional] Numero de referencia o nombre de campaña.
     * @return array
     */
    public function sendMessage($phoneNumbers, $sms, $reference = null)
    {
        $url = 'https://api.hablame.co/sms/envio';

        $params = [
            'cliente' => $this->client,
            'api' => $this->api,
            'numero' => $phoneNumbers,
            'sms' => $sms,
            'referencia' => $reference,
        ];

        $response = $this->http->post($url, ['form_params' => $params]);

        return json_decode((string)$response->getBody(), true);
    }
}
