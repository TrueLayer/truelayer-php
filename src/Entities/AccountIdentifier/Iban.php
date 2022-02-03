<?php

declare(strict_types=1);

namespace TrueLayer\Entities\AccountIdentifier;

use TrueLayer\Constants\AccountIdentifierTypes;
use TrueLayer\Entities\Entity;
use TrueLayer\Interfaces\AccountIdentifier\IbanInterface;

final class Iban extends Entity implements IbanInterface
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
     * @var string[]
     */
    protected array $rules = [
        'iban' => 'required|alpha_num|max:34|min:4',
    ];

    /**
     * @return string
     */
    public function getIban(): string
    {
        return $this->iban;
    }

    /**
     * @param string $iban
     *
     * @return IbanInterface
     */
    public function iban(string $iban): IbanInterface
    {
        $this->iban = $iban;

        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return AccountIdentifierTypes::IBAN;
    }
}
