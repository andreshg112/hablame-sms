<?php

namespace Andreshg112\HablameSms;

use Andreshg112\HablameSms\Exceptions\CouldNotSendNotification;
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
     * Crea una instancia recibiendo el número del cliente, la clave, y el token. Adicionalmente, puede recibir una
     * instancia de Guzzle con la que ejecutará las peticiones, útil para pruebas y para especificar un número de
     * reintentos.
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
     * @return array The response body.
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
     * @param  string  $phoneNumbers  Número telefonico a enviar SMS.
     * @param  string  $sms  Mensaje de texto a enviar.
     * @param  string|null  $datetime  [optional] Fecha de envío. Si está vacío, se envía inmediatamente.
     * @param  bool  $flash  [optional] Indica si es un mensaje flash, es decir, que ocupa la pantalla.
     * @param  bool  $priority  [optional] Indica si el mensaje es prioritario (costo adicional). Se ignora si se especifican varios destinatarios.
     * @return array The response body.
     * @throws \Andreshg112\HablameSms\Exceptions\CouldNotSendNotification
     */
    public function sendMessage(
        string $phoneNumbers,
        string $sms,
        string $datetime = null,
        bool $flash = false,
        bool $priority = false
    ): array {
        $arrayNumbers = array_values(array_filter(explode(',', $phoneNumbers)));

        if (empty($arrayNumbers)) {
            throw new CouldNotSendNotification('No phone number has been specified');
        }

        $params = [
            'flash' => (int)$flash,
            'sendDate' => isset($datetime) ? strtotime($datetime) : null,
        ];

        if (count($arrayNumbers) === 1) {
            $params += ['toNumber' => $phoneNumbers, 'sms' => $sms];

            $url = self::API_URL . 'sms/v3/send/' . ($priority ? 'priority' : 'marketing');
        } else {
            if ($priority) {
                throw new CouldNotSendNotification('Priority SMS can only be sent to one number');
            }

            $params['bulk'] = array_map(function ($phoneNumber) use ($sms) {
                return ['numero' => $phoneNumber, 'sms' => $sms];
            }, $arrayNumbers);

            $url = self::API_URL . 'sms/v3/send/marketing/bulk';
        }

        $response = $this->http->post($url, ['headers' => $this->getAuthHeaders(), 'json' => array_filter($params)]);

        return json_decode((string)$response->getBody(), true);
    }
}
