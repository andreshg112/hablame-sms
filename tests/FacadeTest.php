<?php

namespace Andreshg112\HablameSms\Tests;

use Andreshg112\HablameSms\Facade;
use Andreshg112\HablameSms\HablameSmsServiceProvider;
use Illuminate\Support\Facades\Config;
use Orchestra\Testbench\TestCase;

class FacadeTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Config::set('services.hablame_sms.cliente', '12345678');

        Config::set('services.hablame_sms.api', '9876543210');
    }

    protected function getPackageProviders($app)
    {
        return [HablameSmsServiceProvider::class];
    }

    /** @test */
    public function it_checks_the_balance()
    {
        $mockedResponse = [
            'resultado' => 0,
            'resultado_t' => '',
            'cliente' => '12345678',
            'saldo' => '102657.0000000',
            'credito' => '0.00000',
            'bloqueo' => '0',
        ];

        Facade::shouldReceive('checkBalance')
            ->once()
            ->andReturns($mockedResponse);

        $response = Facade::checkBalance();

        $this->assertArraySubset($mockedResponse, $response);
    }

    /** @test */
    public function it_sends_a_message()
    {
        $phoneNumber = '573123456789';

        $sms = 'Mensaje de prueba';

        $datetime = '2019-06-12 22:39:58';

        $reference = 'Campaña promocional';

        $mockedResponse = [
            'cliente' => '12345678',
            'lote_id' => 0,
            'fecha_recepcion' => '2019-06-12 22:39:58',
            'resultado' => 0,
            'resultado_t' => null,
            'sms_procesados' => 1,
            'referecia' => $reference,
            'ip' => '190.9.191.119',
            'sms' => [
                '1' => [
                    'id' => '1234567890123456',
                    'numero' => $phoneNumber,
                    'sms' => $sms,
                    'fecha_envio' => $datetime,
                    'ind_area_nom' => 'Colombia Celular',
                    'precio_sms' => '9.00000',
                    'resultado_t' => '',
                    'resultado' => '0',
                ],
            ],
        ];

        Facade::shouldReceive('sendMessage')
            ->with($phoneNumber, $sms, $datetime, $reference)
            ->once()
            ->andReturns($mockedResponse);

        $response = Facade::sendMessage($phoneNumber, $sms, $datetime, $reference);

        $this->assertArraySubset($mockedResponse, $response);
    }
}
