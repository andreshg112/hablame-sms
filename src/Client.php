<?php

namespace Andreshg112\HablameSms;

use GuzzleHttp\Client as GuzzleClient;

class Client
{
    private const API_URL = 'https://api103.hablame.co/api/';

    /** Clave API suministrada por Háblame SMS. */
    private string $apikey;

    /** Número del cliente en Háblame SMS. */
    private string $account;

    /** Cliente de Guzzle. */
    private GuzzleClient $http;

    /** Token enviado por correo al cliente desde el panel de Háblame SMS. */
    private string $token;

    /**
     * Crea una instancia recibiendo el número del cliente y la clave.
     */
    public function __construct(string $account, string $apikey, string $token, GuzzleClient $http = null)
    {
        $this->account = $account;

        $this->apikey = $apikey;

        $this->token = $token;

        $this->http = $http ?? new GuzzleClient();
    }

    /**
     * Consulta el saldo.
     */
    public function checkBalance(): array
    {
        $url = self::API_URL . 'account/v1/status';

        $params = ['account' => $this->account, 'apikey' => $this->apikey, 'token' => $this->token];

        $response = $this->http->get($url, ['headers' => $params]);

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
            'cliente' => $this->account,
            'api' => $this->apikey,
            'numero' => $phoneNumbers,
            'sms' => $sms,
            'fecha' => $datetime,
            'referencia' => $reference,
        ];

        $response = $this->http->post($url, ['query' => array_filter($params)]);

        return json_decode((string)$response->getBody(), true);
    }
}
