<?php

declare(strict_types=1);

namespace TrueLayer\Entities\AccountIdentifier;

use TrueLayer\Constants\AccountIdentifierTypes;
use TrueLayer\Entities\Entity;
use TrueLayer\Interfaces\AccountIdentifier\BbanInterface;

final class Bban extends Entity implements BbanInterface
{
    /**
     * @var string
     */
    protected string $bban;

    /**
     * @var string[]
     */
    protected array $arrayFields = [
        'type',
        'bban',
    ];

    /**
     * @var string[]
     */
    protected array $rules = [
        'bban' => 'required|alpha_num|max:30',
    ];

    /**
     * @return string
     */
    public function getBban(): string
    {
        return $this->bban;
    }

    /**
     * @param string $bban
     *
     * @return BbanInterface
     */
    public function bban(string $bban): BbanInterface
    {
        $this->bban = $bban;

        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return AccountIdentifierTypes::BBAN;
    }
}
