<?php

declare(strict_types=1);

namespace TrueLayer\Entities\SchemeIdentifier;

use TrueLayer\Interfaces\SchemeIdentifier\BbanInterface;
use TrueLayer\Entities\Entity;

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
}
