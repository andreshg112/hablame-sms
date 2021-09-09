<?php

namespace Andreshg112\HablameSms;

class HablameMessage
{
    private ?string $phoneNumbers = null;

    private ?string $sms = null;

    private ?string $datetime = null;

    public function __construct(
        string $phoneNumber = null,
        string $sms = null,
        string $datetime = null
    ) {
        $this->phoneNumbers = $phoneNumber;

        $this->sms = $sms;

        $this->datetime = $datetime;
    }

    /**
     * Números telefónicos a enviar SMS separados por coma.
     *
     * @param  string  $phoneNumbers
     * @return $this
     */
    public function phoneNumbers(string $phoneNumbers): self
    {
        $this->phoneNumbers = $phoneNumbers;

        return $this;
    }

    /**
     * Mensaje de texto a enviar.
     *
     * @param  string  $sms
     * @return $this
     */
    public function sms(string $sms): self
    {
        $this->sms = $sms;

        return $this;
    }

    /**
     * [optional] Fecha de envío. Si está vacío, se envía inmediatamente.
     * (Ejemplo: 2017-12-31 23:59:59).
     *
     * @param  string|null  $datetime
     * @return $this
     */
    public function datetime(string $datetime = null): self
    {
        $this->datetime = $datetime;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'phoneNumbers' => $this->phoneNumbers,
            'sms' => $this->sms,
            'sendDate' => $this->datetime,
        ];
    }
}
