<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Remitter;

use TrueLayer\Constants\AccountIdentifierTypes;
use TrueLayer\Entities\Entity;
use TrueLayer\Interfaces\AccountIdentifier\IbanInterface;
use TrueLayer\Interfaces\Remitter\IbanRemitterInterface;

class IbanRemitter extends Entity implements IbanRemitterInterface
{
    /**
     * @var string
     */
    protected string $iban;

    /**
     * @var string[]
     */
    protected array $arrayFields = [
        'type',
        'iban',
    ];

    /**
     * @return string
     */
    public function getType(): string
    {
        return AccountIdentifierTypes::IBAN;
    }

    /**
     * @return string
     */
    public function getIban(): string
    {
        return $this->iban;
    }

    /**
     * @param string $iban
     * @return IbanInterface
     */
    public function iban(string $iban): IbanInterface
    {
        $this->iban = $iban;
        return $this;
    }
}
