<?php

namespace Andreshg112\HablameSms\Tests;

use Andreshg112\HablameSms\HablameMessage;
use Illuminate\Foundation\Testing\WithFaker;
use Orchestra\Testbench\TestCase;

class MessageTest extends TestCase
{
    use WithFaker;

    public function setUp(): void
    {
        $this->setUpFaker();

        parent::setUp();
    }

    /** @test */
    public function it_accepts_attributes_when_constructing_a_message()
    {
        $attributes = [
            'numero' => $this->faker->randomNumber(8),
            'sms' => $this->faker->sentence,
            'fecha' => $this->faker->date('Y-m-d H:i:s'),
            'referencia' => $this->faker->word,
        ];

        $message = new HablameMessage(
            $attributes['numero'],
            $attributes['sms'],
            $attributes['fecha'],
            $attributes['referencia']
        );

        $this->assertEquals($attributes, $message->toArray());
    }

    /** @test */
    public function it_accepts_attributes_one_by_one()
    {
        $attributes = [
            'numero' => $this->faker->randomNumber(8),
            'sms' => $this->faker->sentence,
            'fecha' => $this->faker->date('Y-m-d H:i:s'),
            'referencia' => $this->faker->word,
        ];

        $message = (new HablameMessage())
            ->phoneNumbers($attributes['numero'])
            ->sms($attributes['sms'])
            ->datetime($attributes['fecha'])
            ->reference($attributes['referencia']);

        $this->assertEquals($attributes, $message->toArray());
    }
}
