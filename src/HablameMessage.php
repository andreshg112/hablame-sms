<?php

namespace Andreshg112\HablameSms;

class HablameMessage
{
    /** @var string|null $phoneNumbers */
    protected $phoneNumbers = null;

    /** @var string|null $sms */
    protected $sms = null;

    /** @var string|null $datetime */
    protected $datetime = null;

    /** @var string|null $reference */
    protected $reference = null;

    /**
     * Creates the instance.
     *
     * @param string|null $phoneNumbers
     * @param string|null $sms
     * @param string|null $datetime
     * @param string|null $reference
     */
    public function __construct(
        string $phoneNumbers = null,
        string $sms = null,
        string $datetime = null,
        string $reference = null
    ) {
        $this->phoneNumbers = $phoneNumbers;

        $this->sms = $sms;

        $this->datetime = $datetime;

        $this->reference = $reference;
    }

    /**
     * Número(s) telefonico(s) a enviar SMS (separados por una coma).
     *
     * @param string $phoneNumbers
     * @return  $this
     */
    public function phoneNumbers(string $phoneNumbers): self
    {
        $this->phoneNumbers = $phoneNumbers;

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
     * (Ejemplo: 2017-12-31 23:59:59)
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
            'numero' => $this->phoneNumbers,
            'sms' => $this->sms,
            'fecha' => $this->datetime,
            'referencia' => $this->reference,
        ];
    }
}
