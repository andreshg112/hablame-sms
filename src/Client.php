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

        $response = $this->http->get($url, ['headers' => $this->getAuthHeaders()]);

        return json_decode((string)$response->getBody(), true);
    }

    private function getAuthHeaders(): array
    {
        return [
            'account' => $this->account,
            'apikey' => $this->apikey,
            'token' => $this->token,
        ];
    }

    /**
     * Envía un mensaje de texto (SMS) al destinatario indicado.
     *
     * @param string $phoneNumber Número telefonico a enviar SMS.
     * @param string $sms Mensaje de texto a enviar.
     * @param string|null $datetime [optional] Fecha de envío. Si está vacío, se envía inmediatamente.
     * @param string|null $reference [optional] Número de referencia o nombre de campaña.
     * @param bool $flash [optional] Indica si es un mensaje flash, es decir, que ocupa la pantalla.
     * @param bool $priority [optional] Indica si el mensaje es prioritario (costo adicional).
     * @return array
     */
    public function sendMessage(
        string $phoneNumber,
        string $sms,
        string $datetime = null,
        string $reference = null,
        bool $flash = false,
        bool $priority = false
    ): array {
        $url = self::API_URL . 'sms/v3/send/' . ($priority ? 'priority' : 'marketing');

        $params = [
            'toNumber' => $phoneNumber,
            'sms' => $sms,
            'flash' => (int)$flash,
            'sendDate' => isset($datetime) ? strtotime($datetime) : null,
            'reference_1' => $reference,
        ];

        $response = $this->http->post($url, ['headers' => $this->getAuthHeaders(), 'json' => array_filter($params)]);

        return json_decode((string)$response->getBody(), true);
    }
}
