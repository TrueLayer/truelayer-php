<?php

declare(strict_types=1);

namespace TrueLayer\Models\SchemeIdentifier;

use TrueLayer\Contracts\SchemeIdentifier\IbanInterface;
use TrueLayer\Models\Model;

final class Iban extends Model implements IbanInterface
{
    /**
     * @var string
     */
    protected string $iban;

    /**
     * @var string[]
     */
    protected array $arrayFields = [
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
}
