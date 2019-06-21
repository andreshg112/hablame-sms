<?php

namespace Andreshg112\HablameSms\Tests;

use Orchestra\Testbench\TestCase;
use Andreshg112\HablameSms\Facade;
use Illuminate\Support\Facades\Config;
use Illuminate\Notifications\Notifiable;
use Andreshg112\HablameSms\HablameChannel;
use Andreshg112\HablameSms\HablameMessage;
use Illuminate\Notifications\Notification;
use Andreshg112\HablameSms\HablameSmsServiceProvider;
use Andreshg112\HablameSms\Exceptions\CouldNotSendNotification;

class ChannelTest extends TestCase
{
    /** @var \Andreshg112\HablameSms\Facade|\Andreshg112\HablameSms\Client $hablame */
    protected $hablame = null;

    /** @var \Andreshg112\HablameSms\HablameChannel $channel */
    protected $channel = null;

    /** @var TestNotification $notification */
    protected $notification = null;

    /** @var TestNotifiable $notifiable */
    protected $notifiable = null;

    public function setUp(): void
    {
        parent::setUp();

        Config::set('services.hablame_sms.cliente', '12345678');

        Config::set('services.hablame_sms.api', '9876543210');

        $this->channel = new HablameChannel;

        $this->notification = new TestNotification;

        $this->notifiable = new TestNotifiable;
    }

    protected function getPackageProviders($app)
    {
        return [HablameSmsServiceProvider::class];
    }

    /** @test */
    public function it_can_send_a_notification()
    {
        $mockedResponse = [
            'cliente' => '12345678',
            'lote_id' => 0,
            'fecha_recepcion' => '2019-06-12 22:39:58',
            'resultado' => 0,
            'resultado_t' => null,
            'sms_procesados' => 1,
            'referecia' => 'Campaña promocional',
            'ip' => '190.9.191.119',
            'sms' => [
                '1' => [
                    'id' => '1234567890123456',
                    'numero' => '3123456789',
                    'sms' => 'Mensaje de prueba',
                    'fecha_envio' => '2019-06-12 22:39:58',
                    'ind_area_nom' => 'Colombia Celular',
                    'precio_sms' => '9.00000',
                    'resultado_t' => '',
                    'resultado' => '0'
                ]
            ]
        ];

        /** @var \Andreshg112\HablameSms\HablameMessage $message */
        $message = $this->notification->toHablameNotification($this->notifiable);

        $data = $message->toArray();

        Facade::shouldReceive('sendMessage')
            ->with(
                $data['numero'],
                $data['sms'],
                $data['fecha'],
                $data['referencia']
            )
            ->once()
            ->andReturn($mockedResponse);

        $this->channel->send($this->notifiable, $this->notification);
    }

    /** @test */
    public function it_cannot_send_a_notification()
    {
        $this->expectException(CouldNotSendNotification::class);

        $this->expectExceptionMessage('No hay SMS disponibles para enviar');

        $mockedResponse = [
            'cliente' => '12345678',
            'lote_id' => 0,
            'fecha_recepcion' => '2019-06-20 00:27:24',
            'resultado' => 1,
            'resultado_t' => 'No hay SMS disponibles para enviar',
            'sms_procesados' => 0,
            'referecia' => null,
            'ip' => null,
            'sms' => null
        ];

        /** @var \Andreshg112\HablameSms\HablameMessage $message */
        $message = $this->notification->toHablameNotification($this->notifiable);

        $data = $message->toArray();

        Facade::shouldReceive('sendMessage')
            ->with(
                $data['numero'],
                $data['sms'],
                $data['fecha'],
                $data['referencia']
            )
            ->once()
            ->andReturn($mockedResponse);

        $this->channel->send($this->notifiable, $this->notification);
    }
}

class TestNotifiable
{
    use Notifiable;
}

class TestNotification extends Notification
{
    public function toHablameNotification($notifiable)
    {
        return new HablameMessage('3123456789', 'Mensaje de prueba', '2019-06-20', 'Campaña');
    }
}
