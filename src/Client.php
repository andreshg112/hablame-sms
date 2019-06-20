<?php

namespace Andreshg112\HablameSms;

use GuzzleHttp\Client as GuzzleClient;

class Client
{
    /** @var string $api Clave API suministrada por Háblame SMS. */
    protected $api = null;

    /** @var string $client Número del cliente en Háblame SMS. */
    protected $client = null;

    /** @var \GuzzleHttp\Client $http Cliente de Guzzle. */
    protected $http = null;

    /**
     * Crea una instancia recibiendo el número del cliente y la clave.
     *
     * @param string $client
     * @param string $api
     * @param \GuzzleHttp\Client $http
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
    public function checkBalance(): array
    {
        $url = 'https://api.hablame.co/saldo/consulta/index.php';

        $params = ['cliente' => $this->client, 'api' => $this->api];

        $response = $this->http->get($url, ['query' => $params]);

        return json_decode((string)$response->getBody(), true);
    }

    /**
     * Envía un mensaje de texto (SMS) al destinatario o destinatarios indicados.
     *
     * @param string $phoneNumbers Número(s) telefonico(s) a enviar SMS (separados por coma).
     * @param string $sms Mensaje de texto a enviar.
     * @param string|null $datetime [optional] Fecha de envío. Si está vacío, se envía inmediatamente.
     * @param string|null $reference [optional] Número de referencia o nombre de campaña.
     * @return array
     */
    public function sendMessage(
        string $phoneNumbers,
        string $sms,
        string $datetime = null,
        string $reference = null
    ): array {
        $url = 'https://api.hablame.co/sms/envio';

        $params = [
            'cliente' => $this->client,
            'api' => $this->api,
            'numero' => $phoneNumbers,
            'sms' => $sms,
            'fecha' => $datetime,
            'referencia' => $reference,
        ];

        $response = $this->http->post($url, ['query' => array_filter($params)]);

        return json_decode((string)$response->getBody(), true);
    }
}
