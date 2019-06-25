# Cliente de Háblame SMS (Colombia) para PHP (Optimizado para Laravel)

[![Latest Version on Packagist](https://img.shields.io/packagist/v/andreshg112/hablame-sms.svg?style=flat-square)](https://packagist.org/packages/andreshg112/hablame-sms)
[![Build Status](https://travis-ci.com/andreshg112/hablame-sms.svg?branch=master)](https://travis-ci.com/andreshg112/hablame-sms)
[![StyleCI](https://github.styleci.io/repos/181806042/shield?branch=master)](https://github.styleci.io/repos/181806042)
[![Quality Score](https://img.shields.io/scrutinizer/g/andreshg112/hablame-sms.svg?style=flat-square)](https://scrutinizer-ci.com/g/andreshg112/hablame-sms)
[![Code Coverage](https://scrutinizer-ci.com/g/andreshg112/hablame-sms/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/andreshg112/hablame-sms/?branch=master)
[![Maintainability](https://api.codeclimate.com/v1/badges/0e7fe998a23bf36ec963/maintainability)](https://codeclimate.com/github/andreshg112/hablame-sms/maintainability)
[![Total Downloads](https://img.shields.io/packagist/dt/andreshg112/hablame-sms.svg?style=flat-square)](https://packagist.org/packages/andreshg112/hablame-sms)

Este paquete facilita la conexión con la API de [Háblame SMS](https://www.hablame.co) para enviar mensajes de texto y consultar el saldo. Además, permite el envío de notificaciones a través de SMS usando el [sistema de notificaciones de Laravel](https://laravel.com/docs/5.5/notifications).

## Requerimientos

-   PHP >= 7.1
-   Laravel >= 5.3 (Requerido solo si vas a usar la fachada o el canal de notificaciones).

## Instalación

Puedes instalar el paquete a través de composer:s

```bash
composer require andreshg112/hablame-sms
```

## Uso

```php
/**
 * Crear instancia.
 *
 * $client: Número de cliente.
 * $api: Clave de la API.
 * $guzzle: [opcional] Sirve para pasar un cliente de Guzzle (\GuzzleHttp\Client) configurado,
 * por ejemplo, en pruebas unitarias.
 */
$hablame = new \Andreshg112\HablameSms\Client($client, $api, $guzzle);

/**
 * Consultar saldo.
 *
 * Retorna un array con la respuesta del servidor de Háblame.
 */
$response = $hablame->checkBalance();

/**
 * Enviar mensaje.
 *
 * $phoneNumbers: Número(s) telefónico(s) separados por coma.
 * $sms: Cuerpo del mensaje.
 * $datetime: [opcional] Fecha a enviar el mensaje. Formato en PHP: 'Y-m-d H:i:s'
 * $referencia: [opcional] Nombre de campaña.
 *
 * Retorna un array con la respuesta del servidor de Háblame.
 */
$response = $hablame->sendMessage($phoneNumbers, $sms, $datetime, $reference);
```

### Laravel

#### Fachada y alias

En Laravel puedes usar la fachada o el alias para ejecutar las funciones anteriores. Primero debes agregar las credenciales de Háblame a `config/services.php` así:

```php
return [
    // ...
    'hablame_sms' => [
        'api' => env('HABLAME_API', ''),
        'cliente' => env('HABLAME_CLIENTE', ''),

        /**
         * Si deseas agregar tu propio cliente de Guzzle, en vez de usar uno por defecto,
         * haz que el callback retorne el respectivo cliente.
         * Si quieres usar uno por defecto, quita este parámetro o asígnalo null.
         */
        'guzzle' => function (): \GuzzleHttp\Client {
            return createHttpClient(logger());
        },
    ],
    // ...
];
```

Ahora puedes hacer:

```php
$response = \Andreshg112\HablameSms\Facade::checkBalance();

$response = \Andreshg112\HablameSms\Facade::sendMessage($phoneNumbers, $sms, $datetime, $reference);

// o

$response = \Hablame::checkBalance();

$response = \Hablame::sendMessage($phoneNumbers, $sms, $datetime, $reference);
```

> Si usas Laravel < 5.5, debes agregar \Andreshg112\HablameSms\HablameSmsServiceProvider al array de `providers` en `config/app.php`.

#### Notificaciones

Puedes enviar notificaciones usando el [sistema integrado en Laravel](https://laravel.com/docs/5.5/notifications) que facilita su envío. Ten en cuenta que debes saber usar las notificaciones de Laravel antes de usar este paquete.

En tu notificación, añade `HablameChannel::class` al array que retorna la función `via()`:

```php
use \Andreshg112\HablameSms\HablameChannel;

public function via($notifiable)
{
    return [HablameChannel::class];
}
```

Luego, crea un método llamado `toHablameNotification()` en tu clase:

```php
use \Andreshg112\HablameSms\HablameMessage;

public function toHablameNotification($notifiable)
{
    return (new HablameMessage)
        ->phoneNumbers($phoneNumbers)
        ->sms($sms)
        ->datetime($datetime)
        ->reference($reference);

    // o

    return new HablameMessage($phoneNumbers, $sms, $datetime, $reference);
}
```

### Pruebas

```bash
composer test
```

### Registro de cambios

Por favor mira el historial de [versiones](../../releases) para más información sobre lo que ha cambiado recientemente.

## Contribuir

Por favor mira las [guías de contribución](CONTRIBUTING.md) (en inglés) para conocer los detalles.

### Seguridad

Si descubres alguna vulnerabilidad, por favor escríbeme a andreshg112@gmail.com en vez de usar el [seguimiento de indicendias](../../issues) de GitHub.

## Créditos

-   [Andrés Herrera García](https://github.com/andreshg112)
-   [Todos los colaboradores](../../contributors)

## Licencia

La Licencia MIT. Por favor mira el [archivo de licencia](LICENSE.md) para más información.

## PHP Package Boilerplate

This package was generated using the [PHP Package Boilerplate](https://laravelpackageboilerplate.com).
