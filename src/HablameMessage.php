<?php

namespace Andreshg112\HablameSms;

class HablameMessage
{
    private ?string $phoneNumber = null;

    private ?string $sms = null;

    private ?string $datetime = null;

    private ?string $reference = null;

    /**
     * Creates the instance.
     *
     * @param string|null $phoneNumber
     * @param string|null $sms
     * @param string|null $datetime
     * @param string|null $reference
     */
    public function __construct(
        string $phoneNumber = null,
        string $sms = null,
        string $datetime = null,
        string $reference = null
    ) {
        $this->phoneNumber = $phoneNumber;

        $this->sms = $sms;

        $this->datetime = $datetime;

        $this->reference = $reference;
    }

    /**
     * Número telefonico a enviar SMS.
     *
     * @param string $phoneNumber
     * @return  $this
     */
    public function phoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * Mensaje de texto a enviar.
     *
     * @param string $sms
     * @return  $this
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
     * @param string|null $datetime
     * @return  $this
     */
    public function datetime(string $datetime = null): self
    {
        $this->datetime = $datetime;

        return $this;
    }

    /**
     * [optional] Número de reference o nombre de campaña.
     *
     * @param string|null $reference
     * @return  $this
     */
    public function reference(string $reference = null): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'toNumber' => $this->phoneNumber,
            'sms' => $this->sms,
            'sendDate' => $this->datetime,
            'reference' => $this->reference,
        ];
    }
}
